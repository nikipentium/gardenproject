<?php
include_once ("..\php_includes\db_conx.php");
$sqlCommand = "SELECT product_name FROM products";
$result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
$numrows = mysqli_num_rows($result);
if($numrows<1){
    echo "no products";
}
else {
    $options = array();
    $i=0;
    while($row = mysqli_fetch_array($result, MYSQLI_BOTH))
    {
        $options[$i++] = $row[0];
    }
	echo json_encode($options);
}
exit();
?>