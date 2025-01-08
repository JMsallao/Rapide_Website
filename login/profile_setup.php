<?php
session_start();
include('../connection.php');

// Ensure the session user ID is set correctly
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} else {
    die("User ID is not set in the session.");
}

// Initialize variables to avoid undefined variable warnings
$fname = '';
$lname = '';
$email = '';
$username = '';
$phone = '';
$province = '';
$city = '';
$barangay = '';
$bday = '';
$pic = '';

// Fetch the user data from the database if the user is logged in
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);

    // Assign the fetched data to variables
    $fname = $user_data['fname'];
    $lname = $user_data['lname'];
    $email = $user_data['email'];
    $username = $user_data['username'];
    $phone = $user_data['phone'];
    $province = isset($user_data['province']) ? $user_data['province'] : 'Cavite';
    $city = isset($user_data['city']) ? $user_data['city'] : '';
    $barangay = isset($user_data['brgy']) ? $user_data['brgy'] : '';
    $bday = isset($user_data['bday']) ? $user_data['bday'] : '';
    $pic = isset($user_data['pic']) ? $user_data['pic'] : '';
} else {
    die("User not found in the database.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step-by-Step Profile Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
            margin-bottom: 30px;
            text-align: center;
        }

        .progress-container {
            margin-bottom: 30px;
            position: relative;
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
        }

        .progress-bar {
            height: 10px;
            background-color: #007bff;
            border-radius: 5px;
            transition: width 0.4s;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .form-label {
            font-weight: bold;
            margin-top: 15px;
        }

        .btn-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
        }

        #prevBtn {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        #prevBtn:hover {
            background-color: #5a6268;
        }

        #nextBtn {
            background-color: #007bff;
            color: white;
            border: none;
        }

        #nextBtn:hover {
            background-color: #0056b3;
        }

        #submitBtn {
            background-color: #28a745;
            color: white;
            border: none;
        }

        #submitBtn:hover {
            background-color: #218838;
        }

        input,
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 15px;
        }

        input:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Profile Setup</h2>
        <div class="progress-container">
            <div class="progress-bar" id="progress-bar"></div>
        </div>

        <form action="update_profile.php" method="POST" enctype="multipart/form-data" id="profileForm">
            <div class="step active" id="step-1">
                <h4>Step 1: Personal Information</h4>
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" name="fname" value="<?php echo $fname; ?>" required>

                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lname" value="<?php echo $lname; ?>" required>

                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo $username; ?>" required>

                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" readonly required>

                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>" required>

                <label for="bday" class="form-label">Birthday</label>
                <input type="date" class="form-control" name="bday" value="<?php echo $bday; ?>" required>
            </div>

            <div class="step" id="step-2">
                <h4>Step 2: Address</h4>
                <div class="row">
                    <div class="col-6">
                        <label for="province" class="form-label">Province</label>
                        <select class="form-control" id="province" name="province" required>
                            <option value="Cavite" <?php if ($province == 'Cavite') echo 'selected'; ?>>Cavite</option>
                        </select>
                    </div>
                    <?php
                    // Fetch city list from the database
                    $city_query = "SELECT id, city_name FROM city_list";
                    $city_result = mysqli_query($conn, $city_query);
                    ?>
                    <div class="col-6">
                        <label for="city" class="form-label">City/Municipality</label>
                        <select class="form-control" id="city" name="city" required>
                            <option value="">Select City/Municipality</option>
                            <?php
                            if (mysqli_num_rows($city_result) > 0) {
                                while ($row = mysqli_fetch_assoc($city_result)) {
                                    $city_id = $row['id'];
                                    $city_name = $row['city_name'];
                                    $selected = ($city == $city_id) ? 'selected' : '';
                                    echo "<option value='$city_id' $selected>$city_name</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <label for="barangay" class="form-label">Barangay</label>
                        <select class="form-control" id="barangay" name="barangay" required>
                            <option value="">Select Barangay</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="step" id="step-3">
                <h4>Step 3: Profile Picture</h4>
                <label for="pic" class="form-label">Upload Picture</label>
                <input type="file" class="form-control" name="pic" accept="image/*">
            </div>

            <div class="btn-navigation">
                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeStep(-1)">Back</button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">Next</button>
                <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">Submit</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.getElementById('city').addEventListener('change', function() {
        var cityId = this.value;
        var barangayDropdown = document.getElementById('barangay');

        // Clear previous barangay options
        barangayDropdown.innerHTML = '<option value="">Select Barangay</option>';

        if (cityId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_brgy.php?city_id=' + cityId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var barangays = JSON.parse(xhr.responseText);
                    barangays.forEach(function(barangay) {
                        var option = document.createElement('option');
                        option.value = barangay.id;
                        option.textContent = barangay.brgy_name;
                        barangayDropdown.appendChild(option);
                    });
                }
            };
            xhr.send();
        }
    });

        let currentStep = 1;
        const totalSteps = 3;

        function showStep(step) {
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
            document.getElementById(`step-${step}`).classList.add('active');

            document.getElementById('progress-bar').style.width = ((step - 1) / (totalSteps - 1)) * 100 + '%';

            document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'inline-block';
            document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'inline-block';
            document.getElementById('submitBtn').style.display = step === totalSteps ? 'inline-block' : 'none';
        }

        function changeStep(stepChange) {
            const form = document.getElementById('profileForm');
            const currentStepDiv = document.querySelector(`.step.active`);
            const inputs = currentStepDiv.querySelectorAll('input, select');

            for (let input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return;
                }
            }

            currentStep += stepChange;
            if (currentStep < 1) currentStep = 1;
            if (currentStep > totalSteps) currentStep = totalSteps;

            showStep(currentStep);
        }

        showStep(currentStep);
    </script>
</body>
</html>
