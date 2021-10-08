<?php

function menu($userType, $page, $username)
{
?>
  <div class="aside">
    <div class="row">
      <div class="col-5">
        <img class="img" src="../img/<?php if($userType ==1) echo "freelancer.png"; else echo "recruiter.png";?>" />
      </div>
      <div class="col-7 align-self-center ">
        <div class="type">
          <?php
          if ($userType == 1) echo "Freelancer";
          else echo "Recruiter"; ?>
        </div>
        <div> <?php echo $username; ?> </div>
      </div>
    </div>
    <div>
      <ul class="menu">
        <li><a href="./user-profile.php"> <span class="flaticon-businessman <?php if ($page == "profile") echo "active"; ?>" /> Profile </a></li>
        <?php
        // freelancer
        if ($userType == 1) {
        ?>
          <li><a href="./freelancer-resume.php"> <span class="flaticon-resume <?php if ($page == "resume") echo "active"; ?>" /> Resume </a></li>
          <li><a href="./freelancer-applied.php"> <span class="flaticon-book <?php if ($page == "applied") echo "active"; ?>" /> My Applied </a></li>
          <li><a href="./freelancer-jobs.php"> <span class="flaticon-briefcase-1 <?php if ($page == "jobs") echo "active"; ?>" /> Jobs </a></li>
        <?php
        } else {
        ?>
          <li><a href="./recruiter-jobs.php"> <span class="flaticon-briefcase-1 <?php if ($page == "jobs") echo "active"; ?>" /> My Jobs </a></li>
          <li><a href="./recruiter-add-job.php"> <span class="flaticon-pen <?php if ($page == "submitJob") echo "active"; ?>" /> Submit Job </a></li>
          <li><a href="./recruiter-applied.php"> <span class="flaticon-megaphone <?php if ($page == "applicants") echo "active"; ?>" /> Applicants Jobs </a></li>
        <?php
        }
        ?>
        <li><a href="./change-password.php"> <span class="flaticon-password <?php if ($page == "password") echo "active"; ?>" /> Change Password </a></li>
        <li><a href="./delete-user.php"> <span class="flaticon-trash <?php if ($page == "delete") echo "active"; ?>" /> Delete Profile </a></li>
        <li><a href="./logout.php"> <span class="flaticon-off" /> Logout </a></li>
      </ul>
    </div>
  </div>
<?php
}


function uploadFile($file, $type)
{
  // get details of the uploaded file
  $fileTmpPath = $file->FileTmpPath;
  $fileName = $file->FileName;
  $fileSize = $file->FileSize;
  $fileType = $file->FileType;
  $fileNameCmps = explode(".", $fileName);
  $fileExtension = strtolower(end($fileNameCmps));

  if ($type == "image") {
    // check if file has one of the following extensions
    $allowedfileExtensions = array('jpg', 'png');
    // directory in which the uploaded file will be moved
    $uploadFileDir = '../uploaded-images/';
  } else if ($type == "document") {
    $allowedfileExtensions = array('doc', 'docx', 'pdf');
    $uploadFileDir = '../uploaded-documents/';
  }

  $result = []; //result[0]: true or false, result[1]: filename in case success otherwise error msg.
  $result[0] = "0"; //false
  $message = "";
  if (in_array($fileExtension, $allowedfileExtensions)) {
    // sanitize file-name
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    $dest_path = $uploadFileDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
      $message = $newFileName;
      $result[0] = "1"; //true
    } else {
      $message = 'Please make sure the upload directory is writable by web server.';
    }
  } else {
    $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
  }
  $result[1] = $message;
  return $result;
}
