<?php // Ajax calls this NAME CHECK code to execute
include_once ("..\php_includes\db_conx.php");
if (isset($_POST["usernamecheck"])) {
    $username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
    $sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $uname_check = mysqli_num_rows($query);
    if (strlen($username) < 3 || strlen($username) > 16) {//username too big or too small
        echo 'wrongSize';
        exit();
    }
    if (is_numeric($username[0])) {//username invalid
        echo 'invalid';
        exit();
    }
    if ($uname_check < 1) {//username not taken
        echo 'true';
        exit();
    } else {//username taken
        echo 'false';
        exit();
    }
}
?>