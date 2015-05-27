<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Welcome page</title>
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
						about us
						<p id="about">
							College project website
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2 col-centered">
						<h2 class="text-center" id="explore">Explore</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<a class="btn btn-primary" href="signup.php" role="button">Indian Science</a>
					</div>
					<div class="col-md-2 pull-right">
						<a class="btn btn-primary pull-right" href="signup.php" role="button">E-commerce</a>
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