<?php // Ajax calls this EMAIL CHECK code to execute
include_once ("..\php_includes\db_conx.php");
//ajax.send("product_name="+option+"&message="+message+"&blog_id"+pageid);
$product_name = $_POST['product_name'];
$blog_id = $_POST['blog_id'];
$message = $_POST['message'];
$sqlCommand = "SELECT id FROM products WHERE product_name='$product_name'";
$result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
$row = mysqli_fetch_array($result, MYSQLI_BOTH);
$product_id = $row['id'];
$sqlCommand = "INSERT INTO blog_product(blog_id,product_id,relation) VALUES('$blog_id','$product_id','$message')";
$result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
print_r("success");
?>