<?php
session_start();
require_once "../db/dbWrapper.php";
$username = $password = $invalid = "";
$db = new dbWrapper();

/*$sql = "SELECT * FROM degrees";
$all_degrees = $db::queryAll($sql);

$sql = "SELECT * FROM skills";
$all_skills = $db::queryAll($sql);*/

$exists = $userType = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST["firstName"]);
    if (empty($firstName)) {
        $firstName_err = "Please enter your first name.";
    }

    $lastName = trim($_POST["lastName"]);
    if (empty($lastName)) {
        $lastName_err = "Please enter your last name.";
    }

    $username = trim($_POST["username"]);
    // Check if username is empty
    if (empty($username)) {
        $username_err = "Please enter a username.";
    }

    $email = trim($_POST["email"]);
    if (empty($email)) {
        $email_err = "Please enter your first name.";
    }

    $password = trim($_POST["password"]);
    if (empty($password)) {
        $password_err = "Please enter password.";
    }

    $DOB = trim($_POST["DOB"]);
    if (empty($DOB)) {
        $DOB_err = "Please enter your date of birth.";
    }

    $major = $_POST["major"];
    $university = $_POST["university"];
    $experienceYears = $_POST["experienceYears"];
    $lastWork = $_POST["lastWork"];
    $userType = $_POST["userType"];
    $skillIds = $_POST["skills"];
    /*
    if (empty($username_err) && empty($firstName_err) && empty($lastName_err) && empty($password_err) && empty($email_err) && empty($DOB_err)) {
        $sql = "select id from users where username= ?";
        $result = $db::queryOne($sql, [$username]);
        if ($result != null)
            $exists = "username already exists.";
        else {
            $sql = "insert into users(FirstName, LastName, Username, Email, Password, DOB) values(?,?,?,?,?,?)";
            $userId = $db::query($sql, [$firstName, $lastName, $username, $email, $password, $DOB]);
        }
    }

    /* if($userType = 1) {
            $sql = "insert into freelancers(UserId, DegreeId, Major, University, ExperienceYears, LastWork, SkillId, IdPicture) values(?, ?,?,?,?,?,?,?)";
            $freelancerId = $db::query($sql, [$userId, $degreeId, $major, $university, $experienceYears, $lastWork, $skillId, $IdPicture]);
            
            $sql = "insert into freelancers-skills (freelancerId, skillId) values(? , ?)";

        }

        else {
            $sql = "insert into recruiters(UserId) values(?)";
            $recruiter = query($sql, [$id]);
        }

        $sql = "SELECT * FROM 'degrees'";
        $all_degrees = queryAll($sql, ); */
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Freelancer Sign Up</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">

    <script src="../js/jquery-3.6.0.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>

    <style>
        .hidden {
            display: none;
        }
    </style>
    <script>
        $(function() {
            $('.datepicker').datepicker();
            $('.selectpicker').selectpicker();

            /*var multipleCancelButton = new Choices('.selectpicker', {
                removeItemButton: true,
                maxItemCount: 5,
                searchResultLimit: 5,
                renderChoiceLimit: 5
            });*/
        });
    </script>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <h1>Create free account</h1>
                </div>
            </div>
            <div class="row ">
                <div class="col-4"></div>
                <div class="col-4">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <h2 class="text-center">Sign up as:</h2>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <button type="button" onclick="$('#registerForm').show(); $('#profile').show(); $('#userType').val(1)" class="btn btn-primary btn-lg form-control">Freelancer</button>
                                </div>
                                <div class="col">
                                    <button type="button" onclick="$('#registerForm').show(); $('#profile').hide(); $('#userType').val(2)" class="btn btn-primary btn-lg form-control" value=2>Recruiter</button>
                                </div>
                                <input type="hidden" id="userType" name="userType">
                            </div>
                        </div>
                        <?php if ($exists != "") echo '<div class="alert alert-danger p-1 mt-1"><i class="fa fa-fw fa-exclamation-triangle"></i>' . $exists . '</div>'; ?>

                        <div id="registerForm" <?php if ($userType == "") echo 'class="hidden"' ?>>
                            <div class="form-group">
                                <table>
                                    <tr>
                                        <td><input type="text" class="form-control" name="firstName" placeholder="First Name*" required="required"></td>
                                        <td><input type="text" class="form-control" name="lastName" placeholder="Last Name*" required="required"></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" name="username" placeholder="Username*" required="required" value="<?php echo $username ?>">
                            </div>

                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email*" required="required">
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password*" required="required">
                            </div>


                            <div class="form-group">
                                <input class="datepicker form-control" name="DOB" placeholder="Date of birth*" required="required" data-date-format="mm/dd/yyyy">
                            </div>


            



                                <div class="form-group">
                                    <input type="text" class="form-control" name="major" placeholder="Major">
                                </div>

                                <div class="form-group">
                                    Degree:
                                    <select class="form-control">
                                        <?php

                                        for ($i = 0; $i < count($all_degrees) - 1; $i++) {
                                            echo '<option value=' . $all_degrees[$i]['Id'] . '>' . $all_degrees[$i]['Name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name="university" placeholder="University Name">
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name="experienceYears" placeholder="Years of experience">
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name="lastWork" placeholder="Last Work">
                                </div>
                                <div class="form-group">
                                    Skills:
                                    <select class="form-control selectpicker" name="skills" multiple>
                                        <option value="">one</option>
                                        <option value="">two</option>
                                        <option value="">three</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary btn-lg btn-block" value="Register now" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
    </div>
</body>

</html>