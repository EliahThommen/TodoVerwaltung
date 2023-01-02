<?php
session_start();
session_regenerate_id();

//check if user has permission for this page
if (isset($_SESSION['user_ID'])) {
    if ($_SESSION['user_ID'] == 1) {
        header('Location: admin.php');
    }
    if ($_SESSION['user_ID'] >= 2) {
        header('Location: index.php');
    }
}

include('includes/dbconnector.inc.php');

$error = '';
$username = '';

// form send
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)) {
    // username
    if (!empty(trim($_POST['username']))) {
        $username = htmlspecialchars(trim($_POST['username']));

        if (!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}/", $username) || strlen($username) > 30) {
            $error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";
        }
    } else {
        $error .= "Geben Sie bitte den Benutzername an.<br />";
    }
    // password
    if (!empty(trim($_POST['password']))) {
        $password = htmlspecialchars(trim($_POST['password']));
        // passwort gültig?
        if (!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)) {
            $error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
        }
    } else {
        $error .= "Geben Sie bitte das Passwort an.<br />";
    }

    if (empty($error)) {
        // query
        $query = "SELECT username, password, user_ID from user where username = ?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            echo $conn->error;
        }
        if (!$stmt->bind_param("s", $username)) {
            echo $conn->error;
        }
        if (!$stmt->execute()) {
            echo $conn->error;
        }

        $result = $stmt->get_result();

        if ($result->num_rows) {
            // userdaten lesen -> array
            $row = $result->fetch_assoc();
            // passwort prüfen
            if (password_verify($password, $row['password'])) {
                $login = true;
                $_SESSION["login"] = $login;
                $_SESSION["username"] = $username;
                $_SESSION["user_ID"] = $row['user_ID'];

                // normal user
                if ($_SESSION["user_ID"] != 1) {
                    header('Location: index.php');

                    // admin
                } else if ($_SESSION["user_ID"] == 1) {
                    header('Location: admin.php');
                }
            } else {
                $login = false;
                $error .= "Benutzername oder Passwort sind falsch";
            }
        } else {
            $error .= "Benutzername oder Passwort sind falsch";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include('includes/head.inc.php'); ?>

<body>
    <div class="wrapper">

        <div class="content">
            <h1 class="login">Login</h1>
            <div class="error">
                <?php
                if (isset($login)) {
                    if ($login === false) {
                        echo "<div class='alert alert-danger' role='alert'>$error</div>";
                    }
                } ?>

            </div>

            <form action="login.php" method="POST">

                <!-- username -->
                <div class="form-group">
                    <label for="username">Benutzername *</label>
                    <input type="text" name="username" class="form-control" id="username" placeholder="username" maxlength="30" required="true" pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}" title="Gross- und Keinbuchstaben, min 6 Zeichen.">
                </div>

                <!-- password -->
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="password" required="true" pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="mindestens einen Gross-, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen, mindestens 8 Zeichen lang,keine Umlaute.">
                </div>

                <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
                <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
            </form>
        </div>

        <?php include('includes/footer.inc.php'); ?>
    </div>
</body>

</html>