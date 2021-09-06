<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Freelancer Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <h1>Freelancer</h1>
                </div>
            </div>
            <div class="row ">
                <div class="col-4"></div>
                <div class="col-4">
                    <form>
                        <h2 class="text-center">Sign Up</h2>
                        <div class="form-group">
                            <input type="text" class="form-control" name="FirstName" placeholder="First Name*" required="required">
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" name="LastName" placeholder="Last Name*" required="required">
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" name="username" placeholder="Username*" required="required">
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control" name="Email" placeholder="Email*" required="required">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password*" required="required">
                        </div>
                        

                        <div class="form-group">
                            <input type="text" class="form-control" name="DOB" placeholder="Date of birth" onfocus="(this.type='date')">
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