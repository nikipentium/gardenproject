<?php
/* pagination of products
 * 1.display all products
 * 2.display products by category
 * 3.display products by sub category
 * ORDER products by
 * 1.newest products first
 * 2.cheapest products
 * 3.expensive products
 * 4.popular
 */

include_once("php_includes/check_login_status.php");
if($user_ok != true){
    header("location: login.php");
}
?>
<?php
 //store the GET variables
 if(!isset($_GET['type'])){
    header("location: market.php?type=all");
}
$type=$_GET['type'];
function count_all_results($db_conx){
    //sql statement to get count of all the blogs from the database
    $sqlCommand = "SELECT COUNT(id) FROM products";
    $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
    $row = mysqli_fetch_array($result, MYSQLI_BOTH) ;
    $count=$row[0];
    return $count;
}
if($type == 'all'){
    $numrows = count_all_results($db_conx);
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
        <title>Market</title>
        <script src="javascript/main.js"></script>
        <script src="javascript/ajax.js"></script>
        <script src="javascript/search.js"></script>
        <script>
            var rpp = <?php echo $rpp; ?>; // results per page
            var last = <?php echo $last; ?>; // last page number
            var type = '<?php echo $type; ?>'; // type (recommeded,trending,all)
            
            function request_page(pn){
                var results_box = document.getElementById("results_box");
                var pagination_controls = document.getElementById("pagination_controls");
                results_box.innerHTML = "loading results ...";
                var ajax = ajaxObj("POST","php_parsers/market_pagination_parser.php");//returns JSON rpp recommended blogs in order
                
                ajax.onreadystatechange = function() {
                   if(ajaxReturn(ajax) == true) {
                       var response = ajax.responseText.trim();
                       alert(response);
                       var productDetails = JSON.parse(response); // response = id + product_name + category + subcategory    
                       var html = "";
                       if(type == 'all'){
                           for(var obj in productDetails){  
                               html += "<div class='col-md-3 myborder'>"
                               html += "<img src='inventory_images/"+productDetails[obj]['details'].id+".jpg'/>"
                               html += "<p>Product Name : "+ productDetails[obj]['details'].product_name +"</p>"
                               html += "<p>Category : "+ productDetails[obj]['details'].category +"</p>"
                               html += "<p>Sub Category : "+ productDetails[obj]['details'].subcategory +"</p>"
                               html += "<a href=http://localhost/xampp/phptest/gardenproject/root/product.php?pid="+ productDetails[obj]['details'].id +">View the Product Details</a>"
                               html += "<p>Sellers : "+ productDetails[obj]['sellers'] +"</p>"
                               html += "<p>Quantity : "+ productDetails[obj]['quantity'] +"</p>"
                               html += "<p>Average Price : "+ productDetails[obj]['average'] +"</p>"
                               html += "</div>"
                           }
                       }
                       else if(type == 'x'){
                           for(var obj in blogLinks){  
                               
                           }
                       }
                       else if(type == 'y'){
                           for(var obj in blogLinks){  
                               //html_output +=" <a href='http://localhost/xampp/phptest/gardenproject/root/blogs.php?pid="+blogLinks[obj].blog_id+"'> Click to view </a><br/>The blog views is " +blogLinks[obj].views+"<br/><hr/>";   
                           }
                       }
                       results_box.innerHTML = html;
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
                                <h3> Ecommerce</h3>
                                <hr />
                            </div>
                              <div class="btn-group-vertical">
                                  <a class="btn btn-primary" href="inventory_list.php">My Inventory</a>
                                  <a class="btn btn-primary" href="market.php">The Market</a>
                                  <a class="btn btn-primary" href="">My Cart</a>
                              </div>
                        </div>
                        <div class="col-md-10" id="marketBody">
                            <div class="row">
                                <div class="col-md-12 myborder">
                                     <div id="pagination_controls"></div>
                                </div> 
                                <div class="col-md-12 myborder">          
                                    <div class="row" id="results_box">
                                         <script> request_page(1); </script>
                                    </div>      
                                </div>                      
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