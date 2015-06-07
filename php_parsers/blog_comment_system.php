<?php
include_once("../php_includes/check_login_status.php");
if($user_ok != true || $log_username == "") {
    exit();
}
?>
<?php // DISPLAY OLD COMMENTS AND REPLIES
//ajax.send("action="+action+"&blog_id="+blog_id);
if (isset($_POST['action']) && $_POST['action'] == "load_comments"){
    //respond with osid,author,data,postdate
    $blog_id = $_POST['blog_id'];
    $sqlCommand = "SELECT osid,author,data,postdate FROM blog_comments WHERE type='a' AND blog_id=$blog_id ORDER BY postdate DESC";
    $query = mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
    $result = array();
    $i=0;
    while($row = mysqli_fetch_array($query, MYSQLI_BOTH)){      
         $content['osid'] = $row['osid'];
         $content['author'] = $row['author'];
         $content['data'] = $row['data'];
         $content['postdate'] = $row['postdate'];
         $cid = $row['osid'];
         $sqlCommand = "SELECT osid,author,data,postdate FROM blog_comments WHERE type='b' AND osid=$cid AND blog_id=$blog_id ORDER BY postdate ASC";
         $query2 = mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
         $result2 = array();
         $k=0;
         while($row2 = mysqli_fetch_array($query2, MYSQLI_BOTH)){
             $replies['osid'] = $row2['osid'];
             $replies['author'] = $row2['author'];
             $replies['data'] = $row2['data'];
             $replies['postdate'] = $row2['postdate'];
             $result2[$k++]=$replies;
         }
         $content['replies']=$result2;
         $result[$i++] = $content;
    }
    print_r(json_encode($result));
    exit();   
}
//POST COMMENT
//ajax.send("action="+action+"&type="+type+"&blog_id="+blog_id+"&viewer="+viewer+"&data="+comment_text);
if (isset($_POST['action']) && $_POST['action'] == "comment_post"){
    // Make sure post data is not empty
    if(strlen($_POST['data']) < 1){
        mysqli_close($db_conx);
        echo "data_empty";
        exit();
    }
    // Make sure type is either a
    if($_POST['type'] != "a"){
        mysqli_close($db_conx);
        echo "type_unknown";
        exit();
    }
    // Clean all of the $_POST vars that will interact with the database
    $type = preg_replace('#[^a-z]#', '', $_POST['type']);
    $blog_id = preg_replace('#[^a-z0-9]#i', '', $_POST['blog_id']);
    $data = htmlentities($_POST['data']);
    $viewer = $_POST['viewer'];
    $data = mysqli_real_escape_string($db_conx, $data);
    // Make sure account name exists (the profile being posted on)
    $sql = "SELECT COUNT(id) FROM users WHERE username='$viewer' AND activated='1' LIMIT 1";
    $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
    $row = mysqli_fetch_row($query);
    if($row[0] < 1){
        mysqli_close($db_conx);
        echo "$account_no_exist";
        exit();
    }
    // Insert the status post into the database now
    $sql = "INSERT INTO blog_comments(blog_id, author, type, data, postdate) 
            VALUES('$blog_id','$viewer','$type','$data',now())";
    $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
    $id = mysqli_insert_id($db_conx);
    $sql = "UPDATE blog_comments SET osid=$id WHERE id=$id LIMIT 1";
    $result = mysqli_query($db_conx,$sql) or die(mysqli_error($db_conx));
    //GET LAST(LATEST) COMMENT INSERTED
    $sqlCommand = "SELECT osid,author,data,postdate FROM blog_comments WHERE blog_id=$blog_id ORDER BY postdate DESC LIMIT 1";
    $query = mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
    $result = array();
    $i=0;
    while($row = mysqli_fetch_array($query, MYSQLI_BOTH)){      
         $content['osid'] = $row['osid'];
         $content['author'] = $row['author'];
         $content['data'] = $row['data'];
         $content['postdate'] = $row['postdate'];
         $result[$i++] = $content;
    }
    print_r(json_encode($result));

    //Insert notifications to owner of the post author
     //get the owner of the blog
     $sqlCommand = "SELECT owner,pagetitle FROM pages WHERE id = $blog_id LIMIT 1";
     $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
     $row = mysqli_fetch_array($result, MYSQLI_BOTH);
     $owner = $row['owner'];
     $blog_title = $row['pagetitle'];
     $app = "Blog Status Post";
     $sqlCommand="INSERT INTO notifications(username, initiator, app, date_time) VALUES('$owner','$log_username','$app',now())";
     mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
     //get ID that was generated
     $id = mysqli_insert_id($db_conx);
     //update notification with modifiednote
     $note = $log_username.' posted a query on: <br /><a href="blogs.php?pid='.$blog_id.'#comment_'.$id.'">'.$blog_title.'</a>
     <br/><button class="btn btn-default" onclick="update_num_notes('.$id.')" id="view_btn">View Comment</button>'; 
     $sqlCommand ="UPDATE notifications SET note = '$note' WHERE id = $id";
     mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
     exit();

}
?><?php //REPLY POST
//ajax.send("action="+action+"&type="+type+"&blog_id="+blog_id+"&osid="+osid+"&viewer="+viewer+"&data="+reply_text);
if (isset($_POST['action']) && $_POST['action'] == "reply_post"){
    // Make sure data is not empty
    if(strlen($_POST['data']) < 1){
        mysqli_close($db_conx);
        echo "data_empty";
        exit();
    }
    // Clean the posted variables
    $type = $_POST['type'];
    $osid = preg_replace('#[^0-9]#', '', $_POST['osid']);
    $blog_id = preg_replace('#[^a-z0-9]#i', '', $_POST['blog_id']);
    $data = htmlentities($_POST['data']);
    $data = mysqli_real_escape_string($db_conx, $data);
    $viewer = $_POST['viewer'];
    // Make sure account name exists (the profile being posted on)
    $sql = "SELECT COUNT(id) FROM users WHERE username='$viewer' AND activated='1' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $row = mysqli_fetch_row($query);
    if($row[0] < 1){
        mysqli_close($db_conx);
        echo "$account_no_exist";
        exit();
    }
    // Insert the blog comment reply post into the database now
    $sql = "INSERT INTO blog_comments(blog_id, author, osid, type, data, postdate) 
            VALUES('$blog_id','$viewer',$osid,'$type','$data',now())";
    $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
    //select last reply and send it
    $sqlCommand = "SELECT osid,author,data,postdate FROM blog_comments WHERE blog_id=$blog_id AND osid=$osid ORDER BY postdate DESC LIMIT 1";
    $query = mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
    $result = array();
    $i=0;
    while($row = mysqli_fetch_array($query, MYSQLI_BOTH)){      
         $content['osid'] = $row['osid'];
         $content['author'] = $row['author'];
         $content['data'] = $row['data'];
         $content['postdate'] = $row['postdate'];
         $result[$i++] = $content;
    }
    print_r(json_encode($result));
    //exit();
     //Insert notifications to initiator of comment post of the reply post author
     //get the owner of the blog
     $sqlCommand = "SELECT pagetitle,owner FROM pages WHERE id = $blog_id LIMIT 1";
     $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
     $row = mysqli_fetch_array($result, MYSQLI_BOTH);
     $blog_title = $row['pagetitle'];
     $owner = $row['owner'];
     $sqlCommand = "SELECT author FROM blog_comments WHERE blog_id = $blog_id AND osid=$osid LIMIT 1";
     $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
     $row = mysqli_fetch_array($result, MYSQLI_BOTH);
     $author = $row['author'];
     $app = "Blog Reply Post";
     //the author of the comment or page owner of the blog should not get notified if they themselves reply on a post
     if($viewer == $author){
         //if viewer is author do not notify the viewer but notify the owner
         $sqlCommand="INSERT INTO notifications(username, initiator, app, date_time) VALUES('$owner','$log_username','$app',now())";
         mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
         $id = mysqli_insert_id($db_conx);
         $note = $log_username.' replied to a query on: <br /><a href="blogs.php?pid='.$blog_id.'#reply_'.$osid.'">Blog : '.$blog_title.'</a><br/><button class="btn btn-default" onclick="update_num_notes('.$id.')" id="view_btn">View Reply</button>';      
         $sqlCommand ="UPDATE notifications SET note = '$note' WHERE id = $id";
         mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
     }
     else if($owner == $viewer){
         //if owner is author do not notify the owner but notify the viewer
         $sqlCommand="INSERT INTO notifications(username, initiator, app, date_time) VALUES('$author','$log_username','$app',now())";
         mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
         $id = mysqli_insert_id($db_conx);
         $note = $log_username.' replied to a query on: <br /><a href="blogs.php?pid='.$blog_id.'#reply_'.$osid.'">Blog : '.$blog_title.'</a><br/><button class="btn btn-default" onclick="update_num_notes('.$id.')" id="view_btn">View Reply</button>';      
         $sqlCommand ="UPDATE notifications SET note = '$note' WHERE id = $id";
         mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
     }
     else{
         //notify author
         $sqlCommand="INSERT INTO notifications(username, initiator, app, date_time) VALUES('$author','$log_username','$app',now())";
         mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
         $id = mysqli_insert_id($db_conx);
         $note = $log_username.' replied to a query on: <br /><a href="blogs.php?pid='.$blog_id.'#reply_'.$osid.'">Blog : '.$blog_title.'</a><br/><button class="btn btn-default" onclick="update_num_notes('.$id.')" id="view_btn">View Reply</button>';      
         $sqlCommand ="UPDATE notifications SET note = '$note' WHERE id = $id";
         mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
         //notify owner
         $sqlCommand="INSERT INTO notifications(username, initiator, app, date_time) VALUES('$owner','$log_username','$app',now())";
         mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
         $id = mysqli_insert_id($db_conx);
         $note = $log_username.' replied to a query on: <br /><a href="blogs.php?pid='.$blog_id.'#reply_'.$osid.'">Blog : '.$blog_title.'</a><br/><button class="btn btn-default" onclick="update_num_notes('.$id.')" id="view_btn">View Reply</button>';      
         $sqlCommand ="UPDATE notifications SET note = '$note' WHERE id = $id";
     }  
     exit();
}
?>