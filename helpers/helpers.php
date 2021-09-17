<?php 

function menu($userType, $page, $username)
{
    echo '
    <div class="col-3 aside">
    <div class="row">
        <div class="col-6" style="padding: 20px;">
            <img class="img" src="../img/avatar.png" />
        </div>
        <div class="col-6 align-middle align-items-center justify-content-center" style="padding: 20px;">
            <span> '. $username .' </span>
        </div>
    </div>
    <div>
        <ul class="menu">
            <li><a href="./user-profile.php"> <span class="flaticon-businessman '.($page == "profile" ?  "active": "").'" /> Profile </a></li>';
            // freelancer
            if ($userType == 1) {
                echo '
            <li><a href="./freelancer-resume.php" > <span class="flaticon-resume ' . ($page == "resume" ? "active" : "") . '" /> Resume </a></li>
            <li><a href="./freelancer-myApplied.php" > <span class="flaticon-book ' . ($page == "myApplied" ? "active" : "") . '"/> My Applied </a></li>
            <li><a href="./freelancer-jobs.php" > <span class="flaticon-briefcase-1 ' . ($page == "jobs" ? "active" : "") . '"/> Jobs </a></li>';
            } else {
                echo '
            <li><a href="#" > <span class="flaticon-briefcase-1 ' . ($page == "myJobs" ? "active" : "") . '"/> My Jobs </a></li>
            <li><a href="#" > <span class="flaticon-pen ' . ($page == "submitJob" ? "active" : "") . '"/> Submit Job </a></li>
            <li><a href="#" > <span class="flaticon-megaphone ' . ($page == "applicants" ? "active" : "") . '"/> Applicants Jobs </a></li>';
            }

            echo '
            <li><a href="#" > <span class="flaticon-password ' . ($page == "changePassword" ? "active" : "") . '"/> Change Password </a></li>
            <li><a href="#" > <span class="flaticon-trash ' . ($page == "deleteProfile" ? "active" : "") . '"/> Delete Profile </a></li>
            <li><a href="#" > <span class="flaticon-off ' . ($page == "logout" ? "active" : "") . '"/> Logout </a></li>
        </ul>
    </div>
</div>';
}


function uploadFile()
{

        // get details of the uploaded file
        $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
        $fileName = $_FILES['uploadedFile']['name'];
        $fileSize = $_FILES['uploadedFile']['size'];
        $fileType = $_FILES['uploadedFile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
     
        // sanitize file-name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
     
        // check if file has one of the following extensions
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');
     
        if (in_array($fileExtension, $allowedfileExtensions))
        {
          // directory in which the uploaded file will be moved
          $uploadFileDir = './uploaded_files/';
          $dest_path = $uploadFileDir . $newFileName; // ./Jobs/pic1.png;
     
          if(move_uploaded_file($fileTmpPath, $dest_path)) 
          {
            $message ='File is successfully uploaded.';
          }
          else
          {
            $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
          }
        }
        else
        {
          $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
      }
     /* else
      {
        $message = 'There is some error in the file upload. Please check the following error.<br>';
        $message .= 'Error:' . $_FILES['uploadedFile']['error'];
      }
    }
    $_SESSION['message'] = $message;*/

?>