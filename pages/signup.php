<?php
require_once "../models/user.php";
require_once "../models/freelancer.php";
require_once "../models/recruiter.php";
session_start();

if (isset($_SESSION['freelancer']))
    header('Location: ./freelancer-main.php');
if (isset($_SESSION['recruiter']))
    header('Location: ./recruiter-main.php');

$msg = $firstName = $lastName = $email = $password = $confirmPassword = $DOB = $username = $userType = "";
$firstName_err = $lastName_err = $username_err = $email_err = $password_err = $confirmPassword_err = $DOB_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST["firstName"]);
    if (empty($firstName)) {
        $firstName_err = "Please enter your first name!";
    }

    $lastName = trim($_POST["lastName"]);
    if (empty($lastName)) {
        $lastName_err = "Please enter your last name!";
    }

    $username = trim($_POST["username"]);
    if (empty($username)) {
        $username_err = "Please enter a username!";
    }

    $email = trim($_POST["email"]);
    if (empty($email)) {
        $email_err = "Please enter your email!";
    }

    $password = trim($_POST["password"]);
    if (empty($password)) {
        $password_err = "Please enter a password!";
    }

    $confirmPassword = trim($_POST["confirmPassword"]);
    if (empty($confirmPassword)) {
        $confirmPassword_err = "Please re-enter the password!";
    } else {
        if ($password != $confirmPassword)
            $confirmPassword_err = "Please make sure your passwords match!";
    }

    $DOB = trim($_POST["DOB"]);
    if (empty($DOB)) {
        $DOB_err = "Please enter your date of birth!";
    }

    $userType = trim($_POST["userType"]);

    if ($userType == 1 || $userType == 2) {
        if (empty($firstName_err) && empty($lastName_err) && empty($username_err) && empty($email_err) && empty($password_err) && empty($confirmPassword_err) && empty($DOB_err)) {

            $valid = User::ValidateUsername($username);
            if (!$valid)
                $username_err = "username already exists.";
            else {
                $user = new User();
                $user->FirstName = $firstName;
                $user->LastName = $lastName;
                $user->Email = $email;
                $user->Username = $username;
                $user->Password = password_hash($password, PASSWORD_DEFAULT);
                $user->DOB = $DOB;
                $user->UserTypeId = $userType;
                $user->Save();
                if ($user->Id > 0) {
                    $freelancerId = $recruiterId = 1;
                    if ($userType == 1) {
                        $freelancer = new Freelancer();
                        $freelancer->UserId = $user->Id;
                        $freelancerId = $freelancer->Save();
                    } else if ($userType == 2) {
                        $recruiter = new Recruiter();
                        $recruiter->UserId = $user->Id;
                        $recruiterId = $recruiter->Save();
                    }
                    if ($freelancerId == 0 && $recruiterId == 0)
                        $msg = "An error was occured";
                    else {
                        echo '<script type="text/javascript">
                        alert("You have successfully registred, login now!");
                        window.location="./login.php";
                        </script>';
                    }
                } else
                    $msg = "An error was occured!";
            }
        }
    } else
        $msg = "Invalid user type!";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-datepicker.min.css">

    <script src="../js/jquery-3.6.0.js"></script>
    <script src="../js/bootstrap-datepicker.min.js"></script>

    <style>
        body {
            color: #fff;
            background: linear-gradient(90deg, #0055aa 0%, #22cccc 80%);
        }

        .hidden {
            display: none;
        }

        .signup-form form {
            margin: 30px auto;
            text-align: center;
            padding: 30px;
            background: #fff;
            border-radius: 3px;
        }

        .signup-form h2 {
            color: #888;
            margin: 5px 0 30px;
        }

        .button {
            border: 2px solid #fff ;
            width: 200px;
            border-radius: 20px;
        }

    </style>
    <script>
        $(function() {
            $('.datepicker').datepicker();
        });

        function changeActiveButton(btnId) {
            $('#registerForm').show();

            if (btnId == "btnFreelancer") {
                $('#btnFreelancer').removeClass('btn-secondary').addClass('btn-primary');
                $('#btnRecruiter').removeClass('btn-primary').addClass('btn-secondary');
            } else {
                $('#btnFreelancer').removeClass('btn-primary').addClass('btn-secondary');
                $('#btnRecruiter').removeClass('btn-secondary').addClass('btn-primary');
            }
        }
    </script>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="row ">
                <div class="col-3"></div>
                <div class="col-6 signup-form">
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <h2 class="text-center">Sign up as:</h2>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label onclick="changeActiveButton(this.id)" id="btnFreelancer" class="btn btn-lg button <?php if($userType=="" || $userType==1) echo'btn-primary'; else echo'btn-secondary';?>">
                                            <input type="radio" name="userType" value="1" <?php if($userType==1) echo'checked';?>> Freelancer
                                        </label>
                                        <label onclick="changeActiveButton(this.id)" id="btnRecruiter" class="btn btn-primary btn-lg button <?php if($userType=="" || $userType==2) echo'btn-primary'; else echo'btn-secondary';?>" >
                                            <input type="radio" name="userType" value="2" <?php if($userType==2) echo'checked';?> > Recruiter
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($msg != "") echo '<div class="alert alert-danger p-1 mt-1">' . $msg . '</div>'; ?>
                        <div id="registerForm" <?php if ($userType == "") echo 'class="hidden"'; ?>>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" class="form-control" name="firstName" placeholder="First Name*" required="required" value="<?php echo $firstName ?>">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" name="lastName" placeholder="Last Name*" required="required" value="<?php echo $lastName ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control <?php if ($username_err != "") echo 'is-invalid'; ?>" name="username" placeholder="Username*" required="required" value="<?php echo $username ?>">
                                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                            </div>

                            <div class="form-group">
                                <input type="email" class="form-control <?php if ($email_err != "") echo 'is-invalid'; ?>" name="email" placeholder="Email*" required="required" value="<?php echo $email ?>">
                                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <input type="password" class="form-control <?php if ($password_err != "") echo 'is-invalid'; ?>" name="password" placeholder="Password*" required="required">
                                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                    </div>
                                    <div class="col">
                                        <input type="password" class="form-control <?php if ($confirmPassword_err != "") echo 'is-invalid'; ?>" name="confirmPassword" placeholder="Confirm Password*" required="required">
                                        <span class="invalid-feedback"><?php echo $confirmPassword_err; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input class="datepicker form-control <?php if ($DOB_err != "") echo 'is-invalid'; ?>" name="DOB" placeholder="Date of birth*" required="required" data-date-format="yyyy-mm-dd" value="<?php echo $DOB ?>">
                                <span class="invalid-feedback"><?php echo $DOB_err; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary btn-lg btn-block" value="Register now" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>