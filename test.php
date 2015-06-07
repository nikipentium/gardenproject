<?php phpinfo() ?>
<?php
/*include_once("php_includes/check_login_status.php");
        $count = array();
        $i=0;
        $sqlCommand = "SELECT id,owner,linklabel FROM pages WHERE owner <> '$log_username' ";
        $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $count[$i++] = $row;
        }
        print_r($count) ;
        exit();
?>
<?php
if (isset($_GET['jon'])) {
    function abc() {
        echo "hello";
    }

    exit();
}
*/?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="style/bootstrap/bootstrap.js"></script>
		<script>
        /*$(document).ready(
		  $.ajax({url:"http://localhost//xampp/phptest/gardenproject/test.php?jon=shit"})
		      .success(function(data){
		          $("#text").html(data);
		      });
		 );*/
		 function inone(){
		     var menu = document.getElementById('text');
		     (function(){
                // create almost any element this way...
                var el = document.createElement("div");
                // add some text to the element...
                el.innerHTML = "Copyright &copy; 2004-"+ (new Date).getFullYear() + " flip-flop media";
                // "document.body" can be any element on the page.
                document.body.appendChild(el);
            }());
		 }
		</script>
	</head>
	<body>
	    <h1 id="text" name="text">hello</h1>
	    <button onclick="inone()"> click </button>
	</body>
</html>