<?php
session_start();
require_once "../db/dbWrapper.php";
require_once "../helpers/helpers.php";

$db = new dbWrapper();

// $user = $_SESSION["user"];
// $firstName = $user->FirstName;
// $lastName = $user->LastName;
// $username = $user->Username;
// $email = $user->Email;
// $DOB = $user->DOB;
// $userId = $user->ID;


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
    
    $user = new User();
    $user->ID = $userId;
    $user->FirstName = $firstName;
    $user->LastName = $lastName;
    $user->Username = $username;
    $user->Email = $email;
    $user->DOB = $DOB;

    $user->UpdateUser();
    $sql = "update users set FirstName=?, LastName=?, username=?, email=?, DOB=?  where id=?";
    $update = $db::query($sql, [$firstName, $lastName, $username, $email, $DOB, $userId]);

    if ($update == 1) {
      $msg = "Information saved!";
      $sql = "select * from users where id=?";
      $user = $db::queryOne($sql);
      $_SESSION["user"] = $user;
    } else
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
  <link rel="stylesheet" href="../css/icons.css">


<style>
        .aside {
            border-right: 1px solid #ECEDF2;
            box-shadow: 0 6px 15px 0 rgb(64 79 104 / 5%);
        }

        img {
            width: 80px;
            margin: 20px;
        }

        .menu {
            list-style: none;
        }

        li {
            padding: 10px;
            text-decoration: none;
            margin-bottom: 2px;
            box-sizing: border-box;
            display: list-item;
        }

        li a {
            color: #77838F;
            font-size: 18px;
            font-weight: 400;
            line-height: 1.75;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0
        }

        .menu li {
            color: #696969;
            background-color: #fff;
            padding: 4px 15px;
            display: inline-block;
            width: 100%;
            margin-bottom: 2px
        }

        .menu li a span {
            padding: 10px;
        }

        .menu li a span:hover,
        .menu li a .active {
            border-radius: 8px;
            width: 100%;
            color: #1967D2;
            background-color: rgba(25, 103, 210, 0.1);
        }

        .menu li a span {
            display: inline-block;
            font-size: 18px;
            margin-right: 10px
        }
    </style>

</head>


<body>
  <div class="row">
    <?php menu(1,"profile", "siham") ?>
    <div class="col-9">
      <div class="profile-form-wrapper">
        <h1 class="d-flex justify-content-center">Edit Profile</h1>
        <div class="inner-list form-group">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div>
              <h3 class="d-flex justify-content-center">My Profile</h3>
            </div>

            <div class="form-group">
              <label for="firstName">First Name</label>
              <input type="text" class="form-control" name="firstName" placeholder="Enter first name" value="<?php echo $firstName; ?>">
            </div>

            <div class="form-group">
              <label for="lastName">Last Name</label>
              <input type="text" class="form-control" name="lastName" placeholder="Enter first Name" value="<?php echo $lastName; ?>>
            </div>

            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" name="username" placeholder="Enter username" value="<?php echo $username; ?>>

            </div>

            <div class="form-group">
              <label for="email">Email address</label>
              <input type="email" class="form-control" name="email" placeholder="Enter email" value="<?php echo $email; ?>>

            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">Date of birth</label>
              <input type="email" class="form-control" name="DOB" placeholder="Enter date of birth" value="<?php echo $DOB; ?>>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary btn-lg btn-block" value="Update">
            </div>








          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>