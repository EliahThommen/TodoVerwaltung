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
$todo_ID = '';
$title = '';
$category_ID = '';
$priority = '';
$description = '';
$create_date = '';
$expire_date = '';
$in_archiv = '';
$status = '';

if ($_SERVER["REQUEST_METHOD"] == "GET" && empty($error)) {
    $todo_ID = $_GET['todo_ID'];

    $query = 'SELECT * FROM `todo` WHERE todo_ID = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $todo_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $title = $row['title'];
    $category_ID = $row['category_category_ID'];
    $priority = $row['priority'];
    $description = $row['description'];
    $create_date_array = explode(' ', $row['create_date']);
    $create_date = $create_date_array[0];
    $expire_date_array = explode(' ', $row['expire_date']);
    $expire_date = $expire_date_array[0];
    $in_archiv = $row['In_Archiv'];
    $status = $row['Status'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)) {
    // var_dump($_POST);
    // title
    if (isset($_POST['title']) && !empty(trim($_POST['title'])) && strlen(trim($_POST['title'])) <= 50) {
        $title = htmlspecialchars(trim($_POST['title']));
    } else {
        $error .= "Geben Sie bitte einen richtigen Titel an.<br />";
    }

    // priority
    if (isset($_POST['priority'])) {
        $priority = intval($_POST['priority'], 10);
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

    if (isset($_POST['status'])) {
        $status = $_POST['status'];
    } else {
        $error .= "Geben Sie bitte ein richtiger Status an.<br />";
    }

    if (isset($_POST['todo_ID'])) {
        $todo_ID = $_POST['todo_ID'];
    }

    if (empty($error)) {
        echo $todo_ID;
        echo "UPDATE todo SET priority = $priority, title = $title, description = $description, create_date = $create_date, expire_date = $expire_date, status = $status WHERE todo_ID = $todo_ID";

        $query = "UPDATE todo SET priority = ?, title = ?, description = ?, create_date = ?, expire_date = ?, status = ? WHERE todo_ID = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt === false) {

            $stmt->bind_param('issssii', $priority, $title, $description, $create_date, $expire_date, $status, $todo_ID);

            if ($stmt->execute()) {
                $priority = $title = $description = $create_date = $expire_date = $Status = $In_Archiv = $category_ID = $todo_ID =  '';
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
            <h1 class="title">ToDo bearbeiten</h1>
            <div class="error">
                <?php
                if (isset($error)) {
                    if (!empty($error)) {
                        echo "<div class='alert alert-danger' role='alert'>$error</div>";
                    }
                } ?>

            </div>

            <form action="edit_todo.php" method="post">
                <!-- title -->
                <div class="form-group">
                    <input type="text" name="title" class="form-control" placeholder="Titel*" maxlength="30" required="true" value="<?php echo $title ?>">
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
                    <textarea name="description" rows="4" class="form-control" placeholder="Beschreibung*" maxlength="1000" required="true"><?php echo $description ?></textarea>
                </div>
                <!-- status -->
                <div class="form-group">
                    <span>Status <input type="number" name="status" max="100" accuracy="2" min="0" style="text-align:left;" onkeydown="return false" value="<?php echo $status ?>">%</span>
                </div>
                <!-- create_date -->
                <div class="form-group">
                    <input type="date" name="create_date" class="form-control" required="true" value="<?php echo $create_date ?>">
                </div>
                <!-- expire_date -->
                <div class="form-group">
                    <input type="date" name="expire_date" class="form-control" required="true" value="<?php echo $create_date ?>">
                </div>
                <!-- user_ID -->
                <input type="hidden" name="user_ID" value="<?php echo $user_ID; ?>">


                <input type="hidden" name="todo_ID" value="<?php echo $todo_ID; ?>">



                <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
                <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
            </form>
        </div>
        <?php include('includes/footer.inc.php'); ?>
    </div>
</body>

</html>