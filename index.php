<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <style>
  
        html,
        body,
        header {
            height: 100%;
        }

        header.masthead {
            padding-top: 10px;
            background: linear-gradient(90deg, #0265ddaa 0%, #02ffffbb 90%), url("./img/home-page.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: scroll;
            background-size: cover;
        }

        header.masthead h1 {
            font-size: 3.5rem;
        }

        #main .navbar-brand:hover {
            color: #fff;
        }

        #main .navbar-brand {
            font-weight: 700;
            color: rgba(255, 255, 255, 0.7);
        }

        .text-white-75 {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        header.masthead p {
            font-size: 1.15rem;
        }

        hr.divider {
            height: 5px;
            max-width: 100px;
            margin: 1.5rem auto;
            background-color: #fff;
        }

        .button {
            padding: 15px;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            border: none;
            border-radius: 10px;
            background-color: #fff;
            color: #007bff;
        }

        .button:hover {
            background-color: #00bbff;
        }
    </style>
</head>

<body>
    <nav class="fixed-top py-3" id="main">
        <div class="container">
            <div class="navbar-brand">Our Website</div>
        </div>
    </nav>
    <header class="masthead">
        <div class="container px-4 px-5 h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-8 align-self-end">
                    <h1 class="text-white font-weight-bold">Your Favorite Place for Free Bootstrap Themes</h1>
                    <hr class="divider" />
                </div>
                <div class="col-8 align-self-baseline">
                    <p class="text-white-75 mb-5">Start Bootstrap can help you build better websites using the Bootstrap framework! Just download a theme and start customizing, no strings attached!</p>
                    <div class="row">
                        <div class="col">
                            <a class="btn btn-primary button" href="./pages/login.php">
                                Login
                            </a>
                        </div>
                        <div class="col">
                            <a class="btn btn-primary button" href="./pages/signup.php">
                                Sign Up
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>

</html>