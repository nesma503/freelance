<?php
require_once "../models/user.php";
require_once "../models/freelancer.php";
require_once "../models/recruiter.php";
session_start();

if (isset($_SESSION['freelancer']))
    header('Location: ./freelancer-main.php');
if (isset($_SESSION['recruiter']))
    header('Location: ./recruiter-main.php');

$username_err = $password_err = $invalid = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    // Check if username is empty
    if (empty($username)) {
        $username_err = "Please enter your username!";
    }

    $password = trim($_POST["password"]);
    // Check if password is empty
    if (empty($password)) {
        $password_err = "Please enter your password!";
    }

    // check if all fields are not empty
    if (empty($username_err) && empty($password_err)) {
        // get user from db using the username
        $user = User::LoadyByUsername($username);
        if ($user == null)
            $invalid = "Invalid username or password!";
        else {
            if (password_verify($password, $user->Password)) {
                if ($user->UserTypeId == 1) {
                    $freelancer = Freelancer::Load($user);
                    if ($freelancer != null) {
                        $_SESSION['freelancer'] = $freelancer;
                        header('Location: ./freelancer-main.php');
                    } else
                        $invalid = "An error was occured!";
                } else if ($user->UserTypeId == 2) {
                    $recruiter = Recruiter::Load($user);
                    if ($recruiter != null) {
                        $_SESSION['recruiter'] = $recruiter;
                        header('Location: ./recruiter-main.php');
                    } else
                        $invalid = "An error was occured!";
                }
                $invalid = "An error was occured!";
            } else
                $invalid = "Invalid username or password!";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery-3.6.0.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <style>
        body {
            color: #fff;
            background: linear-gradient(90deg, #0055aa 0%, #22cccc 80%);
        }

        .wrapper {
            margin-top: 50px;
        }

        .login-form form {
            margin: 30px auto;
            text-align: center;
            padding: 30px;
            background: #fff;
            border-radius: 3px;
        }

        .login-form h2 {
            color: #888;
            margin: 5px 0 30px;
        }

        .login-form form a {
            color: #7a7a7a;
        }

        .login-form a {
            color: #fff;
            text-decoration: underline;
        }

        .modal-header,
        .modal-body p {
            color: #7a7a7a;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <h1>Welcome to Lebanese Recruit</h1>
                </div>
            </div>
            <div class="row ">
                <div class="col-4"></div>
                <div class="col-4 login-form">
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <h2 class="text-center">Login</h2>
                        <div class="form-group has-error">
                            <input type="text" class="form-control <?php if ($username_err != "") echo 'is-invalid'; ?>" name="username" placeholder="Username" required="required">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control  <?php if ($password_err != "") echo 'is-invalid'; ?>" name="password" placeholder="Password" required="required">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Sign in" />
                        </div>
                        <?php if ($invalid != "") echo '<div class="alert alert-danger p-1 mt-1"><i class="fa fa-fw fa-exclamation-triangle"></i>' . $invalid . '</div>'; ?>
                        <p><a href="" data-toggle="modal" data-target="#modal">Lost your Password?</a></p>
                    </form>
                    <p class="text-center small">Don't have an account? <a href="./signup.php">Sign up here!</a></p>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="frmResetPassword" action="./forget-password.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Lost your password?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Enter your email address to reset your password</p>
                            <input type="text" class="form-control" name="email" placeholder="Email address*" required="required">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Submit"/>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>