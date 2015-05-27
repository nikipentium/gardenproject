<?php
include_once("php_includes/check_login_status.php");
?>
<?php
//check if user is logged in and is of the particular userlevel for expert 
if($user_ok == true && $log_userlevel=='b'){
    //display some dynamic default content from the owner's(log_username) database
}
else{
    //header to userlevel page
    header("location: userlevel.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="javascript/ajax.js"></script>
        <script src="javascript/expert.js"></script>
        <script src="javascript/main.js"></script>
        <link rel="stylesheet" type="text/css" href="style/bootstrap/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <link rel="stylesheet" type="text/css" href="style/expert.css">
        <title>expert page</title>
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
                          <div class="btn-group-vertical">
                             <button type="button" class="btn btn-primary">Dashboard</button>
                             <button type="button" class="btn btn-primary" id="viewBlogButton">My Blogs</button>
                             <button type="button" class="btn btn-primary">My Polls</button>
                          </div>
                    </div>
                    <div class="col-md-10" id="middleContainer">
                        middle
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