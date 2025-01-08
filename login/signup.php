<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="../css\bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
    body {
        background: linear-gradient(to bottom right, #6a11cb, #2575fc);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;

    }

    .card {
        margin-top: 50vh;
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        background-color: #ffffff;
        padding: 25px;
        width: 100%;
        max-width: 420px;
    }

    .nav-tabs {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
        background-color: rgba(255, 230, 0, 0.2);
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .nav-tabs .nav-link {
        color: #495057;
        border-radius: 30px;
        padding: 10px 25px;
        font-size: 16px;
        font-weight: bold;
        margin: 0 5px;
    }

    .nav-tabs .nav-link:hover {
        background: #495057;
        color: white;
        transition: 0.3s;
    }

    .nav-tabs .nav-link.active {
        background-color: #ffe600;
        color: black;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .form-group {
        position: relative;
        margin-bottom: 20px;
    }

    .form-group input {
        border-radius: 10px;
        padding: 12px 45px;
        border: 1px solid #ced4da;
        transition: all 0.3s ease-in-out;
    }

    .form-group input:focus {
        border-color: #6a11cb;
        box-shadow: 0 0 10px rgba(106, 17, 203, 0.3);
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
        background-color: #6a11cb;
        border-color: #6a11cb;
        color: white;
        border-radius: 10px;
        padding: 12px 20px;
        width: 100%;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #2575fc;
        transform: scale(1.05);
    }

    .form-title {
        font-size: 28px;
        font-weight: bold;
        margin-top: 10px;
        margin-bottom: 25px;
        text-align: center;
        color: #343a40;
    }

    @media (max-width: 576px) {
        .card {
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .form-title {
            font-size: 22px;
        }

        .btn-primary {
            font-size: 14px;
            padding: 10px 16px;
        }

        .form-group input {
            padding: 10px 40px;
        }

        .nav-tabs .nav-link {
            font-size: 14px;
            padding: 8px 16px;
        }
    }
    </style>
</head>

<body>
    <div class="card">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="signin-tab" href="login.php" role="tab" aria-controls="signin"
                    aria-selected="false">Login</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="signup-tab" href="#signup" role="tab" aria-controls="signup"
                    aria-selected="true">Sign Up</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="signup" role="tabpanel" aria-labelledby="signup-tab">
                <h2 class="form-title">Create Your Account</h2>
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
                        <input type="text" class="form-control" name="phone" placeholder="Mobile Number" required />
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
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms">Accept Terms & Conditions</label>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>