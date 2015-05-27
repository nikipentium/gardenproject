<?php // Ajax calls this EMAIL CHECK code to execute
include_once ("..\php_includes\db_conx.php");
if (isset($_POST["emailcheck"])) {
    $email = $_POST['emailcheck'];
    $sql = "SELECT id FROM users WHERE email='$email' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $email_check = mysqli_num_rows($query);
    if ($email_check < 1) {//email not taken
        echo 'true';
        exit();
    } else {//email taken
        echo 'false';
        exit();
    }
}
?>