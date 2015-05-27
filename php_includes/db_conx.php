<?php  

$db_host = "localhost"; 

$db_username = "root";  

$db_pass = "math@1640";  

$db_name = "garden_project"; 


$db_conx = mysqli_connect("$db_host","$db_username","$db_pass", "$db_name") or die ("could not connect to mysql");  
   
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
}

?> 