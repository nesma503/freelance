<?php
require_once "../models/user.php";
require_once "../models/recruiter.php";
require_once "../models/job.php";
require_once "../models/category.php";
require_once "../models/skill.php";
require_once '../helpers/helpers.php';
session_start();

if (!isset($_SESSION['recruiter']))
    header('Location: ./login.php');

$recruiter = Recruiter::Create($_SESSION['recruiter']);

$categories = Category::GetAll();
$skills = Skill::GetAll();

$image_err = $title_err = $description_err = $category_err = $salary_err = $msg = $error = "";
$imageUrl = $title = $description = $category = $salary = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES) && isset($_FILES['image'])) {
        // get details of the uploaded file
        if ($_FILES['image']['error'] != 0) {
            if ($_FILES['image']['error'] != 4) // error = 4 : No file was uploaded.
                $error = 'There is some error in file upload, error: ' . $_FILES['image']['error'];
        } else {
            $image = new stdClass();
            $image->FileTmpPath = $_FILES['image']['tmp_name'];
            $image->FileName = $_FILES['image']['name'];
            $image->FileSize = $_FILES['image']['size'];
            $image->FileType = $_FILES['image']['type'];
            $result = uploadFile($image, "image");
            if ($result[0] == 1) // success
                $imageUrl = $result[1]; // new file name
            else
                $image_err = $result[1]; // error msg
        }
    }

    $title = trim($_POST["title"]);
    if (empty($title)) {
        $title_err = "Please enter a job title!";
    }

    $description = trim($_POST["description"]);
    if (empty($description)) {
        $description_err = "Please enter a job description!";
    }

    $category = trim($_POST["category"]);
    if (empty($category)) {
        $category_err = "Please select a category!";
    }

    $salary = trim($_POST["salary"]);
    if (empty($salary)) {
        $salary_err = "Please enter a salary!";
    }

    if (empty($image_err) && empty($title_err) && empty($description_err) && empty($category_err) && empty($salary_err)) {
        $job = new Job();
        $job->RecruiterId = $recruiter->Id;
        $job->Title = $title;
        $job->Description = $description;
        $job->CategoryId = $category;
        $job->Salary = $salary;
        $job->Skills = explode(",", $_POST["skillIds"]);
        if (!empty($imageUrl))
            $job->ImageUrl = $imageUrl;
        $success = $job->Save();
        if ($success) {
            $title = $description = $category = $salary = $imageUrl = "";
            $msg = "Job has been added successfuly!";
        } else
            $error = "An error occured!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Post Job</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/bootstrap-select.css" />

    <script src="../js/jquery-3.6.0.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap-select.min.js"></script>

    <!-- include TinyMCE plugin -->
    <script src="../plugins/tinymce/jquery.tinymce.min.js"></script>
    <script src="../plugins/tinymce/tinymce.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // initialize TinyMCE
            tinymce.init({
                selector: 'textarea',
                theme: "silver",
                skin: "oxide",
                content_css: 'default',
                statusbar: false,
                menubar: false,
                plugins: 'code lists link',
                toolbar: 'undo redo | styleselect | forecolor | bold italic | bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent | link | code',
            });

            // inilialize select picker
            $('#selectpicker').selectpicker();

            $('#selectpicker').change(function() {
                $('#skillIds').val($('#selectpicker').val());
            });
        });
    </script>
</head>

<body>
    <div class="container-fluid">
        <div class="row fill-height">
            <div class="col-3 z-index-2">
                <!-- menu -->
                <?php menu($recruiter->UserTypeId, "submitJob", $recruiter->Username); ?>
            </div>
            <!-- page -->
            <div class="col-9 wrapper">
                <div class="container-fluid">
                    <div class="title">
                        <h2>Post a New Job</h2>
                    </div>
                    <form class="form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                        <?php if ($error != "") echo '<div class="alert alert-danger p-1 mt-1">' . $error . '</div>'; ?>
                        <?php if ($msg != "") echo '<div class="alert alert-success p-1 mt-1">' . $msg . '</div>'; ?>
                        <div class="form-group">
                            <label>Featured Image</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" id="inputGroupImage" name="image" class="custom-file-input" accept="image/.jpg, .JPG, .png">
                                    <label class="custom-file-label" for="inputGroupImage">Choose file</label>
                                </div>
                            </div>
                            <?php if ($image_err != "") echo '<span class="error">' . $image_err . '</span>'; ?>
                        </div>

                        <div class="form-group">
                            <label for="title">Job Title*</label>
                            <input type="text" id="title" name="title" class="form-control <?php if ($title_err != "") echo 'is-invalid'; ?>" required value="<?php echo $title; ?>" />
                            <span class="invalid-feedback"><?php echo $title_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="description">Job Description*</label>
                            <textarea id="description" name="description">
                                <?php echo $description; ?>
                            </textarea>
                            <?php if ($description_err != "") echo '<span class="error">' . $description_err . '</span>'; ?>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="category">Category*</label>
                                    <select id="category" name="category" class="form-control <?php if ($category_err != "") echo 'is-invalid'; ?>" required>
                                        <option value="">Please Select</option>
                                        <?php
                                        for ($i = 0; $i < count($categories); $i++) {
                                            echo '<option value="' . $categories[$i]->Id . '"' . ($category == $categories[$i]->Id ? "selected" : "") . ' >' . $categories[$i]->Name . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $category_err; ?></span>
                                </div>
                                <div class="col">
                                    <label for="salary">Salary*</label>
                                    <div class="input-group">
                                        <input type="number" id="salary" name="salary" class="form-control <?php if ($salary_err != "") echo 'is-invalid'; ?>" required value="<?php echo $salary; ?>" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">$</span>
                                        </div>
                                    </div>
                                    <span class="invalid-feedback"><?php echo $salary_err; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="skills">Skills</label>
                            <select id="selectpicker" class="form-control" multiple title="Choose required skills">
                                <?php
                                for ($i = 0; $i < count($skills); $i++) {
                                    echo '<option value="' . $skills[$i]->Id . '" data-tokens="' . $skills[$i]->Id . '">' . $skills[$i]->Name . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" id="skillIds" name="skillIds" />

                        <input type="submit" class="btn btn-primary btn-lg btn-block" value="Post" />
                    </form>

                </div>
            </div>
        </div>
    </div>
</body>