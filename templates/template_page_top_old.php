<?php
// It is important for any file that includes this file, to have
// check_login_status.php included at its very top.
$envelope = '<img src="images/note_off.png" alt="Notes" title="This envelope is for logged in members">';
$loginLink = '<ul class="list-inline col-centered">
                <li>
                    <a class="btn btn-primary" href="signup.php" role="button">Signup</a>
                </li>
                <li>
                    <a class="btn btn-primary" href="login.php" role="button">Login</a>
                </li>
            </ul>';
$menu = '<div class="col-md-1" id="home">
            <a class="btn btn-primary" href="homepage.php" role="button">Home</a>
        </div>';
if($user_ok == true) {
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
    $loginLink = '<ul class="list-inline col-centered">
                     <li>
                        '.$envelope.'
                     </li>
                     <li>
                          <a class="btn btn-primary" href="profile.php?u='.$log_username.'">My Profile</a>
                     </li>
                     <li>
                         <a class="btn btn-primary" href="logout.php">Log Out</a>
                     </li>
                     </ul>';
     $menu = '<div class="col-md-1" id="home">
                    <a class="btn btn-primary" href="userlevel.php" role="button">Home</a>
              </div>
                <div class="col-md-2" id="changepwd">
                         <a class="btn btn-primary" href="change_password.php">change password</a>
              </div>
                <div class="col-md-2" id="ecommerce">
                         <a class="btn btn-primary" href="ecommerce.php">Ecommerce</a>
              </div>
               <div class="col-md-2" id="indianscience">
                         <a class="btn btn-primary" href="ecommerce.php">Indian Science</a>
              </div>';
}
?>
<div id="pageTop">
	<div class="row">
		<div class="col-md-2" id="logo">
			Logo
		</div>
		<div class="col-md-7" id="pageHeading">
			<h2>Welcome to the Garden Project</h2>
		</div>
		<div class="col-md-3" class="button" id="loginbox">
            <?php
                echo "$loginLink";
            ?>
		</div>
	</div>
	<div class="row" id="topMenu">
		<?php
		  echo "$menu";
		?>
	</div>
    <div class="row">
		  <div class="col-md-12" id="welcome">
			<?php
                echo '<p>Welcome '.$log_username.'</p>';
			?>
		</div>
	</div>
</div>
