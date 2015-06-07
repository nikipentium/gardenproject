<?php
include_once ("..\php_includes\db_conx.php");
//called by ajax trending_tags.js
//accesses user_tag and tags table in database
//responds with tag_id , tag_name and tag_count for all users in ASC order
$response = array();
$i=0;
$sqlCommand = "SELECT tag_id,title FROM tags ORDER BY tag_id ASC LIMIT 30";
$result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
    $tag_id = $row['tag_id'];
    $tag_name = $row['title'];
    $sqlCommand = "SELECT SUM(tag_count) FROM user_tag WHERE tag_id = $tag_id";
    $result2 = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
    $row2 = mysqli_fetch_array($result2, MYSQLI_BOTH);
    if($row2[0]!=null){
        $tag_count = $row2[0];
        $response['tag'+$i]['tag_id'] = $tag_id;
        $response['tag'+$i]['tag_name'] = $tag_name;
        $response['tag'+$i]['tag_count'] = $tag_count;
        $i++;
    }
}
//sort the array according to tag count
print_r(json_encode($response));
?>