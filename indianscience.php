<?php
/*
 * Pagination for 
 * all blogs from newest to oldest
 * Recommeded blogs (based on most popular tag associated with the viewer) from highest to lowest viewed
 * Trending blogs (based on highest views of users)
 */

include_once("php_includes/check_login_status.php");
if($user_ok != true){//check if user is logged in
    header("location: login.php");
}
if(!isset($_GET['type'])){
    header("location: indianscience.php?type=all");
}
$type=$_GET['type'];
function count_all_results($db_conx,$log_username){
    //sql statement to get count of all the blogs from the database
    $sqlCommand = "SELECT COUNT(id) FROM pages WHERE owner <> '$log_username'";
    $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
    $row = mysqli_fetch_array($result, MYSQLI_BOTH) ;
    $count=$row[0];
    return $count;
}
function count_recommended_results($log_id,$db_conx){
    $count = array();
    $i=0;
    $blog_id=0;
    $sqlCommand = "SELECT tag_id,tag_count FROM user_tag WHERE user_id = $log_id ORDER BY tag_count DESC LIMIT 1"; // tag id and tag count of highest interest to logged in user
    $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC) ;
    $tag_id = $row["tag_id"];
    //print_r($tag_id."is the tag id <br/>");
    //$sqlCommand = "SELECT blog_id FROM blog_tags WHERE tag_id = $tag_id"; // returns an array of views of blogs associated with the tag // ADD - the blog should not be already viewed by him
    $sqlCommand = "SELECT blog_id FROM blog_tags WHERE tag_id = $tag_id and blog_id not in (SELECT blog_id from user_blog_view where user_id = $log_id)
                        and blog_id not in (select p.id from users u, pages p where u.id=$log_id and p.owner = u.username)"; // returns an array of blogs associated with the tag
    $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
        $id = $row[0];
        $sqlCommand = "SELECT blog_id FROM user_blog_view WHERE blog_id = $id"; // number of views of the blog with blog id = row[0]
        //print_r($sqlCommand."<br/>");
        $result2 = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
        $numrows = mysqli_num_rows($result2); //count
        $count[$i]=array("views"=>$numrows,"blog_id"=>$id);
        $i++;
    }
    //print_r($count);
    return (count($count));
}
function count_trending_results($db_conx,$log_username){
    //sql statement to get count of all the blogs from the database
    $sqlCommand = "SELECT COUNT(id) FROM pages WHERE owner <> '$log_username'";
    $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
    $row = mysqli_fetch_array($result, MYSQLI_BOTH) ;
    $count=$row[0];
    return $count;
}
if($type == 'all'){
    $numrows = count_all_results($db_conx, $log_username);
}
else if($type == 'rcom'){
	$numrows = count_recommended_results($log_id,$db_conx);
}
else if($type == 'trending'){
	$numrows = count_trending_results($db_conx,$log_username);
}
// Specify how many results per page
$rpp = 2;
// This tells us the page number of our last page
$last = ceil($numrows/$rpp);
// This makes sure $last cannot be less than 1
if($last < 1){
    $last = 1;
}

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
        <script src="javascript/trending_tags.js"></script>
        <script>
            var rpp = <?php echo $rpp; ?>; // results per page
            var last = <?php echo $last; ?>; // last page number
            var type = '<?php echo $type; ?>'; // type (recommeded,trending,all)
            
            function request_page(pn){
                var results_box = document.getElementById("results_box");
                var pagination_controls = document.getElementById("pagination_controls");
                results_box.innerHTML = "loading results ...";
                var ajax = ajaxObj("POST","php_parsers/indianscience_pagination_parser.php");//returns JSON rpp recommended blogs in order              
                ajax.onreadystatechange = function() {
                   if(ajaxReturn(ajax) == true) {
                       var response = ajax.responseText.trim();
                       alert(response);
                       /*response has
                         * id
                         * pagetitle
                         * linklabel
                         * owner
                         * lastmodified
                         * number of views
                         */
                        if(response == "no_results"){
                            results_box.innerHTML = "no recommendations for you right now";
                        }
                        else{
                           var blogLinks = JSON.parse(response);    
                           var html_output = "";
                           if(type == 'rcom'){
                               html_output += "<h1>Recommended Blogs</h1><hr/>";
                               for(var obj in blogLinks){  
                                   html_output += "<div class='blog_result myborder'>";
                                   html_output += "<p>Title : "+blogLinks[obj].pagetitle+"</p>";
                                   html_output += "<p>Blog Views : " +blogLinks[obj].views+"</p>"; 
                                   html_output += "<p>Author : "+blogLinks[obj].author+"</p>";
                                   html_output +=" <p>Click to view :<a href='http://localhost/xampp/phptest/gardenproject/root/blogs.php?pid="+blogLinks[obj].blog_id+"'> "+blogLinks[obj].linklabel+" </a></p>";   
                                   html_output += "<p>Date Published : "+blogLinks[obj].lastmodified+"</p>";
                                   html_output += "</div>";
                                   html_output += "<hr/>";
                               }
                           }
                           else if(type == 'all'){
                               html_output += "<h1>All Blogs</h1><hr/>";
                               for(var obj in blogLinks){                             
                                   html_output += "<div class='blog_result myborder'>";
                                   html_output += "<p>Title : "+blogLinks[obj].pagetitle+"</p>";
                                   html_output += "<p>Author : "+blogLinks[obj].owner+"</p>";   
                                   html_output +=" <p>Click to view :<a href='http://localhost/xampp/phptest/gardenproject/root/blogs.php?pid="+blogLinks[obj].id+"'> "+blogLinks[obj].linklabel+" </a></p>";   
                                   html_output += "<p>Date Published : "+blogLinks[obj].lastmodified+"</p>";
                                   html_output += "</div>";
                                   html_output += "<hr/>";
                               }
                           }
                           else if(type == 'trending'){
                               html_output += "<h1>Trending Blogs</h1><hr/>";
                               for(var obj in blogLinks){    
                                   html_output += "<div class='blog_result myborder'>";
                                   html_output += "<p>Title : "+blogLinks[obj].pagetitle+"</p>"
                                   html_output += "<p>Blog Views : " +blogLinks[obj].views+"</p>"; 
                                   html_output += "<p>Author : "+blogLinks[obj].author+"</p>";
                                   html_output +=" <p>Click to view :<a href='http://localhost/xampp/phptest/gardenproject/root/blogs.php?pid="+blogLinks[obj].blog_id+"'> "+blogLinks[obj].linklabel+" </a></p>";   
                                   html_output += "<p>Date Published : "+blogLinks[obj].lastmodified+"</p>";
                                   html_output += "</div>";
                                   html_output += "<hr/>";
                               }
                           }
                           results_box.innerHTML = html_output;
                       }
                    }
                 };
            ajax.send("rpp="+rpp+"&last="+last+"&pn="+pn+"&type="+type);
            // Change the pagination controls
            var paginationCtrls = "";
            // Only if there is more than 1 page worth of results give the user pagination controls
            if(last != 1){
                if (pn > 1) {
                    paginationCtrls += '<button onclick="request_page('+(pn-1)+')">&lt;back</button>';
                }
                paginationCtrls += ' &nbsp; &nbsp; <b>Page '+pn+' of '+last+'</b> &nbsp; &nbsp; ';
                if (pn != last) {
                    paginationCtrls += '<button onclick="request_page('+(pn+1)+')">&gt;front</button>';
                }
            }
            pagination_controls.innerHTML = paginationCtrls;
        }
        </script>
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
                    <div class="col-md-2" id="menuContainer">
                        <div>
                            <h3> Blogs</h3>
                            <hr />
                        </div>
                          <div class="btn-group-vertical">
                              <a class="btn btn-primary" href="indianscience.php?type=all" role="button">All</a>
                              <a class="btn btn-primary" href="indianscience.php?type=rcom">Recommended</a>
                              <a class="btn btn-primary" href="indianscience.php?type=trending">Trending Blogs</a>
                              <a class="btn btn-primary" onclick="trending_tags()">Trending Tags</a>
                          </div>
                    </div>
                    <div class="col-md-8" id="blogBody">
                        <div class="row">
                            <div class="col-md-12 myborder">
                                 <div id="results_box"></div>
                                 <div id="pagination_controls"></div>
                                 <hr /> 
                                <script> request_page(1); </script>                                                        
                            </div>
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