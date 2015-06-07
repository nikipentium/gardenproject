<?php
include_once ("..\php_includes\db_conx.php");
 // Ajax calls this REGISTRATION code to execute
 /*
  * it gets the following by POST
  * 1.username
  * 2.email id
  * 3.password
  * 4.gender
  * 5.country
  * 6.state
  * 7.city
  * 8.profession
  * 9.age
  * */
    if (isset($_POST["username"])) {
        // CONNECT TO THE DATABASE
        //include_once ("php_includes/db_conx.php");
        // GATHER THE POSTED DATA INTO LOCAL VARIABLES
        $username = preg_replace('#[^a-z0-9]#i', '', $_POST['username']);
        //$email = mysqli_real_escape_string($db_conx, $_POST['email']);
        $email = $_POST['email'];
        $password = $_POST['password'];
        $gender = preg_replace('#[^a-z]#', '', $_POST['gender']);
        $country = preg_replace('#[^a-z ]#i', '', $_POST['country']);
        $state = preg_replace('#[^a-z ]#i', '', $_POST['state']);
        $city = preg_replace('#[^a-z ]#i', '', $_POST['city']);
        $profession = preg_replace('#[^a-z ]#i', '', $_POST['profession']);
        $age = $_POST['age'];
        //print_r($email."\n".$username."\n".$gender."\n".$password."\n".$country."\n".$state."\n".$city."\n".$profession."\n".$age);
        // GET USER IP ADDRESS
        $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
        // DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
        $sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $u_check = mysqli_num_rows($query);
        // -------------------------------------------
        $sql = "SELECT id FROM users WHERE email='$email' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $e_check = mysqli_num_rows($query);
        // FORM DATA ERROR HANDLING
        if($username == "" || $email == "" || $password == "" || $country == "" || $state == "" || $city == "" || $profession == "" || $age == "" || $gender == "")
        {
            echo "The form submission is missing values.";
            exit();
        } else if ($u_check > 0) {
            echo "The username you entered is already taken";
            exit();
        } else if ($e_check > 0) {
            echo "That email address is already in use in the system";
            exit();
        } else if (strlen($username) < 3 || strlen($username) > 16) {
            echo "Username must be between 3 and 16 characters";
            exit();
        } else if (is_numeric($username[0])) {
            echo 'Username cannot begin with a number';
            exit();
        } else {
            // END FORM DATA ERROR HANDLING
            // Begin Insertion of data into the database
            // Hash the password and apply your own mysterious unique salt
            $p_hash = md5($password);
            /*$cryptpass = crypt($p);
             include_once ("php_includes/randStrGen.php");
             $p_hash = randStrGen(20)."$cryptpass".randStrGen(20);*/
            // Add user info into the database table for the main site table
            $sql = "INSERT INTO users (username, email, password, gender, country, state, city, profession, dob, ip, signup, lastlogin, notescheck)       
                VALUES('$username','$email','$p_hash','$gender','$country','$state','$city','$profession','$age','$ip',now(),now(),now())";
            $query = mysqli_query($db_conx, $sql);
            $uid = mysqli_insert_id($db_conx);
            // Establish their row in the useroptions table
            $sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$username','original')";
            $query = mysqli_query($db_conx, $sql);
            // Create directory(folder) to hold each user's files(pics, MP3s, etc.)
            if (!file_exists("user/$username")) {
                mkdir("../user/$username", 0755);
            }
// Email the user their activation link         
            $message = '<!DOCTYPE html>
         <html>
         <head>
         <meta charset="UTF-8"><title>yoursitename Message</title>
         </head>
         <body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://130.211.160.107/gardenproject/root/signup.php">
         </a>Garden Project Account Activation</div><div style="padding:24px; font-size:17px;">Hello ' . $username . ',<br /><br />Click the link below to activate your account when ready:<br /><br />
         <a href="http://130.211.160.107/gardenproject/root/activation.php?id=' . $uid . '&u=' . $username . '&e=' . $email . '&p=' . $p_hash . '">
         Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>' . $email . '</b></div></body></html>';

            $subject = 'Garden Account Activation';

            include_once ("../email.php");
            
        }
        exit();
    }
?>
