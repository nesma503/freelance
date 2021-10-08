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

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $success = User::Delete($user->UserId);
    if ($success) {
        session_destroy(); //destroy all data registered to a session
        header('Location: ./login.php');
    } else
        $error = "An error occured!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Profile</title>
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
                <?php menu($user->UserTypeId, "delete", $user->Username); ?>
            </div>
            <!-- page -->
            <div class="col-9 wrapper">
                <div class="container-fluid">
                    <div class="title">
                        <h2>Delete Profile</h2>
                    </div>
                    <form class="form" action="<?php echo ($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <?php if ($error != "") echo '<div class="alert alert-danger p-1 mt-1">' . $error . '</div>'; ?>
                        <div class="form-group">
                            <p> Are you sure do you want to delete your profile?
                        </div>
                        <div class=" form-group">
                            <input type="submit" class="btn btn-danger btn-lg btn-block" value="Confirm">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>