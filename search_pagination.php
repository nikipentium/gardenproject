<?php
include_once "/php_includes/check_login_status.php";
//get search option and query via POST from html
//count the number of results
//set results per page
//calculate last page
//request page function
//depending on the search option display of result varies 
//pagination controls

$searchquery = $_POST["searchquery"];
$searchOption = $_POST["option"];
//decode the json results
$queries = explode(" ", $searchquery);

$querylist="";
for($i = 0; $i < count($queries) ; $i++){
    //store tags in behavior (user_tag) table
    //check if tags id already exists
    //if it exists increase the count
    //else add a row to that table
    $query =$queries[$i];
    $sqlcommand = "SELECT tag_id FROM tags WHERE title = '$query' LIMIT 1";
    $result = mysqli_query($db_conx, $sqlcommand) or die (mysqli_error($db_conx));
    $numrows = mysqli_num_rows($result);
    if($numrows > 0){
        $row = mysqli_fetch_array($result, MYSQLI_BOTH);
        $tag_id = $row[0];
        $sqlcommand = "SELECT tag_id FROM user_tag WHERE tag_id = $tag_id AND user_id = $log_id LIMIT 1";
        $result = mysqli_query($db_conx, $sqlcommand) or die (mysqli_error($db_conx));
        $numrows = mysqli_num_rows($result);
        if($numrows < 1){
            $sqlcommand = "INSERT INTO user_tag (user_id,tag_id) VALUES($log_id,$tag_id)";
            $result = mysqli_query($db_conx, $sqlcommand) or die (mysqli_error($db_conx));
        }
        else{
            $sqlCommand = "UPDATE user_tag SET tag_count = tag_count + 1 WHERE tag_id = $tag_id AND user_id = $log_id";
            $result = mysqli_query($db_conx, $sqlCommand);
        }
    }
    if($i==count($queries)-1){
        $querylist .= "'".$queries[$i]."'";
    }
    else{
        $querylist .= "'".$queries[$i]."',";
    }
}

$sqlcommand = "select distinct p.id, p.owner, p.pagetitle from pages p, blog_tags bt, tags t where t.title in ($querylist) and bt.tag_id = t.tag_id and bt.blog_id = p.id";
$result = mysqli_query($db_conx, $sqlcommand) or die (mysqli_error($db_conx));
$numrows = mysqli_num_rows($result); //count
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
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="style/bootstrap/bootstrap.js"></script>
        <script src="javascript/ajax.js"></script>      
        <script>
            var rpp = <?php echo $rpp; ?>; // results per page
            var last = <?php echo $last; ?>; // last page number
            var query = "<?php echo $searchquery ?>"
            var option = "<?php echo $searchOption ?>"
            function request_page(pn){
                var results_box = document.getElementById("results_box");
                var pagination_controls = document.getElementById("pagination_controls");
                results_box.innerHTML = "loading results ...";
                var ajax = ajaxObj("POST","php_parsers/pagination_parser.php");
                ajax.onreadystatechange = function() {
                   if(ajaxReturn(ajax) == true) {
                       var response = ajax.responseText.trim();
                       alert(response);
                       var searchResults = JSON.parse(response);    
                       var html_output = "";
                       for(var obj in searchResults){              
                           html_output +=" <a href='http://localhost/xampp/phptest/gardenproject/root/blogs.php?pid="+searchResults[obj].id+"'> "+searchResults[obj].title+" </a><br/>The blog author is " +searchResults[obj].author+"<br/><hr/>";   
                       }
                       results_box.innerHTML = html_output;
                    }
                 };
            ajax.send("rpp="+rpp+"&last="+last+"&pn="+pn+"&query="+query+"&option="+option);
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
     <title>all users</title>
 </head>
  <body>
        <div class="container">
            <?php
            include_once ("templates/template_page_top.php");
            ?>
           <div class="row" id="pageMiddle">
                <div class="col-md-12">
                    <div class="row">
                       <div class="col-md-12">
                            <div id="pagination_controls"></div>
                       </div>
                     </div>
                   <div class="row">
                       <div class="col-md-12">
                            <div id="results_box"></div>
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