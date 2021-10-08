<?php
require_once "../models/user.php";
require_once "../models/recruiter.php";
session_start();
require_once '../helpers/helpers.php';
if (!isset($_SESSION['recruiter']))
    header('Location: ./login.php');
else
    $recruiter = $_SESSION['recruiter'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Recruiter Main</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row fill-height">
            <div class="col-3 z-index-2">
                <!-- menu -->
                <?php menu($recruiter->UserTypeId, "", $recruiter->Username); ?>
            </div>

            <!-- page -->
            <div class="col-9 wrapper">

            </div>
        </div>
    </div>
</body>