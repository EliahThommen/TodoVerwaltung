<?php
session_start();
session_regenerate_id();

//check if user has permission for this page
if (isset($_SESSION['user_ID'])) {
    if ($_SESSION['user_ID'] == 1) {
        header('Location: admin.php');
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
            <?php if (isset($_GET['search_field'])) {
                $search = $_GET['search_field'];
                echo "<h1 class='title'>Suche nach ($search)</h1>";
            } else {
                echo "<h1 class='title'>To Do</h1>";
            }
            ?>

            <table class="table table-striped">
                <tr class="table-row-header active">
                    <th class="table-row-header-col id">#</th>
                    <th class="table-row-header-col title">Titel</th>
                    <th class="table-row-header-col priority">Priorität</th>
                    <th class="table-row-header-col kategorie">Kategorie</th>
                    <th class="table-row-header-col create-date">Erstellt</th>
                    <th class="table-row-header-col expire_date">Fällig</th>
                    <th class="table-row-header-col status">Status</th>
                    <th class="table-row-header-col archiv">Archivieren</th>
                    <th class="table-row-header-col edit">Bearbeiten</th>
                    <th class="table-row-header-col delete">Löschen</th>
                </tr>
                <?php
                if (isset($_GET['search_field'])) {
                    $search = $_GET['search_field'];
                    $search_percent = "%$search%";
                    $query = "SELECT * FROM todo WHERE title LIKE ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $search_percent);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $query = "SELECT * FROM `todo` ORDER BY priority DESC, expire_date ASC";
                    $result = $conn->query($query);
                }

                $todo_ID = 0;
                while ($row = $result->fetch_assoc()) {
                    $category_ID = $row['category_category_ID'];
                    $category_query = 'SELECT * FROM category LEFT JOIN user_has_category ON user_has_category.category_category_ID = category.category_ID WHERE category.category_ID = ? AND user_has_category.user_user_ID = ?';

                    $category_stmt = $conn->prepare($category_query);
                    $category_stmt->bind_param('ii', $category_ID, $user_ID);
                    if ($category_stmt->execute()) {
                        $category_result = $category_stmt->get_result();
                        $row2 = $category_result->fetch_assoc();
                        if (isset($row2['category_name'])) {
                            $category_name = $row2['category_name'];
                        }
                    } else {
                        $error .= $conn->error;
                    }

                    if (isset($row2['category_name'])) {
                        $real_todo_ID = $row['todo_ID'];


                        $title = $row['title'];
                        $priority = $row['priority'];
                        $description = $row['description'];
                        $create_date_array = explode(" ", $row['create_date']);
                        $create_date = $create_date_array[0];
                        $expire_date_array = explode(" ", $row['expire_date']);
                        $expire_date = $expire_date_array[0];
                        $status = $row['Status'];
                        $user_user_ID = $row['user_user_ID'];

                        if ($row['In_Archiv'] == 0) {
                            $todo_ID++;
                            echo "<tr class='table-row active'>";
                            echo    "<td class='table-row-col id'>$todo_ID</td>";
                            echo    "<td class='table-row-col title'><b>$title</b></td>";
                            echo    "<td class='table-row-col'>$priority</td>";
                            echo    "<td class='table-row-col'>$category_name</td>";
                            echo    "<td class='table-row-col'>$create_date</td>";
                            echo    "<td class='table-row-col'>$expire_date</td>";
                            echo    "<td class='table-row-col'> $status%</td>";

                            if ($user_user_ID == $_SESSION['user_ID']) {
                                echo    "<td class='table-row-col button-archive'><a href='archiv.php?todo_ID=$real_todo_ID' class='btn btn-default delete-user'><img src='images/archiv.png' class='table-row-col-button-delete-img' alt='Löschen'></a></td>";
                                echo    "<td class='table-row-col button-edit'><a href='edit_todo.php?todo_ID=$real_todo_ID' class='btn btn-info'><img src='images/edit.png' class='table-row-col-button-edit-img' alt='Bearbeiten'></a></td>";
                                echo    "<td class='table-row-col button-delete'><a href='includes/delete_todo.inc.php?todo_ID=$real_todo_ID' class='btn btn-danger delete-user'><img src='images/delete.svg' class='table-row-col-button-delete-img' alt='Löschen'></a></td>";
                            } else {
                                echo    "<td class='table-row-col button-archiv'></a></td>";
                                echo    "<td class='table-row-col button-edit'></a></td>";
                                echo    "<td class='table-row-col button-delete'></a></td>";
                            }
                            echo "</tr>";
                        }
                    }
                }
                ?>
            </table>
        </div>
        <?php include('includes/footer.inc.php'); ?>
    </div>
</body>

</html>