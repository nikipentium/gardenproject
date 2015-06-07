<?php
/*
 */

include_once("php_includes/check_login_status.php");
if($user_ok != true){
    header("location: login.php");
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ecommerce</title>
        <script src="javascript/main.js"></script>
        <script src="javascript/ajax.js"></script>
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
                <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2" id="menuContainer">
                        <div>
                            <h3> Ecommerce</h3>
                            <hr />
                        </div>
                          <div class="btn-group-vertical">
                              <a class="btn btn-primary" href="inventory_list.php">My Inventory</a>
                              <a class="btn btn-primary" href="market.php?type=all">The Market</a>
                              <a class="btn btn-primary" href="">My Cart</a>
                          </div>
                    </div>
                    <div class="col-md-10" id="blogBody">
                       <!-- <div class="row">
                            <div class="col-md-12 myborder">
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
                        </div> -->
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