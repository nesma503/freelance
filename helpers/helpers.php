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
            <li><a href="./profile.php"> <span class="flaticon-businessman '.($page == "profile" ?  "active": "").'" /> Profile </a></li>';
            // freelancer
            if ($userType == 1) {
                echo '
            <li><a href="./freelancer-resume.php" > <span class="flaticon-resume ' . ($page == "resume" ? "active" : "") . '" /> Resume </a></li>
            <li><a href="#" > <span class="flaticon-book ' . ($page == "myApplied" ? "active" : "") . '"/> My Applied </a></li>
            <li><a href="#" > <span class="flaticon-briefcase-1 ' . ($page == "jobs" ? "active" : "") . '"/> Jobs </a></li>';
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
?>