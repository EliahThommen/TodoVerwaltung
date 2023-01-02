<?php
session_start();
session_regenerate_id();

//check if user has permission for this page
if (isset($_SESSION['user_ID'])) {
    if ($_SESSION['user_ID'] >= 2) {
        header('Location: index.php');
    }
} else {
    header('Location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //verbindung zur Datenbank Auslagern
    include('includes/dbconnector.inc.php');

    // daten holen
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $error = "";

    // vorname
    if (isset($_POST['firstname']) && !empty(trim($firstname)) && strlen(trim($firstname)) <= 30) {
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $firstname = htmlspecialchars(trim($_POST['firstname']));
    } else {
        $error .= "Geben Sie bitte einen korrekten Vornamen ein.<br />";
    }

    // nachname
    if (isset($_POST['lastname']) && !empty(trim($lastname) && strlen(trim($lastname)) <= 30)) {
        $lastname = htmlspecialchars(trim($_POST['lastname']));
    } else {
        $error .= "Geben Sie bitte einen korrekten Nachnamen ein.<br />";
    }

    // email
    if (isset($_POST['email']) && !empty(trim($email)) && strlen(trim($email)) <= 100) {
        $email = htmlspecialchars(trim($_POST['email']));
        // korrekte emailadresse?
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $error .= "Geben Sie bitte eine korrekte Email-Adresse ein<br />";
        }
    } else {
        $error .= "Geben Sie bitte eine korrekte Email-Adresse ein.<br />";
    }

    // benutzername
    if (isset($_POST['username']) && !empty(trim($username)) && strlen(trim($username)) <= 30) {
        $username = trim($_POST['username']);
        // (minimal 6 Zeichen, Gross- und Kleinbuchstaben)
        if (!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}/", $username)) {
            $error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen korrekten Benutzernamen ein.<br />";
    }

    // passwort
    if (isset($_POST['password']) && !empty(trim($password))) {
        $password = trim($_POST['password']);
        // (minimal 8 Zeichen, Zahlen, Buchstaben, keine Zeilenumbrüche, mindestens ein Gross- und ein Kleinbuchstabe)
        if (!preg_match("/(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)) {
            $error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen korrekten Nachnamen ein.<br />";
    }


    if (empty($error)) {

        $query = "INSERT IGNORE INTO user (firstname, lastname, username, password, email) values (?,?,?,?,?)";
        $stmt = $conn->prepare($query);

        if (!$stmt === false) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param('sssss', $firstname, $lastname, $username, $hashed_password, $email);

            if ($stmt->execute()) {
                // felder leeren
                $username = $password = $firstname = $lastname = $email = $hashed_password =  '';
                $conn->close();
                header('Location: admin.php');
            } else {
                $error .= $conn->error;
            }
        } else {
            $error .= $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('includes/head.inc.php'); ?>

<body>
    <div class="wrapper">
        <?php include('includes/header.inc.php'); ?>

        <div class="content">
            <h1 class="title">Neuer Benutzer hinzufügen: </h1>

            <div class="error">
                <?php
                if (!empty($error)) {
                    echo "<div class='alert alert-danger' role='alert'>$error</div>";
                } ?>
            </div>

            <form action="signup.php" method="post">
                <div class="form-group">
                    <input type="text" name="firstname" class="form-control firstname" placeholder="Vorname*" required="true">
                </div>
                <div class="form-group">
                    <input type="text" name="lastname" class="form-control lastname" placeholder="Nachname*" maxlength="30" required="true">
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control email" placeholder="E-Mail*" maxlength="100" required="true">
                </div>
                <div class="form-group">
                    <input type="text" name="username" class="form-control username" placeholder="Nutzername*" maxlength="30" required="true" pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}" title="Gross- und Keinbuchstaben, min 6 Zeichen.">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control password" placeholder="Passwort*" required="true" pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="mindestens einen Gross-, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen, mindestens 8 Zeichen lang,keine Umlaute.">
                </div>

                <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
                <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>

            </form>
        </div>

        <?php include('includes/footer.inc.php'); ?>
    </div>
</body>

</html>