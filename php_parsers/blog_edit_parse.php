<?php
include_once "../php_includes/check_login_status.php";
/* store blog data (sent using ajax) in database */

    
// You may want to obtain refering site name that this post came from for security purposes here
// exit the script if it is not from your site and script
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ajax.send("blogTitle="+blogTitle+"&blogLink="+blogLink+"&blogBody="+blogBody+"&pid="+pid+"&blogTags="+jsonTags);
$pagetitle = $_POST['blogTitle'];
$linklabel = $_POST['blogLink'];
$pagebody = $_POST['blogBody'];
$blogJsonTags = $_POST['blogTags'];
$pageid = $_POST['pid'];
//delete tags associated with the blog
$sqlCommand = "DELETE FROM blog_tags where blog_id = $pageid";
$result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
function store($tag,$blog_id,$db_conx){
    //check if tag already exists in database tags 
    $sqlCommand = "SELECT tag_id FROM tags WHERE title = '$tag'";
    $result = mysqli_query($db_conx, $sqlCommand);
    $num_rows = mysqli_num_rows($result); 
    if($num_rows<1){ // tag does not exist
        //store $tag in tags database
        $sqlCommand = "INSERT INTO tags (title) VALUES ('$tag')";
        $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));     
    }
    // get the tag_id
    $sqlCommand = "SELECT tag_id FROM tags WHERE title = '$tag'";
    $result = mysqli_query($db_conx, $sqlCommand);
    $row = mysqli_fetch_array($result,MYSQL_ASSOC);
    $tag_id=$row["tag_id"];
    //store the tag id and the blog id in the blog tags table
    $sqlCommand = "INSERT INTO blog_tags (blog_id,tag_id) VALUES ('$blog_id','$tag_id')";
    $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
}
//decode the blog json tag
$blogTags = json_decode($blogJsonTags);
foreach ($blogTags as $key => $value) { 
    foreach ($value as $v) { 
        store($v,$pageid,$db_conx);
    }
}
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
echo "$pageid|success";
exit();
?>