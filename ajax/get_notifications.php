<?php
/* SELECT note FROM notifications WHERE did_read = 0 (UN-READ) in notifications table 
 * respond to request with note of unread notifications
 */
include_once ("..\php_includes\check_login_status.php");
$sqlCommand = "SELECT note FROM notifications WHERE username = '$log_username' AND did_read='0' AND (app='Blog Status Post' OR app='Blog Reply Post') ORDER BY date_time DESC";
$result = mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
$response = array();
$i = 0;
while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
    $response[$i++] = $row['note'];
}
echo json_encode($response);
?>
