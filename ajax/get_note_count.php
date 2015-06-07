<?php
/* COUNT did_read = 0 (UN-READ) in notifications table 
 * respond to request with count of unread notifications
 */
include_once ("..\php_includes\check_login_status.php");
$sqlCommand = "SELECT COUNT(did_read) FROM notifications WHERE username = '$log_username' AND did_read='0' AND (app='Blog Status Post' or app='Blog Reply Post')";
$result = mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
$row = mysqli_fetch_array($result, MYSQLI_BOTH);
echo $row[0];
?>