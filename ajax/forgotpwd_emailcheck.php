<?php // AJAX CALLS THIS CODE TO EXECUTE
    include_once ("..\php_includes\db_conx.php");
    if (isset($_POST['email'])) {
        $email = mysqli_real_escape_string($db_conx, $_POST['email']);
        $sql = "SELECT id, username FROM users WHERE email='$email' AND activated='1' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $numrows = mysqli_num_rows($query);
        if ($numrows > 0) {
            while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                $id = $row["id"];
                $username = $row["username"];
            }
            $emailcut = substr($email, 0, 4);
            $randNum = rand(10000, 99999);
            $tempPass = "$emailcut$randNum";
            $hashTempPass = md5($tempPass);
            $sql = "UPDATE useroptions SET temp_pass='$hashTempPass' WHERE username='$username' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);

            
            $message = '<h2>Hello ' . $username . '</h2><p>This is an automated message from Garden Project.
If you did not recently initiate the Forgot Password process, please disregard this email.</p>
<p>You indicated that you forgot your login password.
We can generate a temporary password for you to log in with, then once logged in you can change your password to anything you like.</p>
<p>After you click the link below your password to login will be:<br /><b>' . $tempPass . '</b></p>
<p><a href="http://localhost/xampp/phptest/gardenproject/root/forgot_password.php?username=' . $username . '&p=' . $hashTempPass . '">
Click here now to apply the temporary password shown below to your account</a></p>
<p>If you do not click the link in this email, no changes will be made to your account.
In order to set your login password to the temporary password you must click the link above.</p>';
            $subject = 'Garden Account Temporary Password';

            include_once ("../email.php");

        } else {
            echo "no_exist";
        }
        exit();
    }
?>