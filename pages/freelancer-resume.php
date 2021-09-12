<?php
session_start();
require_once "../db/dbWrapper.php";

$db = new dbWrapper();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $degree = $_POST["degree"];
  $major = $_POST["major"];
  $university = $_POST["university"];
  $lastWork = $_POST["lastWork"];
  $experience = $_POST["experience"];
  $userId = $_SESSION["userId"];

  $sql = "UPDATE freelancer SET  DegreeId=?, Major=?, UniversityName=?, LastWork=?, ExperienceYears=? WHERE UserId=?";
  $update = $db::query($sql, [$degree, $major, $university, $lastWork, $experience, $userId]);

  if($update == 1) {
    $msg = "Information saved!";
    $sql = "SELECT * FROM freelancers WHERE userId = ?";
    $freelancer = $db::queryOne($sql, [$userId]);
    $_SESSION["freelancer"] = $freelancer;
  }
  else
  $msg = "An error occured!";



}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Freelancer Resume</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">

  <style>
    body {
      padding: 30px;
    }
  </style>
  
</head>

<body>
  <div class="resume-form-wrapper">
    <h1>Edit Resume</h1>
    <div class="inner-list form-group">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div>
          <h3>My Profile</h3>
        </div>

        <div class="form-group">
          <label for="cv">Example file input</label>
          <input type="file" class="form-control-file">
        </div>

        <div class="form-group">
          <label for="degree">Degree</label>
          <select class="selectpicker" title="Choose degree">
            <option></option>
            <option></option>
            <option></option>
          </select>
        </div>

        <div class="form-group">
          <label for="major">Major</label>
          <input type="text" class="form-control" name="major" placeholder="Enter major">
        </div>

        <div class="form-group">
          <label for="university">University</label>
          <input type="text" class="form-control" name="university" placeholder="Enter university name">
        </div>

        <div class="form-group">
          <label for="lastWork">Last work</label>
          <input type="text" class="form-control" name="lastWork" placeholder="Enter your last work">

        </div>

        <div class="form-group">
          <label for="experience">Experience time</label>
          <input type="text" class="form-control" name="experience" placeholder="Enter experience time">
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary btn-lg btn-block" value="Update">
        </div>








      </form>
    </div>
  </div>
</body>

</html>