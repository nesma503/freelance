<?php
require_once "../models/user.php";
require_once "../models/freelancer.php";
require_once "../models/job.php";
require_once "../models/category.php";
require_once "../models/skill.php";
require_once "../helpers/helpers.php";
session_start();

if (!isset($_SESSION['freelancer']))
  header('Location: ./login.php');
$freelancer = Freelancer::Create($_SESSION['freelancer']);

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
// get applied jobs
$jobs = Job::GetApplied($freelancer->Id, $category, $sort, $page);

if (count($jobs) > 0) {
  $totalPages =  ceil($jobs[0]->TotalRows / 1);
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
  <title>My Applied</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/menu.css">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <div class="container-fluid">
    <div class="row fill-height">
      <div class="col-3 z-index-2">
        <!-- menu -->
        <?php menu($freelancer->UserTypeId, "applied", $freelancer->Username); ?>
      </div>
      <!-- page -->
      <div class="col-9 wrapper">
        <div class="container-fluid">
          <div class="title">
            <h2>My Applied</h2>
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
                <div class="skills-container">
                  <?php if ($job->Skills != null && count($job->Skills) > 0) {
                    echo '<p>Required Skills:</p>';
                    foreach ($job->Skills as $skill) {
                  ?>
                      <div class="skill"><?php echo $skill->Name; ?></div>
                  <?php
                    }
                  } else
                    echo 'No skills required.';
                  ?>
                </div>
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
                <a href="./freelancer-applied.php?page=<?php echo $previousPage ?>&category=<?php echo $category ?>&sort=<?php echo $sort ?>" class="btn btn-primary btn-md btn-block <?php if ($previousPage < 1) echo 'disabled'; ?>">Previous</a>
              </div>
              <div class="col-2">
                <a href="./freelancer-applied.php?page=<?php echo $nextPage ?>&category=<?php echo $category ?>&sort=<?php echo $sort ?>" class="btn btn-primary btn-md btn-block <?php if ($nextPage > $totalPages) echo 'disabled'; ?>">next</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>