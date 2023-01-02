<?php
session_start();
session_regenerate_id();

include('dbconnector.inc.php');

if (isset($_GET['todo_ID'])) {
    $error = '';
    $message = '';
    $todo_ID = $_GET['todo_ID'];

    if ($todo_ID > 0) {
        $query = 'DELETE FROM todo WHERE todo_ID = ?';

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            $error .= $conn->error;
        }
        $stmt->bind_param("i", $todo_ID);
        if ($stmt->execute()) {
            $conn->close();
            header('Location: ../index.php');
        } else {
            $error .= $conn->error;
        }
    }
}

//check if user has permission for this page
if (isset($_SESSION['todo_ID'])) {
    if ($_SESSION['todo_ID'] == 1) {
        header('Location: ../admin.php');
    }
    if ($_SESSION['todo_ID'] >= 2) {
        header('Location: ../index.php');
    }
} else {
    header('Location: ../login.php');
}
