<?php
include_once ("php_includes/check_login_status.php");
//print_r($_SESSION);
// If the page requestor is not logged in, usher them away
if ($user_ok != true || $log_username == "") {
	header("location: login.php");
	exit();
}
?>
<?php // Ajax calls this OLD PASSWORD CHECK code to execute

	if (isset($_POST["passwordcheck"])) {
		$password = md5($_POST['passwordcheck']);
		$sql = "SELECT password FROM users WHERE username='$log_username' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row = mysqli_fetch_row($query);
		$db_password = $row[0];
		//$db_password = md5($db_password);
		if ($db_password != $password) {
			echo 'negative';
			exit();
		} else {
			echo 'positive';
			exit();
		}
	}
?>
<?php
// AJAX CALLS THIS Change password CODE TO EXECUTE
if (isset($_POST["tp"])) {
	// CONNECT TO THE DATABASE
	include_once ("php_includes/check_login_status.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
	$tp = md5($_POST['tp']);//old password
	$np = md5($_POST['np']);//new password
	$rp = md5($_POST['rp']);//retype password
	// FORM DATA ERROR HANDLING
	if ($tp == "" || $np == "" || $rp == "") {
		echo "change_password_failed";
		exit();
	} else {
		// END FORM DATA ERROR HANDLING
		$sql = "SELECT password FROM users WHERE username='$log_username' AND activated='1' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row = mysqli_fetch_row($query);
		$db_pass_str = $row[0];
		if ($tp != $db_pass_str) {
			echo "change_password_failed";
			exit();
		} else {
			// UPDATE THEIR "password" FIELD
			$sql = "UPDATE users SET password='$np' WHERE username='$log_username' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			$_SESSION["password"]=$np;
			echo "$log_username";
			exit();
		}
	}
	exit();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Change Password</title>
        <script src="javascript/ajax.js"></script>
        <link rel="stylesheet" type="text/css" href="style/bootstrap/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="style/style.css">
		<script>
			function emptyElement(x) {
				document.getElementById(x).innerHTML = "";
			}

			function checkpassword() {
				var p = document.getElementById("old_password").value;
				if (p != "") {
					document.getElementById("passwordstatus").innerHTML = 'checking ...';
					var ajax = ajaxObj("POST", "change_password.php");
					ajax.onreadystatechange = function() {
						if (ajaxReturn(ajax) == true) {
							if (ajax.responseText.trim() == "negative")
								document.getElementById("passwordstatus").innerHTML = "does not match";
							else {
								//alert(ajax.responseText);
								document.getElementById("passwordstatus").innerHTML = "match";
							}
						}
					};
					ajax.send("passwordcheck=" + p);
				}
			}

			function changePassword() {
				var tp = document.getElementById("old_password").value;
				var np = document.getElementById("new_password").value;
				var rp = document.getElementById("re_password").value;
				if (tp == "" || np == "" || rp == "") {
					document.getElementById("status").innerHTML = "Fill out all of the form data";
				} else if (np != rp) {
					document.getElementById("status").innerHTML = "Your password fields do not match";
				} else {
					document.getElementById("submit_btn").style.display = "none";
					document.getElementById("status").innerHTML = 'please wait ...';
					var ajax = ajaxObj("POST", "change_password.php");
					ajax.onreadystatechange = function() {
						if (ajaxReturn(ajax) == true) {
							if (ajax.responseText.trim() == "change_password_failed") {
								document.getElementById("status").innerHTML = "change password unsuccessful, please try again.";
								document.getElementById("submit_btn").style.display = "block";
							} else {
								alert("success your password has been changed");
								window.location = "userlevel.php";
							}
						}
					}
					ajax.send("tp=" + tp + "&np=" + np + "&rp=" + rp);
				}
			}
		</script>
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
			<h3>Change password</h3>
			<!-- Change password FORM -->
			<form id="change_password_form" onsubmit="return false;">
				<!--return false since ajax processing -->
				<div>
					Enter Old Password:
				</div>
				<input type="password" onblur="checkpassword()" id="old_password" onfocus="emptyElement('status')" maxlength="100">
				<span id="passwordstatus"></span>
				<br />
				<br />
				<div>
					Enter New Password:
				</div>
				<input type="password" id="new_password" onfocus="emptyElement('status')" maxlength="100" >
				<br />
				<br />
				<div>
					Retype New Password:
				</div>
				<input type="password" id="re_password" onfocus="emptyElement('status')" maxlength="100" >
				<br />
				<br />
				<button id="submit_btn" onclick="changePassword()" >
					Change Password
				</button >
				<p id="status"></p>
			</form>
			<!-- Change password FORM -->
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

