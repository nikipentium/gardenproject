<?php
// Connect to our database here
include_once("php_includes/check_login_status.php");
// This first query is just to get the total count of rows
//get search queries by post variable
$query1 = $_POST["query1"];
$query2 = $_POST["query2"];
$sql = "select distinct p.id, p.owner, p.pagebody from pages p, blog_tags bt, tags t where t.title in ('$query1','$query2') and bt.tag_id = t.tag_id and bt.blog_id = p.id";
$result = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($result);
// Here we have the total row count
$total_rows = $numrows;
// Specify how many results per page
$rpp = 2;
// This tells us the page number of our last page
$last = ceil($total_rows/$rpp);
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
        <script src="javascript/ajax.js"></script>
        <script>
            var rpp = <?php echo $rpp; ?>; // results per page
            var last = <?php echo $last; ?>; // last page number
            function request_page(pn){
                var results_box = document.getElementById("results_box");
                var pagination_controls = document.getElementById("pagination_controls");
                results_box.innerHTML = "loading results ...";
                var ajax = ajaxObj("POST","php_parsers/blog_new_parse.php");
                ajax.open("POST", "php_parsers/blog_pagination_parser.php", true);
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.onreadystatechange = function() {
                   if(ajaxReturn(ajax) == true) {
                        var dataArray = ajax.responseText.split("||");
                        var html_output = "";
                        for(i = 0; i < dataArray.length - 1; i++){
                            var itemArray = dataArray[i].split("|");
                            html_output += "ID: "+itemArray[0]+" - User <b>"+itemArray[1]+"</b><hr>";
                        }
                        results_box.innerHTML = html_output;
                    }
                 };
            ajax.send("rpp="+rpp+"&last="+last+"&pn="+pn);
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
            <?php
            include_once ("templates/template_page_bottom.php");
            ?>
        </div>
    </body>
</html>