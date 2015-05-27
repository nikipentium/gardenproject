<?php
include_once "../php_includes/check_login_status.php";
/* store blog data (sent using ajax) in database */

    
// You may want to obtain refering site name that this post came from for security purposes here
// exit the script if it is not from your site and script
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$pagetitle = $_POST['blogTitle'];
$linklabel = $_POST['blogLink'];
$pagebody = $_POST['blogBody'];
$blogTags = $_POST['blogTags'];
$pageid = $_POST['pid'];
// Filter Function -------------------------------------------------------------------
function filterFunction ($var) { 
    $var = nl2br(htmlspecialchars($var));
    $var = eregi_replace("'", "&#39;", $var);
    $var = eregi_replace("`", "&#39;", $var);
    return $var; 
} 
$pagetitle = filterFunction($pagetitle);
$linklabel = filterFunction($linklabel);
$pagebody = filterFunction($pagebody);
// End Filter Function --------------------------------------------------------------

// Add the info into the database table
$sqlcommand="UPDATE pages SET pagetitle='$pagetitle', linklabel='$linklabel', pagebody='$pagebody' WHERE id='$pageid'";
$query = mysqli_query($db_conx,$sqlcommand)  or die (mysqli_error($db_conx));
//need to return page id of the blog so that blogger can view it in another page
echo 'success';
exit();
?>