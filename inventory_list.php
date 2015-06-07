<?php
//check login status
include_once ("php_includes/check_login_status.php");
if ($user_ok != true) {
    header("location: login.php");
}
?>
<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php
    // Delete Item Question to Admin, and Delete Product if they choose
    if (isset($_GET['deleteid'])) {
        echo 'Do you really want to delete product with ID of ' . $_GET['deleteid'] . '? <a href="inventory_list.php?yesdelete=' . $_GET['deleteid'] . '">Yes</a> | <a href="inventory_list.php">No</a>';
        exit();
    }
    if (isset($_GET['yesdelete'])) {
        // remove item from system and delete its picture
        // delete from database
        $id_to_delete = $_GET['yesdelete'];
        $sql = mysqli_query($db_conx,"DELETE FROM product_owner WHERE product_id='$id_to_delete'") or die(mysqli_error($db_conx));
        // unlink the image from server
        // Remove The Pic -------------------------------------------
        $pictodelete = ("../inventory_images/$id_to_delete.jpg");
        if (file_exists($pictodelete)) {
            unlink($pictodelete);
        }
        header("location: inventory_list.php");
        exit();
    }
?>
<?php
    // Parse the form data and add inventory item to the system
    if (isset($_POST['productsMenu'])) {
        $productsMenu = $_POST['productsMenu'];
         $quantity = $_POST['quantity'];
         $price =$_POST['price'];
         $details = $_POST['details'];
        if($productsMenu == 'other'){
            //update product table and product owner table
            $product_name = $_POST['product_name'];     
            $category = $_POST['category'];
            $subcategory = $_POST['subcategory'];
            $sqlCommand = "INSERT INTO products (product_name,category,subcategory) VALUES('$product_name','$category','$subcategory')";
            $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
            $sqlCommand = "SELECT id FROM products WHERE product_name = '$product_name'";
            $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
            $row = mysqli_fetch_array($result, MYSQLI_BOTH);
            $product_id = $row[0];
            $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
            $sqlCommand = "INSERT INTO product_owner (product_id,user_id,quantity,details,price,date_added) 
                            VALUES($product_id,$log_id,$quantity,'$details',$price,now())";
            $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
        }
        else{
            //update only product owner table 
            $sqlCommand = "SELECT id FROM products WHERE product_name = '$productsMenu'";
            $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
            $row = mysqli_fetch_array($result, MYSQLI_BOTH);
            $product_id = $row[0]; 
            /*$sqlCommand = "SELECT product_id FROM product_owner WHERE product_id=$product_id";
            $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
            $numrows= mysqli_num_rows($result);*/
            //if($numrows < 1){
                $sqlCommand = "INSERT INTO product_owner (product_id,user_id,quantity,details,price,date_added) 
                                VALUES($product_id,$log_id,$quantity,'$details',$price,now())";
                $result = mysqli_query($db_conx, $sqlCommand) or die (mysqli_error($db_conx));
            //}
            /*else{
                //update the quantity and price at product_id row
                $sqlCommand = "UPDATE TABLE product_owner SET quantity=$quantity, price=$price, date_added=now() WHERE product_id=$product_id AND user_id=$log_id";   
            }*/
        }
               
        // See if that product name is an identical match to another product in the system
        
       /* $sql = mysqli_query($db_conx,"SELECT id FROM products WHERE product_name='$product_name' LIMIT 1")or die(mysqli_error($db_conx));
        $productMatch = mysql_num_rows($sql);
        // count the output amount
        if ($productMatch > 0) {
            echo 'Sorry you tried to place a duplicate "Product Name" into the system, <a href="inventory_list.php">click here</a>';
            exit();
        }*/
        // Place image in the folder
        $newname = "$pid.jpg";
        move_uploaded_file($_FILES['fileField']['tmp_name'], "inventory_images/$newname");
        header("location: inventory_list.php");
        exit();
    }
?>
<?php

// This block grabs the whole list for viewing
$product_list = "";
$sqlCommand = "SELECT DISTINCT p1.id,p1.product_name FROM products p1,product_owner p2 WHERE p2.user_id=$log_id AND p1.id=p2.product_id";
$result = mysqli_query($db_conx, $sqlCommand);
while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
    $product_name = $row["product_name"];
    $id = $row["id"];
    $product_list .= "Product Name ".$product_name." &bull; <a href='inventory_list.php?deleteid=$id'>delete</a><br/>";
    $sqlCommand = "SELECT quantity,price,details,date_added FROM product_owner WHERE product_id=$id";
    $result2= mysqli_query($db_conx,$sqlCommand);
    while($row = mysqli_fetch_array($result2, MYSQLI_BOTH)){
        $quantity = $row['quantity'];
        $price = $row['price'];
        $details = $row['details'];
        $date_added = $row['date_added'];
        $product_list .= "Date Added : ".$date_added." | Price : ".$price." | Quantity : ".$quantity." | details : ".$details."<br/>";
    }
}

$sqlCommand = "SELECT * FROM product_owner WHERE user_id=$log_id ORDER BY date_added DESC";
$sql = mysqli_query($db_conx,$sqlCommand) or die (mysqli_error($db_conx));
$productCount = mysqli_num_rows($sql);
// count the output amount
if ($productCount < 1) {
    $product_list = "You have no products listed in your store yet";
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Inventory List</title>
		<script src="javascript/main.js"></script>
		<script src="javascript/ajax.js"></script>
		<script src="javascript/search.js"></script>
		<script src="javascript/inventory.js"></script>
		<link rel="stylesheet" type="text/css" href="style/bootstrap/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="stylesheet" type="text/css" href="style/blog.css">
	</head>
	<body>
		<div class="container">
            <!--<?php
            include_once ("templates/template_page_top.php");
            ?> -->
			<div class="row" id="pageMiddle">
                <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2" id="menuContainer">
                        <div>
                            <h3> Ecommerce</h3>
                            <hr />
                        </div>
                          <div class="btn-group-vertical">
                              <a class="btn btn-primary" href="inventory_list.php">My Inventory</a>
                              <a class="btn btn-primary" href="market.php?type=all">The Market</a>
                              <a class="btn btn-primary" href="">My Cart</a>
                          </div>
                    </div>
                    <div class="col-md-10 myborder" id="middle">
                        <div class="row">
                            <div class="col-md-12 myborder">
                               <div class="row">
                                   <div class="col-md-12 myborder">
                                       <div align="right" style="margin-right:32px;">
                                        <a href="inventory_list.php#inventoryForm">+ Add New Inventory Item</a>
                                       </div>   
                                   </div>                                    
                               </div>
                               <div class="row">
                                   <div class="col-md-12 myborder">
                                       <div align="left" style="margin-left:24px;">
                                            <h2>Inventory list</h2>
                                            <?php echo $product_list; ?>
                                            <hr />
                                       </div>
                                   </div>
                               </div>
                               <div class="row">
                                   <div class="col-md-12 myborder">
                                        <a name="inventoryForm" id="inventoryForm"></a>
                                        <h3> Add New Inventory Item Form </h3>
                                        <form action="inventory_list.php" name="myForm" id="myform" method="post" role="form">
												<div class="form-group">	
												    <label class="control-label col-sm-2">Product Name:</label>                                          
                                                    <select class="form-control" name="productsMenu" id="productsMenu">
                                                        <option value="other">other</option>                                                                                                                                                                           <
                                                   </select> 
												</div>
												<div class="form-group" >
												    <input  name="product_name" class="form-control" type="text" id="product_name" placeholder="product name" size="64" />                                                      
												</div>
												<div class="form-group">
                                                    <input name="price" class="form-control" type="text" id="price" size="12" placeholder="Price"/>
												</div>
												<div class="form-group">
                                                    <input name="quantity" class="form-control" type="text" id="quantity" size="12" placeholder="Quantity"/>
                                                </div>
                                                <div class="form-group" id="category_box" >
                                                    <label class="control-label col-sm-2">Category:</label>                                          
                                                    <select class="form-control" name="category" id="category">
                                                        <option value="Plants">Plants</option>
                                                        <option value="Seeds">Seeds</option>
                                                   </select>                                                                         
                                                </div>
                                                <div class="form-group" id="sub_category_box" >
                                                    <label class="control-label col-sm-2">Sub Cateogory:</label> 
                                                    <select class="form-control" name="subcategory" id="subcategory">
                                                        <option value=""></option>
                                                        <option value="Medicinal Plants">Medicinal Plants</option>
                                                        <option value="Decorative Plants">Decorative Plants</option>
                                                        <option value="Fruits">Fruits</option>
                                                        <option value="Vegetables">Vegetables</option>
                                                    </select></td>
                                                </div>
												<div class="form-group" id="product_details_box">
												    <label class="control-label col-sm-2">Product Details</label>
												    <textarea class="form-control" name="details" id="details" cols="64" rows="5"></textarea>
												</div>	
												<div class="form-group" id="image_box">
												    <label class="control-label col-sm-2">Product Image</label>
                                                    <input class="form-control" type="file" name="fileField" id="fileField" />     
												</div>
												 <br />
												<div class="form-group">							                    
													<input class="btn btn-default" type="submit" name="button" id="button" value="Add Item" />
											    </div>													
											</form>									
            								</div>
                                       </div>                       
                                    </div>
                                </div>
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