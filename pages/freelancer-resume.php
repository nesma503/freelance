<?php
require_once "../models/user.php";
require_once "../models/freelancer.php";
require_once "../models/degree.php";
require_once "../models/skill.php";
require_once "../helpers/helpers.php";
session_start();

if (!isset($_SESSION['freelancer']))
  header('Location: ./login.php');
else
  $freelancer = $_SESSION['freelancer'];

$degrees = Degree::GetAll();
$skills = Skill::GetAll();

$freelancerId = $freelancer->Id;
$userId = $freelancer->UserId;
$degree = $freelancer->DegreeId;
$major = $freelancer->Major;
$university = $freelancer->UniversityName;
$lastWork = $freelancer->LastWork;
$experience = $freelancer->ExperienceYears;
$cv = $freelancer->CV;
$idPicture = $freelancer->IdPicture;
$freelancerSkills = $freelancer->Skills;
//to set selectpicker values
$freelancerSkillIds = [];

// convert array of skills to array of skill Id
if ($freelancerSkills != null && count($freelancerSkills) > 0) {
  for ($i = 0; $i < count($freelancerSkills); $i++) {
    $freelancerSkillIds[$i] = $freelancerSkills[$i]->Id;
  }
}

$msg = $error = $document_err = $image_err = $documentUrl = $imageUrl  = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_FILES) && isset($_FILES['document'])) {
    // get details of the uploaded file
    if ($_FILES['document']['error'] != 0) { //error = 0 : No error.
      if ($_FILES['document']['error'] != 4) // error = 4 : No file was uploaded.
        $error = 'There is some error in file upload, error: ' . $_FILES['document']['error'];
    } else {
      $document = new stdClass();
      $document->FileTmpPath = $_FILES['document']['tmp_name'];
      $document->FileName = $_FILES['document']['name'];
      $document->FileSize = $_FILES['document']['size'];
      $document->FileType = $_FILES['document']['type'];
      $result = uploadFile($document, "document");
      if ($result[0] == 1) // success
        $documentUrl = $result[1]; // filename
      else
        $document_err = $result[1]; // error msg
    }
  }

  if (isset($_FILES) && isset($_FILES['image'])) {
    if ($_FILES['image']['error'] != 0) {
      if ($_FILES['image']['error'] != 4) // error = 4 : No file was uploaded.
        $error = 'There is some error in file upload, error: ' . $_FILES['image']['error'];
    } else {
      $image = new stdClass();
      $image->FileTmpPath = $_FILES['image']['tmp_name'];
      $image->FileName = $_FILES['image']['name'];
      $image->FileSize = $_FILES['image']['size'];
      $image->FileType = $_FILES['image']['type'];
      $result = uploadFile($image, "image");
      if ($result[0] == 1) // success
        $imageUrl = $result[1];
      else
        $image_err = $result[1];
    }
  }
  // fill variables
  $degree = $_POST["degree"];  // degree Id
  if ($degree == "")
    $degree = null;
  $major = trim($_POST["major"]);
  $university = trim($_POST["university"]);
  $lastWork = trim($_POST["lastWork"]);
  $experience = $_POST["experience"];

  // fill freelancer object
  $freelancer = new Freelancer();
  $freelancer->Id = $freelancerId;
  $freelancer->UserId = $userId;
  $freelancer->DegreeId = $degree;
  $freelancer->Major = $major;
  $freelancer->UniversityName = $university;
  $freelancer->LastWork = $lastWork;
  $freelancer->ExperienceYears = $experience;

  // check if document uploaded
  if (!empty($documentUrl))
    $freelancer->CV = $documentUrl;
  else
    $freelancer->CV = $cv;
  // check if image uploaded
  if (!empty($imageUrl))
    $freelancer->IdPicture = $imageUrl;
  else
    $freelancer->IdPicture =  $idPicture;

  // check skills changes
  if ($_POST["skillIds"] != "") {
    $skillIds = explode(",", $_POST["skillIds"]);
    if ($skillIds == $freelancerSkillIds)
      $skillIds = [];
  } else
    $skillIds = [];

  // save
  $success = $freelancer->Save($skillIds);
  if ($success) {
    $freelancer = Freelancer::LoadByUserId($userId);
    if ($freelancer != null) {
      $_SESSION["freelancer"] = $freelancer;

      $cv = $freelancer->CV;
      $idPicture = $freelancer->IdPicture;

      $freelancerSkills = $freelancer->Skills;
      $freelancerSkillIds = [];
      // convert array of skills to array of skill Id
      if ($freelancerSkills != null && count($freelancerSkills) > 0) {
        for ($i = 0; $i < count($freelancerSkills); $i++) {
          $freelancerSkillIds[$i] = $freelancerSkills[$i]->Id;
        }
      }
      $msg = "Information saved!";
    } else
      $error = "An error occured!";
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
  <title>Freelancer Resume</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/menu.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/bootstrap-select.css" />

  <script src="../js/jquery-3.6.0.js"></script>
  <script src="../js/bootstrap.bundle.min.js"></script>
  <script src="../js/bootstrap-select.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#selectpicker').selectpicker();

      // get the selected skills
      $('#selectpicker').change(function() {
        $('#skillIds').val($('#selectpicker').val());
      });
      // set the selected skills
      $('#selectpicker').selectpicker('val', <?php echo json_encode($freelancerSkillIds); ?>);// [1,2,...]
    });
  </script>
</head>

<body>
  <div class="container-fluid">
    <div class="row fill-height">
      <div class="col-3 z-index-2">
        <!-- menu -->
        <?php menu($freelancer->UserTypeId, "resume", $freelancer->Username); ?>
      </div>
      <!-- page -->
      <div class="col-9 wrapper">
        <div class="container-fluid">
          <div class="title">
            <h2>Edit Resume</h2>
          </div>
          <form class="form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
            <?php if ($error != "") echo '<div class="alert alert-danger p-1 mt-1">' . $error . '</div>'; ?>
            <?php if ($msg != "") echo '<div class="alert alert-success p-1 mt-1">' . $msg . '</div>'; ?>
            <div class="form-group">
              <div class="row">
                <div class="col d-flex align-content-between flex-wrap">
                  <label>CV</label>
                  <?php if ($cv != null && $cv != "") { ?>
                    <div class="d-flex justify-content-center">
                      <a href="<?php echo 'download.php?path="../uploaded-documents/' . $cv . '"&username="' . $freelancer->Username . '"&type="document"'; ?>" download>
                        <img src="../img/cv.png">
                        Download
                      </a>
                    </div>
                  <?php } ?>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" id="inputGroupDocument" name="document" class="custom-file-input" accept=".doc, .docx, .pdf">
                      <label class="custom-file-label" for="inputGroupDocument">Choose file</label>
                    </div>
                  </div>
                  <?php if ($document_err != "") echo '<span class="error">' . $document_err . '</span>'; ?>
                </div>
                <div class="col d-flex align-content-between flex-wrap">
                  <label>Identity Picture</label>
                  <?php if ($idPicture != null && $idPicture != "") { ?>
                    <div class="d-flex justify-content-center">
                      <a href="<?php echo 'download.php?path="../uploaded-images/' . $idPicture . '"&username="' . $freelancer->Username . '"&type="image"'; ?>" download>
                        <img src="../img/identity.png">
                        Download
                      </a>
                    </div>
                  <?php } ?>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" id="inputGroupImage" name="image" class="custom-file-input" accept="image/.jpg, .JPG, .png">
                      <label class="custom-file-label" for="inputGroupImage">Choose file</label>
                    </div>
                  </div>
                  <?php if ($image_err != "") echo '<span class="error">' . $image_err . '</span>'; ?>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label for="major">Major</label>
                  <input type="text" id="major" name="major" class="form-control" value="<?php echo $major ?>">
                </div>
                <div class="col">
                  <label for="degree">Degree</label>
                  <select id="degree" name="degree" class="form-control">
                    <option value="">Please Select</option>
                    <?php
                    for ($i = 0; $i < count($degrees); $i++) {
                      echo '<option value="' . $degrees[$i]->Id . '"' . ($degree == $degrees[$i]->Id ? "selected" : "") . ' >' . $degrees[$i]->Name . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="university">University</label>
              <input type="text" id="university" name="university" class="form-control" value="<?php echo $university ?>">
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label for="lastWork">Last work</label>
                  <input type="text" id="lastWork" name="lastWork" class="form-control" value="<?php echo $lastWork ?>">
                </div>

                <div class="col">
                  <label for="experience">Experience Years</label>
                  <input type="number" id="experience" name="experience" class="form-control" value="<?php echo $experience ?>">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="skills">Skills</label>
              <select id="selectpicker" class="form-control" multiple title="Choose your skills">
                <?php
                for ($i = 0; $i < count($skills); $i++) {
                  echo '<option value="' . $skills[$i]->Id . '" data-tokens="' . $skills[$i]->Id . '">' . $skills[$i]->Name . '</option>';
                }
                ?>
              </select>
            </div>
            <input type="hidden" id="skillIds" name="skillIds" />

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