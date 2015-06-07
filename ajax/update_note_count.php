<?php
/* SET did_read = 1 (READ) in notifications table 
 * Get the ID from the request
 */
include_once ("..\php_includes\check_login_status.php");
$id = $_POST['id'];
$sqlCommand = "UPDATE notifications SET did_read ='1' WHERE id = $id";
$result = mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
$row = mysqli_fetch_array($result, MYSQLI_BOTH);
?>