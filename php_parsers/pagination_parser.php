<?php
// Make the script run only if there is a page number posted to this script
if(isset($_POST['pn'])){
    $rpp = preg_replace('#[^0-9]#', '', $_POST['rpp']);
    $last = preg_replace('#[^0-9]#', '', $_POST['last']);
    $pn = preg_replace('#[^0-9]#', '', $_POST['pn']);
    $searchquery = ($_POST['query']);
    $searchOption = $_POST['option'];
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
    
    $queries = explode(" ", $searchquery);
    $querylist="";
    for($i = 0; $i < count($queries) ; $i++){
        if($i==count($queries)-1){
            $querylist .= "'".$queries[$i]."'";
        }
        else{
            $querylist .= "'".$queries[$i]."',";
        }
    }
    // This is your query again, it is for grabbing just one page worth of rows by applying $limit
    $sqlcommand = "select distinct p.id, p.owner, p.pagetitle from pages p, blog_tags bt, tags t where t.title in ($querylist) and bt.tag_id = t.tag_id and bt.blog_id = p.id $limit";
    $result = mysqli_query($db_conx, $sqlcommand) or die (mysqli_error($db_conx));
    $rows = array();
    $content = array();
    $i=0;
    $numrows = mysqli_num_rows($result);
    if($numrows<1){
        $result = "no_results";
    }
    else{
        while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
            $content["id"] = $row[0];
            $content["author"] = $row[1];
            $content["title"] = $row[2];
            $rows["blog".$i++] = $content;
        }        
    }
    // Close your database connection
    // Echo the results back to Ajax
    
    echo json_encode($rows);
    exit();
}
?>