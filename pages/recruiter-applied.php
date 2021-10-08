<?php
require_once "../models/user.php";
require_once "../models/recruiter.php";
require_once "../models/job.php";
require_once "../models/category.php";
require_once "../models/skill.php";
require_once "../helpers/helpers.php";
session_start();

if (!isset($_SESSION['recruiter']))
    header('Location: ./login.php');
else
    $recruiter = Recruiter::Create($_SESSION['recruiter']);

$categories = Category::GetAll();

$category = "";
$sort = "CreationDate";
$page = $totalPages = 1;
$previousPage = 1;
$nextPage = 2;

if (isset($_GET['category']))
    $category = $_GET['category'];

if (isset($_GET['sort']))
    $sort = $_GET['sort'];

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $nextPage = $page + 1;
    $previousPage = $page - 1;
}

$jobs = Job::GetApplicants($recruiter->Id, $category, $sort, $page);
if (count($jobs) > 0) {
    $totalPages =  ceil($jobs[0]->TotalRows / 2);
    if ($page > $totalPages)
        $page = $totalPages;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants Jobs</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row fill-height">
            <div class="col-3 z-index-2">
                <!-- menu -->
                <?php menu($recruiter->UserTypeId, "applicants", $recruiter->Username); ?>
            </div>
            <!-- page -->
            <div class="col-9 wrapper">
                <div class="container-fluid">
                    <div class="title">
                        <h2>Applicants Jobs</h2>
                    </div>
                    <form class="form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
                        <div class="search">
                            <div class="row">
                                <div class="col-5">
                                    <div class="flex-wrap">
                                        <p>Filter by Category:</p>
                                        <select id="category" name="category" class="form-control">
                                            <option value="0">Any</option>
                                            <?php
                                            for ($i = 0; $i < count($categories); $i++) {
                                                echo '<option value="' . $categories[$i]->Id . '"' . ($category == $categories[$i]->Id ? "selected" : "") . ' >' . $categories[$i]->Name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="flex-wrap">
                                        <p>Sort by:</p>
                                        <select id="sort" name="sort" class="form-control">
                                            <option value="CreationDate" <?php if ($sort == "CreationDate") echo "selected"; ?>>Lastest</option>
                                            <option value="Salary" <?php if ($sort == "Salary") echo "selected"; ?>>Salary</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2 d-flex">
                                    <input type="submit" class="btn btn-primary btn-md btn-block mt-auto" value="Filter">
                                </div>
                            </div>
                        </div>
                        <?php
                        foreach ($jobs as $job) {
                        ?>
                            <div class="job">
                                <div class="job-header">
                                    <div class="row">
                                        <div class="col">
                                            <div class="job-title">
                                                <a href="./job-details.php?jobId=<?php echo $job->Id; ?>"> <?php echo $job->Title; ?></a>
                                            </div>
                                            <span><?php echo $job->Category->Name; ?></span>
                                        </div>
                                        <div class="col d-flex align-items-end flex-column">
                                            <div class="date"><?php echo date_format(date_create($job->CreationDate), "Y/m/d"); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($job->Applicants != null && count($job->Applicants) > 0) { ?>
                                    <div class="container applicants-container ">
                                        <p>Applicants:</p>
                                        <div class="row header">
                                            <div class="col">Name</div>
                                            <div class="col">Major</div>
                                            <div class="col">Degree</div>
                                            <div class="col">Exp Years</div>
                                            <div class="col">CV</div>
                                            <div class="col">Identity</div>
                                        </div>
                                        <?php foreach ($job->Applicants as $applicant) { ?>

                                            <div class="row applicant">
                                                <div class="col"><?php echo $applicant->FirstName . " " . $applicant->LastName ?></div>
                                                <div class="col"><?php echo $applicant->Major ?></div>
                                                <div class="col"><?php echo $applicant->Degree->Name ?></div>
                                                <div class="col"><?php echo $applicant->ExperienceYears ?></div>
                                                <div class="col">
                                                    <?php if($applicant->CV != null && $applicant->CV !=""){ ?>
                                                    <a href="<?php echo 'download.php?path=../uploaded-documents/' . $applicant->CV . '&username=' . $applicant->Username . '&type="document"'; ?>">
                                                        <img src="../img/download.png" />
                                                    </a>
                                                    <?php } ?>
                                                </div>
                                                <div class="col">
                                                <?php if($applicant->IdPicture != null && $applicant->IdPicture !=""){ ?>
                                                    <a href="<?php echo 'download.php?path=../uploaded-images/' . $applicant->IdPicture . '&username=' . $applicant->Username . '&type="image"'; ?>">
                                                        <img src="../img/download.png" />
                                                    </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                <?php
                                } else
                                    echo '<div class="container applicants-container "><p>No applicants yet!</p></div>'
                                ?>
                                <div class="job-footer">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="salary">Salary: <?php echo $job->Salary; ?> $</div>
                                        </div>
                                        <div class="col-2">
                                            <a href="./job-details.php?jobId=<?php echo $job->Id; ?>" class="btn btn-success btn-md btn-block">View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="row">
                            <div class="col-8">
                                <div class="mr-auto">Page <?php echo $page ?> of <?php echo $totalPages ?></div>
                            </div>
                            <div class="col-2">
                                <a href="./recruiter-applied.php?page=<?php echo $previousPage ?>&category=<?php echo $category ?>&sort=<?php echo $sort ?>" class="btn btn-primary btn-md btn-block <?php if ($previousPage < 1) echo 'disabled'; ?>">Previous</a>
                            </div>
                            <div class="col-2">
                                <a href="./recruiter-applied.php?page=<?php echo $nextPage ?>&category=<?php echo $category ?>&sort=<?php echo $sort ?>" class="btn btn-primary btn-md btn-block <?php if ($nextPage > $totalPages) echo 'disabled'; ?>">next</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>