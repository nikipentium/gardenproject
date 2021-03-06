<?php
// Make the script run only if there is a page number posted to this script
if(isset($_POST['pn'])){
    $rpp = preg_replace('#[^0-9]#', '', $_POST['rpp']);
    $last = preg_replace('#[^0-9]#', '', $_POST['last']);
    $pn = preg_replace('#[^0-9]#', '', $_POST['pn']);
    // This makes sure the page number isn't below 1, or more than our $last page
    if ($pn < 1) { 
        $pn = 1; 
    } else if ($pn > $last) { 
        $pn = $last; 
    }
    // Connect to our database here
    include_once("../php_includes/db_conx.php");
    // This sets the range of rows to query for the chosen $pn
    $limit = 'LIMIT ' .($pn - 1) * $rpp .',' .$rpp;
    // This is your query again, it is for grabbing just one page worth of rows by applying $limit
    $sql = "select distinct p.id, p.owner, p.pagebody from pages p, blog_tags bt, tags t where t.title in ('$query1','$query2') and bt.tag_id = t.tag_id and bt.blog_id = p.id";
    $query = mysqli_query($db_conx, $sql);
    $dataString = '';
    while($row = mysqli_fetch_array($query,MYSQLI_NUM)){
        $id = $row[0];
        $owner = $row[1];
        $pagebody = $row[2];
        $dataString .= $id.'|'.$owner.'|'.$pagebody.'||';
    }
    // Close your database connection
    mysqli_close($db_conx);
    // Echo the results back to Ajax
    echo $dataString;
    exit();
}
?>