<?php
/*
 * check if viewer is owner of the blog
 * */
include_once("php_includes/check_login_status.php");
if($user_ok != true){
    header("location: login.php");
}
$body="";
$blogtitle="";
$pageid="";
$blogtags="";
$page_owner="";
$add_blog_btn="<button type='button' class='btn btn-primary' style='display:none' id='addBlogButton'>Add Blog</button></br>";
$add_product_btn="<button type='button'  class='btn btn-primary' style='display:none' id='addProductButton'>Add Product</button></br>";
$edit_blog_btn="";
// Determine which page ID to use in our query below ---------------------------------------------------------------------------------------
if (isset($_GET['pid'])) {
    $pageid = ereg_replace("[^0-9]", "", $_GET['pid']); // filter everything but numbers for security   
}
else{
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
}
//for the pid query the body section, blog title
$sqlCommand = "SELECT pagebody,pagetitle FROM pages WHERE id='$pageid' LIMIT 1";
$query = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
while ($row = mysqli_fetch_array($query,MYSQLI_ASSOC)) { 
    $body = $row["pagebody"];
    $blogtitle = $row["pagetitle"];
} 

mysqli_free_result($query);  
// Query the blog tags for the proper page
$sqlCommand = "SELECT title FROM tags t,blog_tags bt WHERE bt.blog_id = '$pageid' AND bt.tag_id = t.tag_id";
$query = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
while ($row = mysqli_fetch_array($query)) { 
    $blogtags .= $row["title"]." | ";
} 
mysqli_free_result($query);
//get pageOwner
$sqlCommand = "SELECT owner FROM `pages` WHERE id='$pageid' LIMIT 1";
$result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
$row = mysqli_fetch_array($result, MYSQLI_NUM);
$pageOwner = $row[0];
$owner = $pageOwner;
if($pageOwner == $log_username){
    $pageOwner = "yes";
    $add_blog_btn ="<div>
                       <button type='button' class='btn btn-primary' id='addBlogButton'>Add Blog</button></br>
                       <hr />
                    </div>";
    $edit_blog_btn ="<button type='button' class='btn btn-primary' id='editBlogButton' onclick=getdata($pageid)>Edit Blog</button></br>";
    $add_product_btn ="<div>
                       <button type='button'  class='btn btn-primary' onclick='display_product_form()' id='addProductButton'>Add Product</button></br>
                       <hr />
                    </div>";
}
else{//update viewer behavior
    $pageOwner = "no";
    //update tables user_blog_view and user_tag
    //update user_blog_view
    //check if user has already viewed the blog
    $sqlCommand = "SELECT blog_id FROM user_blog_view WHERE user_id = '$log_id' AND blog_id = '$pageid'";
    $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
    $numrows = mysqli_num_rows($result);
    if($numrows<1){//not viewed the blog
          $sqlCommand = "INSERT INTO user_blog_view (blog_id,user_id) VALUES ('$pageid','$log_id')";
          $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
              //update user_tag
            //get the tag_ids of the blog
            $sqlCommand = "SELECT t.tag_id FROM tags t,blog_tags bt WHERE bt.blog_id = '$pageid' AND bt.tag_id = t.tag_id";
            $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                //check if tag_id exists in user_tag
                $tag = $row["tag_id"];
                $sqlCommand = "SELECT tag_id FROM user_tag WHERE tag_id = $tag AND user_id = '$log_id' ";
                $result2 = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
                $numrows = mysqli_num_rows($result2);
                if($numrows < 1){
                    $sqlCommand = "INSERT INTO user_tag (tag_id,user_id) VALUES ($tag,$log_id)";
                    $result3 = mysqli_query($db_conx, $sqlCommand);                
                }
                else{
                    $sqlCommand = "UPDATE user_tag SET tag_count = tag_count + 1 WHERE tag_id = $tag AND user_id = $log_id";
                    $result3 = mysqli_query($db_conx, $sqlCommand); 
                }
          } 
     }
} //closes if not pageOwner
// Build Main Navigation menu and gather page data here -----------------------------------------------------------------------------
$sqlCommand = "SELECT id, linklabel FROM pages WHERE showing='1' AND owner='$owner' ORDER BY id DESC"; 
$query = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error()); 
$menuDisplay = '';
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
        <script src="javascript/search.js"></script>
        <script src="javascript/blog.js"></script>
        <script src="javascript/blog_comments.js"></script>
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
                <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 myborder" id="menuContainer">
                        <div>
                            <h4> Author : <?php echo $owner ?></h4>
                            <hr />
                        </div>
                        <?php echo $add_blog_btn ?>
                        <div>
                            <h4><?php echo $owner ?>'s Blogs</h4>
                            <?php echo $menuDisplay ?>
                            <hr />
                        </div>
                    </div>
                    <div class="col-md-8" id="blogBody">
                        <div class="row">
                            <div class="col-md-10">
                                <h2>
                                    <?php
                                        echo $blogtitle;
                                    ?>
                                </h2> 
                            </div>
                            <div class="col-md-2">
                                <h2>
                                    <?php
                                        echo $edit_blog_btn;
                                    ?>
                                </h2> 
                            </div>
                            <div class="col-md-12 myborder">
                            <?php
                                echo $body;
                            ?>
                            <hr />
                            </div>
                            
                            <div class="col-md-12 myborder" id="tags">                   
                            <?php
                                echo "Tags : ".$blogtags;
                            ?>
                            <hr />
                            </div>
                            <div class="col-md-12 myborder" id="commentSection">
                                <h4>Comments</h4>
                                <div id="comment_box">
                                    
                                    <script>
                                          var viewer = "<?php echo $log_username ?>"
                                          var isPageOwner = "<?php echo $pageOwner ?>" ;
                                          pageid = <?php echo $pageid ?>;
                                          comments(isPageOwner,pageid,viewer);
                                    </script>
                                    
                                </div>
                                <div id="prev_comments">
                                    
                                </div>  
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 myborder" id="adds" align="center">
                        <h3>Products Associated</h3>
                        <?php echo $add_product_btn ?>
                        <div id="product_form" onsubmit="return false;">
                            
                        </div>
                    </div>
                </div>
                <div>
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                </div>
            <?php
            include_once ("templates/template_page_bottom.php");
            ?>
        </div>
    </body>
</html>