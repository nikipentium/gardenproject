<?php
include_once("php_includes/check_login_status.php");
// If user is already logged in, header that user away
if($user_ok == true){
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
		<title>Signup page</title>
		<script src="javascript/location.js"></script>
		<script src="javascript/signup.js"></script>
		<script src="javascript/ajax.js"></script>
		<link rel="stylesheet" type="text/css" href="style/bootstrap/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="style/style.css">
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
						<h2>Sign up</h2>
						<form role="form" id="signupForm" onsubmit="return false;">
							<div class="form-group col-sm-5">
								<label  for="username">Username:</label>
								<span id="unamestatus"></span>
								<input type="text" class="form-control" id="username" onkeyup="restrict('username')" onfocus="emptyElement('unamestatus')" placeholder="Enter username">
							</div>
							<br /><br /><br /><br />
							
							<div class="form-group col-sm-5">
								<label  for="email">Email:</label>
								<span id="emailstatus"></span>
								<input type="email" class="form-control"  id="email" onkeyup="restrict('email')" onfocus="emptyElement('emailstatus')" placeholder="Enter email">
							</div>
							<br /><br /><br /><br />
							
							<div class="form-group col-sm-5">
								<label  for="password">create Password:</label>
								<span id="passwordstatus"></span>
								<input type="password" class="form-control" id="password" onfocus="emptyElement('passwordstatus')" placeholder="Enter password">
							</div>
							<br /><br /><br /><br />
							
							<div class="form-group col-sm-5">
								<label  for="confirmPassword" >confirm password:</label>
								<span id="matchstatus"></span>
								<input type="password" class="form-control" id="confirmPassword" onfocus="emptyElement('matchstatus')" placeholder="retype password">
							</div>
							<br /><br /><br />
							<span id="genderstatus"></span>
							<br />
							<div class="form-group">
&nbsp;&nbsp;&nbsp;				<label  >Gender:</label>&nbsp;&nbsp;&nbsp; <label class="radio-inline" for="male">
									<input type="radio" onfocus="emptyElement('genderstatus')" name="genderOption" id="male">
									Male</label>
								<label class="radio-inline" for="female">
									<input type="radio" onfocus="emptyElement('genderstatus')" name="genderOption" id="female">
									Female</label>
							</div>
							<div class="form-group">
&nbsp;&nbsp;&nbsp;				<label  for="country">Country:</label>&nbsp;&nbsp;&nbsp; <select id="country" ></select>
								<br/>
								<script language="javascript">
									populateCountries("country", "state");
								</script>
							</div>
							<div class="form-group">
&nbsp;&nbsp;&nbsp;				<label  for="state">State:</label>&nbsp;&nbsp;&nbsp; <select id="state"></select>
							</div>
							<div class="form-group col-sm-5">
								<label  for="city">City:</label>
								<input type="text" class="form-control" id="city" placeholder="Enter city">
							</div>
							<br /><br /><br /><br />
							<div class="form-group col-sm-5">
								<label  for="profession">Profession:</label>
								<input type="text" class="form-control" id="profession" placeholder="Enter profession">
							</div>
							<br /><br /><br /><br />
							<div class="form-group col-sm-5">
								<label  for="dob">Age:</label>
								<input type="date" id="dob">
							</div>
							<br /><br /><br /><br />
							<div class="form-group">
								<div class="col-sm-10">
									<span id="signupstatus"></span>
									<button type="submit" class="btn btn-primary" id="signupButton">
										Signup
									</button>
								</div>
							</div>
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