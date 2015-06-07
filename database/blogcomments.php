<?php
include_once("../php_includes/db_conx.php");
$tbl_status = "CREATE TABLE IF NOT EXISTS blog_comments ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                osid INT(11) NOT NULL,
                blog_id INT(11) NOT NULL,
                author VARCHAR(16) NOT NULL,
                type ENUM('a','b') NOT NULL,
                data TEXT NOT NULL,
                postdate DATETIME NOT NULL,
                PRIMARY KEY (id) 
                )"; 
$query = mysqli_query($db_conx, $tbl_status); 
if ($query === TRUE) {
    echo "<h3>status table created OK :) </h3>"; 
} else {
    echo "<h3>status table NOT created :( </h3>"; 
}
?>