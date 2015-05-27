<?php
include_once("php_includes/check_login_status.php");
if($user_ok != true){
    header("location: login.php");
}
$u=$log_username;
$body="";
$blogtitle="";
$pageid="";
$blogtags="";
// Determine which page ID to use in our query below ---------------------------------------------------------------------------------------
if (!isset($_GET['pid'])) {
    $sqlCommand = "SELECT id FROM pages WHERE owner='$log_username' ORDER BY id DESC LIMIT 1";
    $query = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
    $numrows = mysqli_num_rows($query);
    if($numrows<1){
        $blogtitle="Hi ".$log_username;
        $body="Welcome to your blog section";
    }
    else{
        while ($row = mysqli_fetch_array($query)) { 
            $pageid = $row["id"];
        } 
        header("location: blogs.php?pid=".$pageid);
    }
} else {
    $pageid = ereg_replace("[^0-9]", "", $_GET['pid']); // filter everything but numbers for security
}
// Query the body section for the proper page
$sqlCommand = "SELECT pagebody FROM pages WHERE id='$pageid' AND owner='$log_username' LIMIT 1";
$query = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error());
while ($row = mysqli_fetch_array($query)) { 
    $body = $row["pagebody"];
} 
// Query the blog title for the proper page
mysqli_free_result($query);
$sqlCommand = "SELECT pagetitle FROM pages WHERE id='$pageid' AND owner='$log_username' LIMIT 1";
$query = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error());
while ($row = mysqli_fetch_array($query)) { 
    $blogtitle = $row["pagetitle"];
} 
mysqli_free_result($query);  
// Query the blog tags for the proper page
$sqlCommand = "SELECT title FROM tags t,blog_tags bt WHERE bt.blog_id = '$pageid' AND bt.tag_id = t.tag_id";
$query = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error());
while ($row = mysqli_fetch_array($query)) { 
    $blogtags .= $row["title"]." | ";
} 
mysqli_free_result($query);  
// Build Main Navigation menu and gather page data here -----------------------------------------------------------------------------
$sqlCommand = "SELECT id, linklabel FROM pages WHERE showing='1' AND owner='$log_username' ORDER BY id ASC"; 
$query = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error()); 
$menuDisplay = '<button type="button" class="btn btn-primary" id="addBlogButton">Add Blog</button></br>';
$numrows = mysqli_num_rows($query);
while ($row = mysqli_fetch_array($query)) { 
    $pid = $row["id"];
    $linklabel = $row["linklabel"];
    $menuDisplay .= '<a href="blogs.php?pid=' . $pid . '">' . $linklabel . '</a><br />';
} 
mysqli_free_result($query); 
//---------------------------------------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Blog page</title>
        <script src="javascript/main.js"></script>
        <script src="javascript/ajax.js"></script>
        <script src="javascript/blog.js"></script>
        <script src="javascript/search.js"></script>
        <link rel="stylesheet" type="text/css" href="style/bootstrap/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <link rel="stylesheet" type="text/css" href="style/blog.css">
    </head>
    <body>
        <div class="container">
            <?php
            include_once ("templates/template_page_top.php");
            ?>
                <div class="row" id="pageMiddle">
                    <div class="col-md-3" id="myBlogsMenu">
                        <h3>My Blogs</h3>
                        <div>
                        <?php
                            echo $menuDisplay;
                        ?>
                        </div>
                    </div>
                    <div class="col-md-9" id="blogBody">
                        <div class="row">
                            <div class="col-md-10">
                                <h2>
                                    <?php
                                        echo $blogtitle;
                                    ?>
                                </h2> 
                            </div>
                            <div class="col-md-2">
                                 <button type="button" class="btn btn-primary" id="editBlogButton" onclick="getdata('<?php echo $pageid ?>')">Edit Blog</button>
                            </div>
                            <div class="col-md-12 myborder">
                            <?php
                                echo $body;
                            ?>
                            </div>
                            <div class="col-md-12 myborder" id="tags">                   
                            <?php
                                echo "Tags : ".$blogtags;
                            ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-md-offset-3" id="commentSection">
                        <h4>Comments</h4>
                        <div>
                            <?php include_once("templates/template_blog_comments.php"); ?>     
                        </div>
                    </div>
                </div>
            <?php
            include_once ("templates/template_page_bottom.php");
            ?>
        </div>
    </body>
</html>