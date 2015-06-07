<?php
     /* pageOwner
      * not pageOwner
      * */
    $status_ui = "";
    $statuslist = "";
    if($pageOwner != "yes"){
        $status_ui = '<textarea id="statustext" class="form-control" onkeyup="statusMax(this,250)" placeholder="Hi '.$log_username.', do you have a query?"></textarea>';
        $status_ui .= '<button class="btn btn-primary" id="statusBtn" onclick="postToStatus(\'status_post\',\'c\',\''.$pageid.'\',\'statustext\')">Post</button>';
    }
?>
<?php
$status_ui = "";
$statuslist = "";
if($pageOwner != "yes"){
    $status_ui = '<textarea id="statustext" class="form-control" onkeyup="statusMax(this,250)" placeholder="Hi '.$log_username.', say about the blog"></textarea>';
    $status_ui .= '<button class="btn btn-primary" id="statusBtn" onclick="postToStatus(\'status_post\',\'b\',\''.$pageid.'\',\'statustext\')">Post</button>';
}
?><?php 
$sql = "SELECT * FROM status WHERE blog_id='$pageid' AND type='a' ORDER BY postdate DESC LIMIT 20";
$query = mysqli_query($db_conx, $sql);
$statusnumrows = mysqli_num_rows($query);
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    $statusid = $row["id"];
    $blog_id = $row["blog_id"];
    $author = $row["author"];
    $postdate = $row["postdate"];
    $data = $row["data"];
    $data = nl2br($data);
    $data = str_replace("&amp;","&",$data);
    $data = stripslashes($data);
    // GATHER UP ANY STATUS REPLIES
    $status_replies = "";
    $query_replies = mysqli_query($db_conx, "SELECT * FROM status WHERE osid='$statusid' AND type='b' ORDER BY postdate ASC");
    $replynumrows = mysqli_num_rows($query_replies);
    if($replynumrows > 0){
        while ($row2 = mysqli_fetch_array($query_replies, MYSQLI_ASSOC)) {
            $statusreplyid = $row2["id"];
            $replyauthor = $row2["author"];
            $replydata = $row2["data"];
            $replydata = nl2br($replydata);
            $replypostdate = $row2["postdate"];
            $replydata = str_replace("&am
            p;","&",$replydata);
            $replydata = stripslashes($replydata);
            $status_replies .= '<div id="reply_'.$statusreplyid.'" class="reply_boxes"><div><b>Reply by <a href="profile.php?u='.$replyauthor.'">'.$replyauthor.'</a> '.$replypostdate.':</b> '.$replyDeleteButton.'<br />'.$replydata.'</div></div>';
        }
    }
    $statuslist .= '<div id="status_'.$statusid.'" class="status_boxes"><div><b>Posted by <a href="profile.php?u='.$author.'">'.$author.'</a> '.$postdate.':</b> '.$statusDeleteButton.' <br />'.$data.'</div>'.$status_replies.'</div>';
    if($isFriend == true || $log_username == $u){
        $statuslist .= '<textarea id="replytext_'.$statusid.'" class="replytext form-control" onkeyup="statusMax(this,250)" placeholder="write a comment here"></textarea><button id="replyBtn_'.$statusid.'" onclick="replyToStatus('.$statusid.',\''.$u.'\',\'replytext_'.$statusid.'\',this)">Reply</button>';   
    }
}
?>
<script>
</script>
<div id="statusui" class="col-md-12">
  <?php echo $status_ui; ?>
</div>
<div id="statusarea" class="col-md-12">
  <?php echo $statuslist; ?>
</div>
