<?php
session_start();
session_regenerate_id();

$error = '';

// check if user has permission for this page
if (isset($_SESSION['user_ID'])) {
    if ($_SESSION['user_ID'] == 1) {
        header('Location: admin.php');
    }
} else {
    header('Location: login.php');
}

include('includes/dbconnector.inc.php');

if (isset($_GET['todo_ID'])) {
    $todo_ID = $_GET['todo_ID'];
    $query = 'UPDATE `todo` SET `In_Archiv`= ? WHERE `todo_ID` = ?';
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        $error .= $conn->error;
    }
    $one = 1;
    $stmt->bind_param('ii', $one, $todo_ID);
    if ($stmt->execute()) {
        //header('Location: index.php');
    } else {
        $error .= $conn->error;
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
            <h1 class="title">Archiv</h1>
            <div class="error">
                <?php
                if (isset($error)) {
                    if (!empty($error)) {
                        echo "<div class='alert alert-danger' role='alert'>$error</div>";
                    }
                } ?>
            </div>

            <table class="table table-striped">
                <tr class="table-row-header active">
                    <th class="table-row-header-col id">#</th>
                    <th class="table-row-header-col title">Titel</th>
                    <th class="table-row-header-col priority">Priorität</th>
                    <th class="table-row-header-col kategorie">Kategorie</th>
                    <!--<th class="table-row-header-col todo-description">Beschreibung</th> -->
                    <th class="table-row-header-col create-date">Erstellt</th>
                    <th class="table-row-header-col expire_date">Fällig</th>
                    <th class="table-row-header-col status">Status</th>
                    <th class="table-row-header-col edit">Bearbeiten</th>
                    <th class="table-row-header-col delete">Löschen</th>
                </tr>
                <?php
                $query = "SELECT * FROM `todo`";

                $result = $conn->query($query);
                $todo_ID = 0;
                while ($row = $result->fetch_assoc()) {
                    $todo_ID++;

                    $category_ID = $row['category_category_ID'];
                    $category_query = 'SELECT * FROM `category` WHERE category_ID = ?';

                    $category_stmt = $conn->prepare($category_query);
                    $category_stmt->bind_param('i', $category_ID);
                    if ($category_stmt->execute()) {
                        $category_result = $category_stmt->get_result();
                        $row2 = $category_result->fetch_assoc();
                        $category_name = $row2['category_name'];
                    } else {
                        $error .= $conn->error;
                    }
                    $real_todo_ID = $row['todo_ID'];

                    $title = $row['title'];
                    $priority = $row['priority'];
                    //$description = $row['description'];
                    $create_date = $row['create_date'];
                    $expire_date = $row['expire_date'];
                    $status = $row['Status'];
                    if ($row['In_Archiv'] == 1) {
                        echo "<tr class='table-row active'>";
                        echo    "<td class='table-row-col id'>$todo_ID</td>";
                        echo    "<td class='table-row-col title'>$title</td>";
                        echo    "<td class='table-row-col'>$priority</td>";
                        echo    "<td class='table-row-col'>$category_name</td>";
                        // echo    "<td class='table-row-col'>$description</td>";
                        echo    "<td class='table-row-col'>$create_date</td>";
                        echo    "<td class='table-row-col'>$expire_date</td>";
                        echo    "<td class='table-row-col'> $status%</td>";
                        echo    "<td class='table-row-col button-edit'><a href='edit_todo.inc.php?todo_ID=$real_todo_ID' class='btn btn-info'><img src='images/edit.png' class='table-row-col-button-edit-img' alt='Bearbeiten'></a></td>";
                        echo    "<td class='table-row-col button-delete'><a href='includes/delete_todo.inc.php?todo_ID=$real_todo_ID' class='btn btn-danger delete-user'><img src='images/delete.svg' class='table-row-col-button-delete-img' alt='Löschen'></a></td>";
                        echo "</tr>";
                    }
                } ?>
            </table>
        </div>
        <?php include('includes/footer.inc.php'); ?>
    </div>
</body>

</html>