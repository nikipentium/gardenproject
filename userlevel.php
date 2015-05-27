<!--
1.	after login check user level and direct to corresponding user level user page.
	user level - a = normal user
	user level - b = experts
	user level - c = admin
	user level - d = developer
2.  if user wants to navigate to any other user page(normal user or expert) he has to get through this page	
	
	-->

<?php
	include_once ("php_includes/check_login_status.php");
	$u=$log_username;//holds username of owner of page being visited
	$ul="";//holds userlevel
	//get user name from url link
	/*if (isset($_GET["u"])) {
		$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	}*/
	//print_r($u);
	//check user name against database
	// Select the member from the users table
	$sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
	$user_query = mysqli_query($db_conx, $sql);
	// Now make sure that user exists in the table
	$numrows = mysqli_num_rows($user_query);
	if ($numrows < 1) {
		echo "That user does not exist or is not yet activated, press back";
		exit();
	}
	else {
		//get the userlevel of the user whose page is being visited
		$sql = "SELECT userlevel FROM users WHERE username= '$u' LIMIT 1";
		$result = mysqli_query($db_conx, $sql);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		$ul=$row[0];
		//print_r($ul);
		
//for EFFICIENCY SHOULD I USE SWITCH CASE FOR BELOW CODE??????????????????????????????????????? ?	
	
	
		//if user level is 'a' then direct to user.php
		if($ul=='a'){
			print_r("working directing to normal user page") ;
			//header("location:user.php?u=".$u);
            header("location:user.php");
		}
		//if user level is 'b' then direct to expert.php
		if($ul=='b'){
			print_r("working directing to expert page") ;
			//header("location:expert.php?u=".$u);
            header("location:expert.php");
		}
		//if user level is 'c' then direct to admin.php
		if($ul=='c'){
			print_r("working directing to admin page") ;
			//header("location:admin.php?u=".$u);
			header("location:admin.php");
		}
	}
?>