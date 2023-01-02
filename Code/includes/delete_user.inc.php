<?php
session_start();
session_regenerate_id();

include('dbconnector.inc.php');

if (isset($_GET['userID'])) {
    $error = '';
    $message = '';
    $user_ID = $_GET['userID'];

    if ($user_ID > 0) {
        $query = 'DELETE FROM user WHERE user_ID = ?';

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            $error .= $conn->error;
        }

        if ($stmt->bind_param("s", $user_ID)) {

            if ($stmt->execute()) {

                $conn->close();
                header('Location: ../admin.php');
            } else {
                $error .= $conn->error;
            }
        } else {
            $error .= $conn->error;
        }
    }
}

//check if user has permission for this page
if (isset($_SESSION['user_ID'])) {
    if ($_SESSION['user_ID'] == 1) {
        header('Location: ../admin.php');
    }
    if ($_SESSION['user_ID'] >= 2) {
        header('Location: ../index.php');
    }
} else {
    header('Location: ../login.php');
}
