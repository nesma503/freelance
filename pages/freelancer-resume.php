<?php
session_start();
require_once "../db/dbWrapper.php";
require_once "../helpers/helpers.php";
require_once "../models/degree.php";

$db = new dbWrapper();

$degrees = Degree::GetAll();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $degree = $_POST["degree"];
  $major = $_POST["major"];
  $university = $_POST["university"];
  $lastWork = $_POST["lastWork"];
  $experience = $_POST["experience"];
  $skills = $_POST["skills"];
  $userId = $_SESSION["userId"];

  $sql = "UPDATE freelancer SET  DegreeId=?, Major=?, UniversityName=?, LastWork=?, ExperienceYears=? WHERE UserId=?";
  $update = $db::query($sql, [$degree, $major, $university, $lastWork, $experience, $userId]);

  if ($update == 1) {
    $msg = "Information saved!";
    $sql = "SELECT * FROM freelancers WHERE userId = ?";
    $freelancer = $db::queryOne($sql, [$userId]);
    $_SESSION["freelancer"] = $freelancer;
  } else
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
    <?php menu(1, "resume", "siham"); ?>
    <div class="col-9">
      <div class="resume-form-wrapper">
        <h1 class="d-flex justify-content-center">Edit Resume</h1>
        <div class="inner-list form-group">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div>
              <h3 class="d-flex justify-content-center">My Resume</h3>
            </div>

            <div class="form-group">
              <label for="cv">Example file input</label>
              <input type="file" class="form-control-file">
            </div>

            <div class="form-group">
              <label for="degree">Degree</label>
              <select name="degree" class="form-control" title="Choose degree">
                <?php
                for ($i = 0; $i < count($degrees); $i++) {
                  echo '<option value="' . $degrees[$i]->Id . '">' . $degrees[$i]->Name . '</option>';
                }
                ?>
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
              <label for="skills">Skills</label>
              <select name="skills" multiple size=1 class="form-control" title="Choose skills">
                <?php
                for ($i = 0; $i < count($skills); $i++) {
                  echo '<option value="' . $skills[$i]->Id . '">' . $skills[$i]->Name . '</option>';
                }
                ?>
              </select>
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