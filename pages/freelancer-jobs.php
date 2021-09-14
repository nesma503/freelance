<?php
session_start();
require_once "../db/dbWrapper.php";
require_once "../helpers/helpers.php";
require_once "../models/jobs.php";

$jobs = Job::GetAll();

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
    <?php menu(1, "jobs", "siham"); ?>
    <div class="col-9">
      <div class="jobs-form-wrapper">
        <h1 class="d-flex justify-content-center">Find Jobs</h1>
        <div class="inner-list form-group">

          <div>
            <h3 class="d-flex justify-content-center">Jobs list</h3>
            <div>
              <div>
                <?php 
                  for($i = 0; $i < count($jobs); $i++) {
                    echo '<div><a href="#"> '. $jobs[$i]->Name .' </a>';
                    echo '<div><p>' . $jobs[$i]->Description . ' </p>';
                    
                  }
                ?>
              </div>
            </div>








          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>