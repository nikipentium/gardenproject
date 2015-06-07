<?php
include_once("php_includes/check_login_status.php");
?>
<?php
//check if user is logged in and is of the particular userlevel for expert 
if($user_ok == true && $log_userlevel=='a'){
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
        <link rel="stylesheet" type="text/css" href="style/bootstrap/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <title>user page</title>
    </head>
    <body>
        <div class="container">
            <?php
            include_once ("templates/template_page_top.php");
            ?>
            <div class="row" id="pageMiddle">
                <div class="col-md-12">
                in user homepage
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