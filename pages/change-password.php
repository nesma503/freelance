<?php
require_once "../models/user.php";
require_once "../models/freelancer.php";
require_once "../models/recruiter.php";
require_once "../helpers/helpers.php";
session_start();

if (!isset($_SESSION['recruiter']) && !isset($_SESSION['freelancer']))
    header('Location: ./login.php');

if (isset($_SESSION['recruiter'])) {
    $user = $_SESSION['recruiter'];
} else {
    $user = $_SESSION['freelancer'];
}

$userId = $user->UserId;
$msg = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = trim($_POST["password"]);
    if (empty($password)) {
        $password_err = "Please enter a password!";
    }

    $confirmPassword = trim($_POST["confirmPassword"]);
    if (empty($confirmPassword)) {
        $confirmPassword_err = "Please re-enter the password!";
    }
    if ($password != $confirmPassword)
        $confirmPassword_err = "Please make sure your passwords match!";

    if (empty($password_err) && empty($confirmPassword_err)) {

        $tmpUser = new User();
        $tmpUser->Id = $userId;
        $tmpUser->Password =  password_hash($password, PASSWORD_DEFAULT);

        $success = $tmpUser->ChangePassword();
        if ($success) {
            $msg = "Password changed successfully!";
        } else
            $error = "An error occured!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/style.css">

    <script src="../js/jquery-3.6.0.js"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row fill-height">
            <!-- menu -->
            <div class="col-3 z-index-2">
                <?php menu($user->UserTypeId, "password", $user->Username); ?>
            </div>
            <!-- page -->
            <div class="col-9 wrapper">
                <div class="container-fluid">
                    <div class="title">
                        <h2>Change Password</h2>
                    </div>
                    <form class="form" action="<?php echo ($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <?php if ($error != "") echo '<div class="alert alert-danger p-1 mt-1">' . $error . '</div>'; ?>
                        <?php if ($msg != "") echo '<div class="alert alert-success p-1 mt-1">' . $msg . '</div>'; ?>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="password">New Password*</label>
                                    <input type="password" id="password" name="password" class="form-control <?php if ($password_err != "") echo 'is-invalid'; ?>" required>
                                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                </div>
                                <div class="col">
                                    <label for="confirmPassword">Confirm Password*</label>
                                    <input type="password" id="confirmPassword" name="confirmPassword" class="form-control <?php if ($confirmPassword_err != "") echo 'is-invalid'; ?>" required>
                                    <span class="invalid-feedback"><?php echo $confirmPassword_err; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group">
                            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>