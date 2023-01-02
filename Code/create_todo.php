<?php
session_start();
session_regenerate_id();

if (isset($_SESSION['user_ID'])) {
    // is admin logged in?
    if ($_SESSION['user_ID'] == 1) {
        header('Location: admin.php');
    }
} else {
    header('Location: login.php');
}
$user_ID = $_SESSION['user_ID'];

include('includes/dbconnector.inc.php');

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)) {
    echo var_dump($_POST);

    // title
    if (isset($_POST['title']) && !empty(trim($_POST['title'])) && strlen(trim($_POST['title'])) <= 50) {
        $title = htmlspecialchars(trim($_POST['title']));
    } else {
        $error .= "Geben Sie bitte einen richtigen Titel an.<br />";
    }

    // category_ID
    if (isset($_POST['category_ID'])) {
        $category_ID = $_POST['category_ID'];
    } else {
        $error .= "Dropdown Kategorie eingabe Falsch.<br />";
    }

    // priority
    if (isset($_POST['priority'])) {
        $priority = $_POST['priority'];
    } else {
        $error .= "Dropdown Priorität eingabe Falsch.<br />";
    }

    // description
    if (isset($_POST['description']) && !empty(trim($_POST['description'])) && strlen(trim($_POST['description'])) <= 1000) {
        $description = htmlspecialchars(trim($_POST['description']));
    } else {
        $error .= "Geben Sie bitte einen richtigen Titel an.<br />";
    }

    include('functions/date.php');

    // create_date
    if (checkDateInput($_POST['create_date'])) {
        $create_date = trim($_POST['expire_date']);
    } else {
        $error .= "Geben Sie bitte ein richtiges Datum an.<br />";
    }

    // expire_date
    if (checkDateInput($_POST['expire_date'])) {
        $expire_date = trim($_POST['expire_date']);
    } else {
        $error .= "Geben Sie bitte ein richtiges Datum an.<br />";
    }

    $status = 0;
    $in_archiv = 0;

    if (empty($error)) {

        $query = "INSERT INTO todo (priority, title, description, create_date, expire_date, Status, In_Archiv, category_category_ID, user_user_ID) values (?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($query);

        if (!$stmt === false) {

            $stmt->bind_param('issssiiii', $priority, $title, $description, $create_date, $expire_date, $status, $in_archiv, $category_ID, $user_ID);

            if ($stmt->execute()) {
                $priority = $title = $description = $create_date = $expire_date = $Status = $In_Archiv = $category_ID = $user_ID =  '';
                $conn->close();
                header('Location: index.php');
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
            <h1 class="title">Neues ToDo erstellen</h1>
            <div class="error">
                <?php
                if (isset($error)) {
                    if (!empty($error)) {
                        echo "<div class='alert alert-danger' role='alert'>$error</div>";
                    }
                } ?>

            </div>

            <form action="create_todo.php" method="post">
                <!-- title -->
                <div class="form-group">
                    <input type="text" name="title" class="form-control" placeholder="Titel*" maxlength="30" required="true">
                </div>
                <!-- category -->
                <div class="form-group">
                    <select class="form-control" name="category_ID" required="true">
                        <option value="" name="category_ID" disabled selected>Wähle Kategorie...</option>
                        <?php
                        $user_ID = $_SESSION['user_ID'];
                        $query = 'SELECT category.category_name, user_has_category.category_category_ID FROM category, user_has_category WHERE category.category_ID = user_has_category.category_category_ID AND user_has_category.user_user_ID = ?';
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $user_ID);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            $category_name = $row['category_name'];
                            $category_ID = $row['category_category_ID'];
                            echo "<option name='category_ID' value='$category_ID'>$category_name</option>";
                        } ?>
                    </select>
                </div>
                <!-- priority -->
                <div class="form-group">
                    <select name="priority" class="form-control" required="true">
                        <option value=" " name="priority" disabled selected>Wähle Priorität...</option>
                        <option value="1" name="priority">1</option>
                        <option value="2" name="priority">2</option>
                        <option value="3" name="priority">3</option>
                        <option value="4" name="priority">4</option>
                        <option value="5" name="priority">5</option>
                    </select>
                </div>
                <!-- description -->
                <div class="form-group">
                    <textarea name="description" rows="4" class="form-control" placeholder="Beschreibung*" maxlength="1000" required="true"></textarea>
                </div>
                <!-- create_date -->
                <div class="form-group">
                    <input type="date" name="create_date" class="form-control" required="true">
                </div>
                <!-- expire_date -->
                <div class="form-group">
                    <input type="date" name="expire_date" class="form-control" required="true">
                </div>
                <!-- user_ID -->
                <input type="hidden" name="user_ID" value="<?php echo $user_ID; ?>">

                <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
                <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
            </form>
        </div>
        <?php include('includes/footer.inc.php'); ?>
    </div>
</body>

</html>