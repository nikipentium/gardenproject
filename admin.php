<?php
include_once("php_includes/check_login_status.php");
?>
<?php
//check if user is logged in and is of the particular userlevel for expert 
if($user_ok == true && $log_userlevel=='c'){
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
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="style/bootstrap/bootstrap.js"></script>
        <script src="javascript/main.js"></script>
        <script src="javascript/ajax.js"></script>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style/bootstrap/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <title>admin page</title>
    </head>
    <body>
        <div class="container">
            <?php
            include_once ("templates/template_page_top.php");
            ?>
            <div class="row" id="pageMiddle">
                <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12" id="welcome">
                        <?php
                            echo '<p>Welcome '.$log_username.' in admin homepage</p>';
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2" id="menuContainer">
                          <div class="btn-group-vertical">
                             <button type="button" class="btn btn-primary">menu</button>
                             <button type="button" class="btn btn-primary">option1</button>
                             <button type="button" class="btn btn-primary">option2</button>
                          </div>
                    </div>
                    <div class="col-md-10" id="middleContainer">
                        middle
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