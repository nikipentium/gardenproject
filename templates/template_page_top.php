<?php
// It is important for any file that includes this file, to have
// check_login_status.php included at its very top.
$envelope = '<img src="images/note_off.png" alt="Notes" title="This envelope is for logged in members">';
$loginLink = '<a class="btn btn-default" href="signup.php" role="button">Signup</a>
              <a class="btn btn-default" href="login.php" role="button">Login</a>';
$menu = '<a class="btn btn-default" href="homepage.php" role="button">Home</a>';
$welcome = '';
$envelope='';
$searchsection ='';
if(isset($user_ok) && $user_ok == true) {
    $sql = "SELECT notescheck FROM users WHERE username='$log_username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $row = mysqli_fetch_row($query);
    $notescheck = $row[0];
    $sql = "SELECT id FROM notifications WHERE username='$log_username' AND date_time > '$notescheck' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $numrows = mysqli_num_rows($query);
    if ($numrows == 0) {
        $envelope = '<a href="notifications.php" title="Your notifications and friend requests"><img src="images/note_off.png" alt="Notes"></a>';
    } else {
        $envelope = '<a href="notifications.php" title="You have new notifications"><img src="images/note_on.png" alt="Notes"></a>';
    }
    $loginLink = ' <a class="btn btn-default" href="profile.php?u='.$log_username.'">My Profile</a>
                    <a class="btn btn-default" href="change_password.php">change password</a>
                    <a class="btn btn-default" href="logout.php">Log Out</a>';
     $menu = '<a class="btn btn-default" href="userlevel.php" role="button">Home</a>
              <a class="btn btn-default" href="ecommerce.php">Ecommerce</a>
              <a class="btn btn-default" href="indianscience.php">Indian Science</a>';
    $welcome = '<h5>Welcome '.$log_username.'</h5>';
    $searchsection=' <button class="btn" type="button" id="searchButton" onclick = toggleElement("search")>
                     <img height=20 width=20 src="images/Search.png" alt="search">
                 </button>';    
}
?>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
<div class="row" id="pageTop">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2" id="logo">
                <h4>The Garden Project</h4>
            </div>
            <div class="btn-group pull-right" role="group" aria-label="...">
            <?php
                echo "$loginLink";
            ?>
            </div> 
            <div class="col-md-1 pull-right" align="right">
                <?php
                    echo "$envelope";
                ?>
            </div> 
        </div>
        <div class="row" id="topMenu">
            <div class="col-md-2">
                <?php
                echo $welcome;
                ?>
            </div>
            <div class="col-md-4" align="center">
                <div class="btn-group" role="group" aria-label="...">
                <?php
                    echo "$menu";
                ?>
                </div> 
            </div>  
           <div class="col-md-1 pull-right" align="right">
                <?php
                echo $searchsection;
                ?>
            </div>
        </div>
        <div class="row" id="search">
            <div class="col-md-12">
                <br />
                <div class="row">
                    <div class="col-md-9">
                          <input class="form-control" type="text" id="searchquery" placeholder="Search The Garden Project Website" />  
                    </div>
                    <div class="col-md-2">
                        <select class="form-control">
                          <option>Blog Tags</option>
                          <option>People</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                         <button class="btn form-control" type="button" id="searchButton" onclick="search()">
                             <img height=20 width=20 src="images/Search.png" alt="search">
                         </button>
                    </div>
                </div>
                <br />
            </div>
        </div>
    </div> 
</div>
</div>
</nav>
<div class="row">
    <div class="col-md-12">
        <br />
        <br />
        <br />
        <br />
        <br />
    </div>
</div>