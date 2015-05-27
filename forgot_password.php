<?php
include_once ("php_includes/check_login_status.php");
// If user is already logged in, header that weenis away
if ($user_ok == true) {
	header("location: userlevel.php");
	exit();
}
?>

<?php // EMAIL LINK CLICK CALLS THIS CODE TO EXECUTE
	if (isset($_GET['username']) && isset($_GET['p'])) {
		$username = preg_replace('#[^a-z0-9]#i', '', $_GET['username']);
		$temppasshash = preg_replace('#[^a-z0-9]#i', '', $_GET['p']);
		if (strlen($temppasshash) < 10) {
			exit();
		}
        //$sql = "SELECT id FROM useroptions WHERE username='$u' AND temp_pass='$temppasshash' LIMIT 1";
		$sql = "SELECT id FROM useroptions WHERE username='$username' AND temp_pass='$temppasshash' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$numrows = mysqli_num_rows($query);   
		if ($numrows == 0) {
			header("location: message.php?msg=There is no match for that username with that temporary password in the system. We cannot proceed.");
			exit();
		} else {
			$row = mysqli_fetch_row($query);
			$id = $row[0];
			$sql = "UPDATE users SET password='$temppasshash' WHERE id='$id' AND username='$username' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			$sql = "UPDATE useroptions SET temp_pass='' WHERE username='$username' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			header("location: login.php");
			exit();
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Forgot Password</title>
		<link rel="stylesheet" type="text/css" href="style/bootstrap/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="style/style.css">

		<script src="javascript/ajax.js"></script>
		<script src="javascript/forgot_password.js"></script>
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
						<h3>Generate a temporary log in password</h3>
					</div>
				</div>
				<div class="row">
                	<div class="col-md-12">
					    <div>
&nbsp;&nbsp;&nbsp;          Step 1: Enter Your Email Address
                        </div>
						<form role="form" onsubmit="return false;" id="forgotpassform">
							<div class="form-group col-md-4">
								<label for="email">Email address:</label>
								<input id="email" class="form-control" type="text" onfocus="document.getElementById('status').innerHTML='';" maxlength="88">
							</div>
							<br /><br /><br /><br />
&nbsp;&nbsp;&nbsp;  		<button type="submit" class="btn btn-default" onclick="forgotpass()" id="forgotpassbtn">
								Generate Temporary Log In Password
							</button>
							<p id="status"></p>
						</form>
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