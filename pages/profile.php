<?php
session_start();
require_once "../db/dbWrapper.php";

$db = new dbWrapper();

$username = $_SESSION["username"];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];
  $username = $_POST["username"];
  $email = $_POST["email"];
  $DOB = $_POST["DOB"];
  $userId = $_SESSION['userId'];

  if (empty($firstName)) {
    $firstName_err = "Please enter first name.";
  }

  if (empty($lastName)) {
    $lastName_err = "Please enter last name.";
  }

  if (empty($username)) {
    $username_err = "Please enter username.";
  }

  $sql_u = "SELECT * FROM users WHERE username= ? where userId <> ?";
  $exists = $db::queryOne($sql_u, [$username, $userId]);
  if ($exists > 0) {
    $username_err = "Username already taken";
  }

  if (empty($email)) {
    $email_err = "Please enter email.";
  }

  if (empty($DOB)) {
    $username_err = "Please enter date of birth.";
  }


  if (empty($firstName_err) && empty($lastName_err) && empty($username_err) && empty($email_err) && empty($DOB)) {
    $sql = "update users set FirstName=?, LastName=?, username=?, email=?, DOB=?  where id=?";
    $update = $db::query($sql, [$firstName, $lastName, $username, $email, $DOB, $userId]);

    if ($update == 1)
      {$msg = "Information saved!";
        $sql = "select * from users where id=?";
        $user = $db::queryOne($sql);
        $_SESSION["user"] = $user;
      }
    else
      $msg = "An error occured!";
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

  <style>
    body {
      padding: 30px;
    }
  </style>

</head>


<body>
  <div class="profile-form-wrapper">
    <h1>Edit Profile</h1>
    <div class="inner-list form-group">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div>
          <h3>My Profile</h3>
        </div>

        <div class="form-group">
          <label for="firstName">First Name</label>
          <input type="text" class="form-control" name="firstName" placeholder="Enter first name" value="<?php echo $username; ?>">
        </div>

        <div class="form-group">
          <label for="lastName">Last Name</label>
          <input type="text" class="form-control" name="lastName" placeholder="Enter first Name">
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" name="username" placeholder="Enter username">

        </div>

        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" class="form-control" name="email" placeholder="Enter email">

        </div>

        <div class="form-group">
          <label for="exampleInputEmail1">Date of birth</label>
          <input type="email" class="form-control" name="DOB" placeholder="Enter date of birth">
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary btn-lg btn-block" value="Update">
        </div>








      </form>
    </div>
  </div>
</body>

</html>