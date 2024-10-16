<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up</title>
    <link rel="stylesheet" href="login\login3.css" />
    <link rel="stylesheet" href="css\bootstrap.min.css" />
    <script src="js\bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <style>
    /* General body and text settings */
    body {
        background: url('login_bg.png') no-repeat center center fixed;
        background-size: cover;
        font-family: Arial, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        height: 100vh;
    }



    .main_container {
        background: url('bg1.jpg') no-repeat center center;
        background-size: cover;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 1200px;
        height: 50%;
        padding: 20px;
        margin: auto;
        background-color: transparent;
    }


    /* Login form container */
    .login-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 50%;
        max-width: 500px;
        background-color: rgba(167, 167, 167, 0.5);
        padding: 20px;
        border-radius: 10px;
        margin-left: 50%;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    /* Image container for the right side */
    .image-container {
        width: 50%;
        height: 100%;
    }

    .image-container img {
        top: 0;
        position: absolute;
        width: 50%;
        height: 50%;
        object-fit: cover;
    }

    /* Form styling */
    .login-form h1 {
        text-align: center;
        width: 100%;
    }

    .login-form .form-group {
        width: 100%;
        margin-bottom: 10px;
        display: flex;
        justify-content: center;
    }

    .login-form input,
    .login-form button {
        width: 80%;
        padding: 10px;
        border-radius: 8px;
        margin: 5px 0;
        border: none;
        outline: none;
    }

    .login-form button {
        background-color: rgb(243, 212, 72);
        color: black;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        width: 50%;
        transition: background-color 0.3s ease;
    }

    /* Hover effect - changes background color to red */
    .login-form button:hover {
        background-color: rgb(255, 17, 0);
        color: rgb(255, 255, 255);
    }

    /* Offcanvas styling */
    .offcanvas-header {
        background-color: rgb(61, 61, 61);
    }

    .offcanvas-title {
        color: rgb(0, 0, 0);
    }

    .offcanvas-body {
        background: url('login_bg.png') no-repeat center center;
        background-size: cover;
        color: rgb(0, 0, 0);
    }

    .offcanvas-body input {
        background-color: rgb(255, 255, 255);
        color: black;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .offcanvas-body button {
        background-color: rgb(243, 212, 72);
        /* Initial background color */
        color: black;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    /* Hover effect */
    .offcanvas-body button:hover {
        background-color: rgba(255, 0, 0, 0.7);
        color: rgb(255, 255, 255);
        transform: scale(1.05);
    }


    /* Responsive adjustments */
    @media (max-width: 768px) {
        .main_container {
            flex-direction: column;
            height: auto;
        }

        .login-form,
        .image-container {
            width: 100%;
        }

        .login-form {
            margin-right: 0;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 480px) {
        .login-form {
            padding: 15px;
        }

        .btn-signup {
            padding: 8px 15px;
            font-size: 14px;
        }
    }
    </style>

</head>

<body>
    <div class="main_container">
        <div class="container m-auto">
            <!-- Login Form -->
            <form action="logconn.php" method="post" class="login-form">
                <h1>Login</h1>
                <div class="form-group">
                    <input type="text" placeholder="Username" name="uname" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="login"> Login</button>
                </div>
                <h5>or</h5>
                <button class="btn-signup" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
                    aria-controls="staticBackdrop">
                    Sign up here!
                </button>
            </form>
        </div>

        <!-- Offcanvas for Sign Up -->
        <div class="offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop"
            aria-labelledby="staticBackdropLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="staticBackdropLabel">Sign Up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="regconn.php" method="post">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" name="fname" placeholder="First Name" />
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div>
                                    <div class="form-group">
                                        <input type="text" name="lname" placeholder="Last Name" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" />
                        </div>
                        <div class="form-group">
                            <input type="text" name="uname" placeholder="User Name" />
                        </div>
                        <div class="form-group">
                            <input type="text" name="address" placeholder="Full Address" />
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password" />
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm" placeholder="Confirm Password" />
                        </div>
                        <div class="form-group">
                            <button type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76A7a9Hr8lFztXXwjbK6g3Kbt1Lz6Y3auD8r5c6EwHgjV4ldtJgROZXB6ZGdvep" crossorigin="anonymous">
    </script>
</body>

</html>