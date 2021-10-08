<?php
require_once "../models/user.php";
require_once "../models/freelancer.php";
require_once "../models/recruiter.php";
require_once "../helpers/helpers.php";
session_start();

if (!isset($_SESSION['recruiter']) && !isset($_SESSION['freelancer']))
  header('Location: ./login.php');
else if (isset($_SESSION['recruiter'])) {
  $user = $_SESSION['recruiter'];
  $recruiter = $_SESSION['recruiter'];
} else {
  $user = $_SESSION['freelancer'];
  $freelancer = $_SESSION['freelancer'];
}

$userId = $user->UserId;
$firstName = $user->FirstName;
$lastName = $user->LastName;
$username = $user->Username;
$email = $user->Email;
$DOB = $user->DOB;
$userType = $user->UserTypeId;
$msg = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $firstName = trim($_POST["firstName"]);
  if (empty($firstName)) {
    $firstName_err = "Please enter your first name.";
  }

  $lastName = trim($_POST["lastName"]);
  if (empty($lastName)) {
    $lastName_err = "Please enter your last name.";
  }

  $username = trim($_POST["username"]);
  if (empty($username)) {
    $username_err = "Please enter a username.";
  }

  // check if the username not exist
  $valid = User::ValidateUsername($username, $userId);
  if (!$valid) {
    $username_err = "Username already taken";
  }

  $email = trim($_POST["email"]);
  if (empty($email)) {
    $email_err = "Please enter your email.";
  }

  $DOB = trim($_POST["DOB"]);
  if (empty($DOB)) {
    $DOB_err = "Please enter your date of birth.";
  }


  if (empty($firstName_err) && empty($lastName_err) && empty($username_err) && empty($email_err) && empty($DOB_err)) {

    $user = new User();
    $user->Id = $userId;
    $user->FirstName = $firstName;
    $user->LastName = $lastName;
    $user->Username = $username;
    $user->Email = $email;
    $user->DOB = $DOB;

    $success = $user->Save();
    if ($success) {

      if ($userType == 1) {
        $freelancer = Freelancer::LoadByUserId($userId);
        if ($freelancer != null) {
          $_SESSION["freelancer"] = $freelancer;
          $msg = "Information saved!";
        } else
          $error = "An error occured!";
      } else {
        $recruiter = Recruiter::LoadByUserId($userId);
        if ($recruiter != null) {
          $_SESSION["recruiter"] = $recruiter;
          $msg = "Information saved!";
        } else
          $error = "An error occured!";
      }
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
  <title>User Profile</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/menu.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/bootstrap-datepicker.min.css">

  <script src="../js/jquery-3.6.0.js"></script>
  <script src="../js/bootstrap-datepicker.min.js"></script>

  <script>
    $(function() {
      $('.datepicker').datepicker();
    });
  </script>
</head>

<body>
  <div class="container-fluid">
    <div class="row fill-height">
      <!-- menu -->
      <div class="col-3 z-index-2">
        <?php menu($user->UserTypeId, "profile", $user->Username); ?>
      </div>
      <!-- page -->
      <div class="col-9 wrapper">
        <div class="container-fluid">
          <div class="title">
            <h2>Edit Profile</h2>
          </div>
          <form class="form" action="<?php echo ($_SERVER["PHP_SELF"]); ?>" method="POST">
            <?php if ($error != "") echo '<div class="alert alert-danger p-1 mt-1">' . $error . '</div>'; ?>
            <?php if ($msg != "") echo '<div class="alert alert-success p-1 mt-1">' . $msg . '</div>'; ?>
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label for="firstName">First Name*</label>
                  <input type="text" id="firstName" name="firstName" class="form-control <?php if ($firstName_err != "") echo 'is-invalid'; ?>" required value="<?php echo $firstName; ?>">
                  <span class="invalid-feedback"><?php echo $firstName_err; ?></span>
                </div>
                <div class="col">
                  <label for="lastName">Last Name*</label>
                  <input type="text" id="lastName" name="lastName" class="form-control <?php if ($lastName_err != "") echo 'is-invalid'; ?>" required value="<?php echo $lastName; ?>">
                  <span class="invalid-feedback"><?php echo $lastName_err; ?></span>
                </div>
              </div>
            </div>

            <div class=" form-group">
              <label for="username">Username*</label>
              <input type="text" id="username" name="username" class="form-control <?php if ($username_err != "") echo 'is-invalid'; ?>" required value="<?php echo $username; ?>">
              <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>

            <div class=" form-group">
              <label for="email">Email Address*</label>
              <input type="email" id="email" name="email" class="form-control <?php if ($email_err != "") echo 'is-invalid'; ?>" required value="<?php echo $email; ?>">
              <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

            <div class=" form-group">
              <label for="DOB">Date of Birth*</label>
              <input type="text" id="DOB" name="DOB" class="datepicker form-control <?php if ($DOB_err != "") echo 'is-invalid'; ?>" required data-date-format="yyyy-mm-dd" value="<?php echo $DOB ?>">
              <span class="invalid-feedback"><?php echo $DOB_err; ?></span>
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