<?php 
session_start();
include 'includes/config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    //log out activity
    $stmt = $conn->prepare("insert into log_aktivitas (user_id, aktivitas) values (?, ?)");
    $aktivitas = "Logout dari sistem";
    $stmt->bind_param("is", $user_id, $aktivitas);
    $stmt->execute();
}

//destroy all session data
session_unset();
session_destroy();

//redirect to login page
header('Location: index.php?login=true');
exit();



?>