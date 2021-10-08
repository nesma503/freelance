<?php
require_once "../models/user.php";
require_once "../models/freelancer.php";
require_once "../models/recruiter.php";
require_once "../models/job.php";
require_once "../models/job-freelancer.php";
require_once "../models/skill.php";
require_once "../helpers/helpers.php";
session_start();

if (!isset($_SESSION['recruiter']) && !isset($_SESSION['freelancer']))
    header('Location: ./login.php');

if (isset($_SESSION['recruiter'])) {
    $user = $_SESSION['recruiter'];
    $recruiter = Recruiter::Create($_SESSION['recruiter']);
} else {
    $user = $_SESSION['freelancer'];
    $freelancer = Freelancer::Create($_SESSION['freelancer']);
}
if (!isset($_GET['jobId'])) {
    if ($recruiter != null)
        header('Location: ./recruiter-jobs.php');
    else
        header('Location: ./freelancer-jobs.php');
} else
    $jobId = $_GET['jobId'];

$job = Job::Load($jobId);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['apply']))
        $freelancer->ApplyJob($jobId);
    else if (isset($_POST['cancel']))
        JobFreelancer::Delete($_POST['jobFreelancerId']);
    else if (isset($_POST['delete'])) {
        // only recruiter can delete his own job
        if ($job->RecruiterId == $recruiter->Id) {
            Job::Delete($jobId);
            header('Location: ./recruiter-jobs.php');
        }
    }
}

if ($user->UserTypeId == 1 && $freelancer != null)
    $jobFreelancer = JobFreelancer::Load($freelancer->Id, $jobId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row fill-height">
            <div class="col-3 z-index-2">
                <!-- menu -->
                <?php menu($user->UserTypeId, "jobs", $user->Username); ?>
            </div>
            <!-- page -->
            <div class="col-9 wrapper">
                <div class="container-fluid">
                    <div class="title">
                        <h2>Job Details</h2>
                    </div>
                    <form class="form" action="<?php echo $_SERVER["PHP_SELF"] . "?jobId=" . $jobId; ?>" method="POST">
                        <?php
                        if ($job != null) {
                        ?>
                            <div class="job">
                                <div class="job-header">
                                    <div class="row">
                                        <div class="col">
                                            <div class="job-title">
                                                <?php echo $job->Title; ?>
                                            </div>
                                        </div>
                                        <div class="col d-flex align-items-end flex-column">
                                            <div class="date"><?php echo $job->CreationDate; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="skills-container">
                                    <?php if ($job->Skills != null && count($job->Skills) > 0) {
                                        echo '<p>Required Skills:</p>';
                                        foreach ($job->Skills as $skill) {
                                    ?>
                                            <div class="skill"><?php echo $skill->Name; ?></div>
                                    <?php
                                        }
                                    } else
                                        echo '<p>No skills required.</p>';
                                    ?>
                                </div>
                                <div class="description-container">
                                    <p>Job Description:</p>
                                    <div class="description">
                                        <?php echo $job->Description; ?>
                                    </div>
                                </div>
                                <div class="image-container">
                                    <?php
                                    if ($job->ImageUrl != null && $job->ImageUrl != "") {
                                        echo '<img class="img-fluid rounded " src="../uploaded-images/' . $job->ImageUrl . '"/>';
                                    }
                                    ?>
                                </div>
                                <div class="job-footer">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="salary">Salary: <?php echo $job->Salary; ?> $</div>
                                        </div>
                                        <div class="col-2">
                                            <?php
                                            if ($user->UserTypeId == 2 && $recruiter != null && $job->RecruiterId == $recruiter->Id)
                                                echo '<input type="submit" name="delete" class="btn btn-danger btn-md btn-block" value="Delete">';
                                            else if ($user->UserTypeId == 1 && $freelancer != null) {
                                                if ($jobFreelancer == null)
                                                    echo '<input type="submit" name="apply" class="btn btn-success btn-md btn-block" value="Apply">';
                                                else {
                                                    echo '<input type="submit" name="cancel" class="btn btn-danger btn-md btn-block" value="Cancel">';
                                                    echo '<input type="hidden" name="jobFreelancerId" value="' . $jobFreelancer->Id . '" />';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } else echo '<p>Sorry,this job not found or deleted!';
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>