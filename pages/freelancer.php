<?php
session_start();
require_once "../db/dbWrapper.php";
$username = $password = $invalid = "";
$db = new dbWrapper();

/* $sql = "SELECT * FROM 'degrees'";
$all_degrees = queryAll($sql, )

$sql = "SELECT * FROM 'skills'";
$all_skills = queryAll($sql, ) */





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




    if (empty($username_err) && empty($firstName_err) && empty($lastName_err) && empty($password_err) && empty($email_err) && empty($DOB_err)) {
        $sql = "select id from users where username= ?";
        $result = $db::queryOne($sql, [$username]);
        if ($result != null)
            $exists = "username already exists.";
        else {
            $sql = "insert into users(FirstName, LastName, Username, Email, Password, DOB) values(?,?,?,?,?,?)";
            $user = $db::query($sql, [$firstName, $lastName, $username, $email, $password, $DOB]);
        }
    }

    /* if($user_type = 1) {
            $sql = "insert into freelancers(UserId, DegreeId, Major, University, ExperienceYears, LastWork, SkillId, IdPicture) values(?, ?,?,?,?,?,?,?)";
            $freelancer = query($sql, [$id, $degreeId, $major, $university, $experienceYears, $lastWork, $skillId, $IdPicture]);

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
                                    <button type="button" class="btn btn-primary btn-lg form-control" value=1>Freelancer</button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary btn-lg form-control" value=2>Recruiter</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <table>
                                <tr>
                                    <td><input type="text" class="form-control" name="firstName" placeholder="First Name*" required="required"></td>
                                    <td><input type="text" class="form-control" name="lastName" placeholder="Last Name*" required="required"></td>
                                </tr>
                            </table>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" name="username" placeholder="Username*" required="required">
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email*" required="required">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password*" required="required">
                        </div>


                        <div class="form-group">
                            <input type="text" class="form-control" name="DOB" placeholder="Date of birth" onfocus="(this.type='date')">
                        </div>




                        <div>


                            <p class="h4"> Set up your profile:</p>


                            <div class="form-group">
                                <input type="text" class="form-control" name="major" placeholder="Major" required="required">
                            </div>

                            <div class="form-group">
                                Degree:
                                <select>
                                    <option value="1">BS</option>
                                    <option value="2">MS</option>
                                    <option value="3">PhD</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" name="university" placeholder="University Name" required="required">
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" name="experienceYears" placeholder="Years of experience">
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" name="lastWork" placeholder="Last Work">
                            </div>


                            <div class="form-group">
                                Skills:
                                <select class="form-select" aria-label="Default select example">
                                    <option value="">one</option>
                                    <option value="">two</option>
                                    <option value="">three</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="url" class="form-control" placeholder="Id Picture" name="idPicture" required="required">
                            </div>

            




                        </div>




                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Register now" />
                        </div>



                    </form>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
    </div>
</body>

</html>