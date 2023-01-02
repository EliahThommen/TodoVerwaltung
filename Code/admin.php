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
<?php include('includes/head.inc.php'); ?>

<body>
    <div class="wrapper">
        <?php include('includes/header.inc.php'); ?>

        <div class="content">
            <h1 class="title">Admin Panel</h1>

            <!-- User -->
            <table class="table table-striped">
                <caption>Benutzer</caption>
                <tr class="table-row-header active">
                    <th class="table-row-header-col id">#</th>
                    <th class="table-row-header-col firstname">Vorname</th>
                    <th class="table-row-header-col lastname">Nachname</th>
                    <th class="table-row-header-col username">Nutzername</th>
                    <th class="table-row-header-col email">E-Mail</th>
                    <th class="table-row-header-col ">Löschen</th>
                    <th class="table-row-header-col ">Kategorien</th>
                </tr>
                <?php
                $query = "SELECT * FROM `user`";

                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    $userID = $row['user_ID'];
                    $firstname = $row['firstname'];
                    $lastname = $row['lastname'];
                    $username = $row['username'];
                    $email = $row['email'];
                    echo "<tr class='table-row active'>";
                    echo    "<td class='table-row-col id'>$userID</td>";
                    echo    "<td class='table-row-col firstname'>$firstname</td>";
                    echo    "<td class='table-row-col lastname'>$lastname</td>";
                    echo    "<td class='table-row-col username'>$username</td>";
                    echo    "<td class='table-row-col email'>$email</td>";
                    if ($userID > 1) {
                        echo    "<td class='table-row-col button-delete'><a href='includes/delete_user.inc.php?userID=$userID' class='btn btn-danger delete-user'><img src='images/delete.svg' class='table-row-col-button-delete-img' alt='Löschen'></a></td>";
                        echo    "<td class='table-row-col categories'><a href='categories.php?userID=$userID&username=$username' class='btn btn-info categories-btn'>Kategorien zuweisen</a></td>";
                    } else {
                        echo    "<td class='table-row-col button-delete'></td>";
                        echo    "<td class='table-row-col categories'></td>";
                    }
                    echo "</tr>";
                } ?>
            </table>



            <!-- Categories -->
            <table class="table table-striped">
                <caption>Kategorien</caption>
                <tr class="table-row-header active">
                    <th class="table-row-header-col id">#</th>
                    <th class="table-row-header-col category_name">Name</th>
                </tr>
                <?php
                $query = "SELECT * FROM `category`";

                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    $category_ID = $row['category_ID'];
                    $category_name = $row['category_name'];

                    echo "<tr class='table-row active'>";
                    echo    "<td class='table-row-col id'>$category_ID</td>";
                    echo    "<td class='table-row-col category_name'>$category_name</td>";
                    echo "</tr>";
                } ?>
            </table>


        </div>
        <?php include('includes/footer.inc.php'); ?>
    </div>
</body>

</html>