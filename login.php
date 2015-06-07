<?php
include_once("php_includes/check_login_status.php");
// If user is already logged in, header that user away
if($user_ok == true){
    //header("location: userlevel.php?u=".$_SESSION["username"]);
    header("location: userlevel.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Login page</title>
		<link rel="stylesheet" type="text/css" href="style/bootstrap/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="style/style.css">
        <script src="javascript/ajax.js"></script>
        <script src="javascript/login.js"></script>
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
					<h2>Login</h2>
					<form class="form-horizontal" role="form" onsubmit="return false;">
						<div class="form-group">
							<label class="control-label col-sm-2" for="email">Email:</label>
							<div class="col-sm-4">
								<input type="email" class="form-control" onfocus="emptyElement('status')" id="email" placeholder="Enter email">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="password">Password:</label>
							<div class="col-sm-4">
								<input type="password" class="form-control" onfocus="emptyElement('status')" id="password" placeholder="Enter password">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" onclick="login()" class="btn btn-default" id="loginbtn">
									Login
								</button>
								<span id="status"></span>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="row" id="forgotPassword">
			    <div class="col-md-4 col-md-push-2">
			        <a href="forgot_password.php">forgot password?</a>
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