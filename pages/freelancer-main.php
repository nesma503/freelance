<?php
session_start();

$user = $_SESSION['user'];
echo $user;
require_once '../helpers/helpers.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Freelancer Sign Up</title>
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
    <div class="">
        <div class="row">
            <!-- menu -->
            <?php menu(2,"profile", "mhd");?>
            <!-- page -->
            <div class="col-9" style="background:  rgba(25, 103, 210, 0.1);">
                hello
            </div>
        </div>

    </div>
    </div>
</body>