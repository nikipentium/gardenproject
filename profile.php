<?php
include_once("php_includes/check_login_status.php");
// Initialize any variables that the page might echo
$u = "";
$sex = "Male";
$userlevel = "";
$profile_pic = "";
$profile_pic_btn = "";
$avatar_form = "";
$country = "";
$joindate = "";
$profession = "";
$lastsession = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
    $u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: userlevel.php");
    exit(); 
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
    echo "That user does not exist or is not yet activated, press back";
    exit(); 
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
    $isOwner = "yes";
    $profile_pic_btn = '<a href="#" onclick="return false;" onmousedown="toggleElement(\'avatar_form\')">Toggle Avatar Form</a>';
    $avatar_form  = '<form id="avatar_form" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">';
    $avatar_form .=   '<h4>Change your avatar</h4>';
    $avatar_form .=   '<input type="file" name="avatar" required>';
    $avatar_form .=   '<p><input type="submit" value="Upload"></p>';
    $avatar_form .= '</form>';
}
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
    $profile_id = $row["id"];
    $gender = $row["gender"];
    $country = $row["country"];
    $userlevel = $row["userlevel"];
    $avatar = $row["avatar"];
    $signup = $row["signup"];
    $profession = $row["profession"];
    $lastlogin = $row["lastlogin"];
    $joindate = strftime("%b %d, %Y", strtotime($signup));
    $lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
}
if($gender == "f"){
    $sex = "Female";
}
if($userlevel == 'a'){
    $userlevel = "Normal user";
}
if($userlevel == 'b'){
    $userlevel = "Expert";
}
if($userlevel == 'c'){
    $userlevel = "Admin";
}
$profile_pic = '<img src="user/'.$u.'/'.$avatar.'" alt="'.$u.'">';
if($avatar == NULL){
    $profile_pic = '<img src="images/avatardefault.gif" >';
}
?>
<?php
$isFriend = false;
if($u != $log_username && $user_ok == true){
    $friend_check = "SELECT id FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='1' OR user1='$u' AND user2='$log_username' AND accepted='1' LIMIT 1";
    if(mysqli_num_rows(mysqli_query($db_conx, $friend_check)) > 0){
        $isFriend = true;
    }
}
?>
<?php 
$friend_button = '<button class="btn btn-default" disabled style="display:none">Add Friend</button>';
// LOGIC FOR FRIEND BUTTON
if($isFriend == true){
    $friend_button = '<button style="display:block" onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendBtn\')">Unfriend</button>';
} else if($user_ok == true && $u != $log_username){
    $friend_button = '<button style="display:block" onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn\')">Add Friend</button>';
}
?>
<?php
$isSubscribe = false;
if($u != $log_username && $user_ok == true){
    $friend_check = "SELECT id FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='1' OR user1='$u' AND user2='$log_username' AND accepted='1' LIMIT 1";
    if(mysqli_num_rows(mysqli_query($db_conx, $friend_check)) > 0){
        $isSubscribe = true;
    }
}
?>
<?php 
$subscribe_button = '<button class="btn btn-default" disabled style="display:none">Subscribe</button>';
// LOGIC FOR FRIEND BUTTON
if($isSubscribe == true){
    $subscribe_button = '<button style="display:block" onclick="subscribeToggle(\'unsubscribe\',\''.$u.'\',\'subscribeBtn\')">Unsubscribe</button>';
} else if($user_ok == true && $u != $log_username){
    $subscribe_button = '<button style="display:block" onclick="subscribeToggle(\'subscribe\',\''.$u.'\',\'subscribeBtn\')">Subscribe</button>';
}
?>
<?php
$friendsHTML = '';
$friends_view_all_link = '';
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$u' AND accepted='1' OR user2='$u' AND accepted='1'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];
if($friend_count < 1){
    $friendsHTML = $u." has no friends yet";
} else {
    $max = 10;
    //dont understand after this
    $all_friends = array();
    $sql = "SELECT user1 FROM friends WHERE user2='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
    $query = mysqli_query($db_conx, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($all_friends, $row["user1"]);
    }
    $sql = "SELECT user2 FROM friends WHERE user1='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
    $query = mysqli_query($db_conx, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($all_friends, $row["user2"]);
    }
    $friendArrayCount = count($all_friends);
    if($friendArrayCount > $max){
        array_splice($all_friends, $max);
    }
    if($friend_count > $max){
        $friends_view_all_link = '<a href="view_friends.php?u='.$u.'">view all</a>';
    }
    $orLogic = '';
    foreach($all_friends as $key => $user){
            $orLogic .= "username='$user' OR ";
    }
    $orLogic = chop($orLogic, "OR ");
    $sql = "SELECT username, avatar FROM users WHERE $orLogic";
    $query = mysqli_query($db_conx, $sql);
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $friend_username = $row["username"];
        $friend_avatar = $row["avatar"];
        if($friend_avatar != ""){
            $friend_pic = 'user/'.$friend_username.'/'.$friend_avatar.'';
        } else {
            $friend_pic = 'images/avatardefault.gif';
        }
        $friendsHTML .= '<a href="profile.php?u='.$friend_username.'"><img class="friendpics" height=300 width=200 src="'.$friend_pic.'" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $u; ?></title>
        <script src="javascript/main.js"></script>
        <script src="javascript/ajax.js"></script>
        <script src="javascript/profile.js"></script>
        <link rel="stylesheet" type="text/css" href="style/bootstrap/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <link rel="stylesheet" type="text/css" href="style/profile.css">
    </head>
    <body>
        <div class="container">
            <?php
            include_once ("templates/template_page_top.php");
            ?>
            <div class="row" id="pageMiddle">
                <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3 basic" id="profilePic">
                        <div class="row" id="username" align="center">
                            <div class="col-md-12">
                                <h4><?php echo"$u" ?></h4>
                            </div>
                        </div>
                        <div class="row" id="avatar" align="center">
                            <div class="col-md-12">
                                <?php echo $profile_pic_btn; ?>
                                <?php echo $avatar_form; ?>
                                <?php echo $profile_pic; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row basic" id="aboutuser">
                            <div class="col-md-8">
                                <h2><strong>About</strong></h2><hr/>                     
                                <h3>Profession : <?php echo $profession ?></h3>
                                <h3>Country : <?php echo $country ?></h3>
                                <h3>Join Date : <?php echo $joindate; ?></h3>
                                <h3>User Level : <?php echo $userlevel; ?></h3>
                                <h3>Gender : <?php echo $sex; ?></h3>
                            </div>
                            <div class="col-md-1">
                                 <p><span id="friendBtn"><?php echo $friend_button; ?></span>                       
                            </div>
                            <div class="col-md-3">
                                 <p><span id="subscribeBtn"><?php echo $subscribe_button; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 basic" id="friendBox">
                        <div class="row">
                            <div class="col-md-12 basic">
                                <?php 
                                    if($friend_count == 1){
                                         echo $u." has ".$friend_count." friend"; 
                                    }
                                    else {
                                        echo $u." has ".$friend_count." friends"; 
                                    }                                 
                                ?>
                                <div>
                                    <p><?php echo $friendsHTML; ?></p>
                                </div>
                                <p><?php echo $friends_view_all_link; ?></p>
                            </div>
                            <div class="col-md-12 basic">
                                subscribers
                              
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 basic" id="wall">
                        <h3><?php echo"$u" ?>'s Wall</h3>
                        <div>
                            <?php include_once("templates/template_status.php"); ?>     
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <?php
            include_once ("templates/template_page_bottom.php");
            ?>
        </div>
        <div>
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
        </div>
    </body>
</html>
<!--


  
  <div id="photo_showcase" onclick="window.location = 'photos.php?u=<?php echo $u; ?>';" title="view <?php echo $u; ?>&#39;s photo galleries">
    <?php echo $coverpic; ?>
  </div>
  


-->