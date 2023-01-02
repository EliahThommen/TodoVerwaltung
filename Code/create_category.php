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

include('includes/dbconnector.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php include('includes/head.inc.php');
$error = '';

if (isset($_POST['category'])) {
    if (!empty(trim($_POST['category'])) && strlen(trim($_POST['category'])) <= 30) {
        $category = htmlspecialchars(trim($_POST['category']));
    } else {
        $error .= "Geben Sie bitte einen richtigen Namen an.<br />";
    }

    $query = 'INSERT INTO `category` (`category_name`) VALUES (?)';
    $stmt = $conn->prepare($query);
    if (!$stmt === false) {
        $stmt->bind_param('s', $category);

        if ($stmt->execute()) {
            $conn->close();
            header('Location: admin.php');
        } else {
            $error .= $conn->error;
        }
    } else {
        $error .= $conn->error;
    }
    $conn->close();
}
?>

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

            <form action="create_category.php" method="post">
                <!-- title -->
                <div class="form-group">
                    <input type="text" name="category" class="form-control" placeholder="Name*" maxlength="30" required="true">
                </div>

                <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
            </form>
        </div>
        <?php include('includes/footer.inc.php'); ?>
    </div>
</body>

</html>