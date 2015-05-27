<?php
include_once "../php_includes/check_login_status.php";

$pageid = $_POST['pid'];
$response="";
$blogtags="";
// get the tags first
$sqlcommand="SELECT title FROM tags t,blog_tags bt WHERE bt.blog_id = '$pageid' AND bt.tag_id = t.tag_id";
$query = mysqli_query($db_conx, $sqlcommand) or die (mysqli_error());
while ($row = mysqli_fetch_array($query)) { 
    $blogtags .= "|".$row["title"];
} 
mysqli_free_result($query);  
// get the other content of the blog
$sqlcommand="SELECT pagetitle,linklabel,pagebody FROM pages WHERE id='$pageid'";
$result = mysqli_query($db_conx,$sqlcommand)  or die (mysqli_error($db_conx));
if(mysqli_num_rows($result)>0)
{
    while($row = mysqli_fetch_assoc($result)) {
         $response.= $row["pagetitle"]."|".$row["linklabel"]."|".$row["pagebody"].$blogtags;   
    }
} else {
    echo "0 results";
    exit();
}
        
//need to return page id of the blog so that blogger can view it in another page
echo $response;
exit();
?>