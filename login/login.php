<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../images\LogoRapide.png" type="image/x-icon">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
    body {
        
        background: url('../images/bg_05.jpg') no-repeat center center fixed;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .card {
        margin-top: 100px;
        /* Adjust this value as needed */
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
        background-color: rgba(255, 255, 255, 0.8);
        padding: 25px;
        max-width: 400px;
        width: 100%;
        margin-bottom: 50px;
    }


    .nav-tabs {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;

        border-radius: 10px;
        padding: 10px;
 
    }

    .nav-tabs .nav-link {
        color: #495057;
        border-radius: 20px;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        margin: 0 5px;
    }

    .nav-tabs .nav-link:hover {
        background: #495057;
        color: white;
        transition: 0.5s;
    }

    .nav-tabs .nav-link.active {
        background-color: #ffee00;
        color: black;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .form-group {
        position: relative;
    }

    .form-group input {
        border-radius: 10px;
        padding: 12px 45px;
        border: 1px solid #ced4da;
        transition: all 0.6s;
        margin-bottom: 20px;
    }

    .form-group input:focus {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
    }

    .form-group i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 18px;
    }

    .btn-primary {
        background-color: #ffee00;
        border-color: #ffee00;
        color: black;
        border-radius: 10px;
        padding: 12px 20px;
        width: 100%;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s, border-color 0.3s, transform 0.2s;
    }

    .btn-primary:hover {
        background-color: grey;
        color: white;
        transform: scale(1.05);
        transition: 0.5s;
    }

    .form-title {
        font-size: 30px;
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 25px;
        text-align: center;
        color: #343a40;
    }

    .form-check-input {
        width: 10px;
        height: 20px;
        margin-right: 10px;
    }
    </style>
</head>

<body>
    <div class="card">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="signin-tab" href="#" onclick="showForm('signin')">Login</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="signup-tab" href="#" onclick="showForm('signup')">Sign Up</a>
            </li>
        </ul>
        <div id="formContent">
            <div id="signinForm">
                <h2 class="form-title">Login</h2>
                <form action="logconn.php" method="post">
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input type="text" class="form-control" placeholder="Username" name="uname" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                    </div>
                </form>
            </div>

            <div id="signupForm" style="display: none; ">
                <h2 class="form-title">Sign Up</h2>
                <form action="regconn.php" method="post">
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input type="text" class="form-control" name="fname" placeholder="First Name" required />
                    </div>
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input type="text" class="form-control" name="lname" placeholder="Last Name" required />
                    </div>
                    <div class="form-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" class="form-control" name="email" placeholder="Email" required />
                    </div>
                    <div class="form-group">
                        <i class="fas fa-user-circle"></i>
                        <input type="text" class="form-control" name="uname" placeholder="Username" required />
                    </div>
                    <div class="form-group">
                        <i class="fas fa-phone"></i>
                        <input type="text" class="form-control" name="phone" placeholder="Phone" required />
                    </div>

                    <div class="form-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" name="password" placeholder="Password" required />
                    </div>
                    <div class="form-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" name="confirm" placeholder="Confirm Password"
                            required />
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">Accept Terms & Conditions</label>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Verification Modal -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verificationModalLabel">Enter Verification Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="verify_code.php" method="POST">
                        <div class="form-group">
                            <label for="verification_code">Verification Code:</label>
                            <input type="text" class="form-control" id="verification_code" name="verification_code"
                                placeholder="Enter the code" required>
                        </div>
                        <div class="form-group mt-3 text-center">
                            <button type="submit" class="btn btn-primary">Verify</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function showForm(formId) {
        document.getElementById("signinForm").style.display = formId === "signin" ? "block" : "none";
        document.getElementById("signupForm").style.display = formId === "signup" ? "block" : "none";

        document.getElementById("signin-tab").classList.toggle("active", formId === "signin");
        document.getElementById("signup-tab").classList.toggle("active", formId === "signup");
    }

    // Show verification modal if the session indicates pending verification
    <?php if (isset($_SESSION['verification_pending']) && $_SESSION['verification_pending'] === true) { ?>
    var verificationModal = new bootstrap.Modal(document.getElementById("verificationModal"));
    verificationModal.show();
    <?php unset($_SESSION['verification_pending']); } ?>
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>