<?php
session_start();
require_once "../config.php";
$username = $password = $invalid= "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err))
        $sql = "select username,password from users where username='" . $username . "' and password ='" . $password . "'";

    $usernameDB = $passwordDB = "";
    if (mysqli_stmt_execute($sql)) {
        mysqli_stmt_bind_result($sql, $usernameDB, $passwordDB);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        body {
            color: #fff;
            background: linear-gradient(90deg, #020024 0%, #0068ff 0%, #00d4ff 90%);
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
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <h1>Welcome to our website</h1>
                </div>
            </div>
            <div class="row ">
                <div class="col-4"></div>
                <div class="col-4 login-form">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <h2 class="text-center">Login</h2>
                        <div class="form-group has-error">
                            <input type="text" class="form-control" name="username" placeholder="Username" required="required">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password" required="required">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Sign in" />
                        </div>
                        <?php if($invalid != "") echo '<div class="alert alert-danger p-1 mt-1"><i class="fa fa-fw fa-exclamation-triangle"></i>'.$invalid.'</div>';?>
                        <p><a href="#">Lost your Password?</a></p>
                    </form>
                    <p class="text-center small">Don't have an account? <a href="#">Sign up here!</a></p>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
    </div>