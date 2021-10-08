<?php
require_once "../models/user.php";
session_start();

if (isset($_SESSION['freelancer']))
    header('Location: ./freelancer-main.php');
if (isset($_SESSION['recruiter']))
    header('Location: ./recruiter-main.php');

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    if (!empty($email)) {
        $user = User::LoadyByEmail($email);
        if ($user != null) {
            // generate new password;
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $password = "";
            $alphaLength = strlen($alphabet) - 1;
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $password .= $alphabet[$n];
            }
            echo $password;

            // update password
            $user->Password = password_hash($password, PASSWORD_DEFAULT);
            $success = $user->ChangePassword();
            if ($success) {
                $to = $email;
                $subject = "Reset Password";
                $txt = "Dear " . $user->Username . ' you can use this new password:' . $password;
                $headers = "From: webmaster@example.com" . "\r\n" .
                    "CC: somebodyelse@example.com";
                
                // send email contains the new password
                if (mail($to, $subject, $txt, $headers))
                    $msg = "A new password has been sent to your email!";
                else
                    $msg = "An error has occured!";
            } else $msg = "An error has occured!";
        } else
            $msg = "Invalid email address!";
    } else
        header('Location: ./login.php');
} else
    header('Location: ./login.php');


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forget Password</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        body {
            color: #fff;
            background: linear-gradient(90deg, #020024 0%, #0068ff 0%, #00d4ff 90%);
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <h1>Forget Password</h1>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <h5><?php echo $msg ?></h5>
                </div>
            </div>
        </div>
    </div>
</body>

</html>