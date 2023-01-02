<?php
session_start();
session_regenerate_id();

include('includes/dbconnector.inc.php');

if (isset($_GET['userID']) && isset($_GET['username'])) {
    $user_ID = $_GET['userID'];
    $username = $_GET['username'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $user_ID = $_POST['user_ID'];
    $error = '';

    if (isset($_POST['category'])) {
        $categories = $_POST['category'];
    }

    foreach ($categories as $category) {
        $query = 'INSERT INTO `user_has_category` (user_user_ID, category_category_ID) VALUES (? , ?)';

        $stmt = $conn->prepare($query);
        if (!$stmt === false) {
            $stmt->bind_param('ss', $user_ID, $category);

            if ($stmt->execute()) {
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
            <h1>Kategorien zuweisen für <?php echo $username ?></h1>

            <form action="categories.php" method="post">
                <?php

                $query = "SELECT * FROM `category`";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    $category = $row['category_name'];
                    $category_ID = $row['category_ID'];

                    echo "<div class='radio'>";
                    echo    "<label>";
                    echo        "<input type='radio' name='category[]' value='$category_ID'> $category";
                    echo    "</label>";
                    echo "</div>";
                } ?>
                <input type="hidden" name="user_ID" value="<?php echo $user_ID ?>">
                <input type="hidden" name="username" value="<?php echo $username ?>">
                <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
            </form>
        </div>
        <?php include('includes/footer.inc.php'); ?>
    </div>
</body>

</html>