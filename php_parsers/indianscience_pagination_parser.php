<?php
// Make the script run only if there is a page number posted to this script
if(isset($_POST['pn'])){
    $rpp = preg_replace('#[^0-9]#', '', $_POST['rpp']);
    $last = preg_replace('#[^0-9]#', '', $_POST['last']);
    $pn = preg_replace('#[^0-9]#', '', $_POST['pn']);
    $type = $_POST['type'];
    // This makes sure the page number isn't below 1, or more than our $last page
    if ($pn < 1) { 
        $pn = 1; 
    } else if ($pn > $last) { 
        $pn = $last; 
    }
    // Connect to our database here
    include_once("../php_includes/check_login_status.php");
    // This sets the range of rows to query for the chosen $pn
    $limit = 'LIMIT ' .($pn - 1) * $rpp .',' .$rpp;

    function all_results($log_username,$db_conx,$limit){
        $results = array();
        $i=0;
        $sqlCommand = "SELECT id,owner,pagetitle,linklabel,lastmodified FROM pages WHERE owner <> '$log_username' ORDER BY lastmodified DESC $limit";
        $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $results[$i++] = $row;
        }
        return $results;
    }  
    function trending_results($log_username,$db_conx,$limit){
        $count = array();
        $i=0;
        $blog_id=0;
        $sqlCommand = "SELECT id,owner,pagetitle,linklabel,lastmodified FROM pages WHERE owner <> '$log_username' $limit"; // returns an array of blogs associated with the tag
        $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            $id = $row[0];
            $sqlCommand = "SELECT user_id,blog_id FROM user_blog_view WHERE blog_id = $id $limit"; // number of views of the blog with blog id = row[0]
            //print_r($sqlCommand."<br/>");
            $result2 = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
            $numrows = mysqli_num_rows($result2); //count
            $count[$i]=array("views"=>$numrows,"blog_id"=>$id,"author"=>$row['owner'],"pagetitle"=>$row['pagetitle'],"linklabel"=>$row['linklabel'],"lastmodified"=>$row['lastmodified']);
            $i++;
        }
        //sort blog count according to value (no. of views)
        $sort_array = array();
        foreach ($count as $key => $row)
        {
            $sort_array[$key] = $row['views'];
        }
        array_multisort($sort_array, SORT_DESC, $count);
        return $count;
    } 
    function recommended_results($log_id,$db_conx,$limit,$log_username){
        $count = array();
        $i=0;
        $blog_id=0;
        // tag id and tag count of highest interest to logged in user
        $sqlCommand = "SELECT tag_id,tag_count FROM user_tag WHERE user_id = $log_id ORDER BY tag_count DESC LIMIT 1"; 
        $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
        $numrows = mysqli_num_rows($result);
        if($numrows < 1){
            echo "no_result";
            exit();
        }
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC) ;
        $tag_id = $row["tag_id"];
        //print_r($tag_id."is the tag id <br/>");
        // returns an array of blogs associated with the tag that the user has not created and has not viewed
        $sqlCommand = "SELECT blog_id FROM blog_tags WHERE tag_id = $tag_id and blog_id not in (SELECT blog_id from user_blog_view where user_id = $log_id)
                        and blog_id not in (select p.id from users u, pages p where u.id=$log_id and p.owner = u.username) $limit"; 
        $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            $id = $row[0];
            $sqlCommand = "SELECT user_id,blog_id FROM user_blog_view WHERE blog_id = $id $limit"; // number of views of the blog with blog id = row[0]
            //print_r($sqlCommand."<br/>");
            $result2 = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
            $numrows = mysqli_num_rows($result2); //count
            $sqlCommand = "SELECT id,owner,pagetitle,linklabel,lastmodified FROM pages WHERE owner <> '$log_username' AND id=$id"; // returns an array of blogs associated with the tag
            //print_r($sqlCommand."<br/>");
            $result2 = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
            $row2 = mysqli_fetch_array($result2, MYSQLI_BOTH);
            $count[$i]=array("views"=>$numrows,"blog_id"=>$id,"author"=>$row2['owner'],"pagetitle"=>$row2['pagetitle'],"linklabel"=>$row2['linklabel'],"lastmodified"=>$row2['lastmodified']);
            $i++;
        }
        //sort blog count according to value (no. of views)
        $sort_array = array();
        foreach ($count as $key => $row)
        {
            $sort_array[$key] = $row['views'];
        }
        array_multisort($sort_array, SORT_DESC, $count);
        return $count;
    }
        
   if($type == 'all'){
        $results = all_results($log_username,$db_conx,$limit);
    }
    else if ($type == 'rcom' ) {
        $results = recommended_results($log_id, $db_conx,$limit,$log_username);
    }
    else if ($type == 'trending') {
        $results = trending_results($log_username,$db_conx,$limit);
    }
       
    // Echo the results back to Ajax
    /*response should have
     * id
     * pagetitle
     * linklabel
     * owner
     * lastmodified
     * number of views
     */
    echo json_encode($results);
    exit();
}
?>