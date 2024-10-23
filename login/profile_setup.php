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

// Check if the query succeeded and if rows were returned
if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the user's data
    $user_data = mysqli_fetch_assoc($result);

    // Assign the fetched data to variables
    $fname = $user_data['fname'];
    $lname = $user_data['lname'];
    $email = $user_data['email'];
    $username = $user_data['username'];
    $phone = $user_data['phone'];
    $province = isset($user_data['province']) ? $user_data['province'] : '';
    $city = isset($user_data['city']) ? $user_data['city'] : '';
    $barangay = isset($user_data['brgy']) ? $user_data['brgy'] : '';
    $bday = isset($user_data['bday']) ? $user_data['bday'] : '';
    $pic = isset($user_data['pic']) ? $user_data['pic'] : '';
} else {
    // If no user found, show a message or handle the case appropriately
    die("User not found in the database.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* General body styling */
    body {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    /* Form container styling */
    .container {
        max-width: 600px;
        margin: 80px auto;
        background-color: #fff;
        padding: 30px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    /* Title styling */
    h2 {
        font-size: 28px;
        color: #333;
        text-align: center;
        margin-bottom: 20px;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }

    /* Profile picture styling */
    .container img {
        display: block;
        margin: 0 auto 20px auto;
        border-radius: 50%;
        border: 3px solid #ddd;
        max-width: 100%;
        height: 100px;
        width: 100px;
    }

    /* Input fields and dropdowns styling */
    .form-control {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        font-size: 16px;
        margin-bottom: 20px;
        width: 100%;
    }

    /* Labels styling */
    .form-label {
        font-weight: bold;
        color: #333;
    }

    /* Submit button styling */
    .btn-primary {
        background-color: grey;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        width: 100%;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: rgb(192, 163, 0);
        color: black;
    }

    /* Adjusting image and input field alignment */
    input[type="file"] {
        padding: 5px;
    }

    /* Responsive styling */
    @media (max-width: 768px) {
        .container {
            max-width: 100%;
            margin: 40px 15px;
            padding: 20px;
        }

        h2 {
            font-size: 24px;
        }

        .form-control {
            font-size: 14px;
            padding: 8px;
        }

        .btn-primary {
            font-size: 16px;
        }
    }

    @media (max-width: 576px) {
        h2 {
            font-size: 22px;
        }

        .form-control {
            font-size: 14px;
        }

        .btn-primary {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 20px;
        }

        .container {
            padding: 15px;
            margin: 30px 10px;
        }

        .btn-primary {
            font-size: 14px;
        }
    }

    @media (max-width: 360px) {
        .container {
            padding: 10px;
        }

        h2 {
            font-size: 18px;
        }

        .btn-primary {
            font-size: 14px;
        }
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Set Up Your Profile</h2>

        <!-- Profile Picture -->
        <!-- Profile Picture -->
        <div class="mb-3">

            <!-- <?php if (!empty($profile_pic)): ?>
            <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" width="150">
            <?php else: ?>
            <img src="../images/profile-user.png" alt="Default Profile Picture" width="150">
            <?php endif; ?> -->

            <?php
            if (!empty($pic)) {
                $profilePicPath = '../user/' . $pic;
                echo '<img src="' . htmlspecialchars($profilePicPath) . '" alt="Profile Picture" width="150">';
            } else {
                echo '<img src="../images/profile-user.png" alt="Default Profile Picture" width="150">';
            }
            ?>

        </div>
        <div class="mb-3">
            <label for="profile_pic" class="form-label">Upload Profile Picture</label>
            <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept="image/*">
        </div>

        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <!-- Personal Information Fields -->
            <div class="row">
                <div class="col-6">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname; ?>"
                        required>
                </div>
                <div class="col-6">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname; ?>"
                        required>
                </div>

                <div class="col-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>"
                        readonly required>
                </div>
                <div class="col-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                        value="<?php echo $username; ?>" required>
                </div>
                <div class="col-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>"
                        required>
                </div>

                <!-- Address Fields -->
                <div class="col-6">
                    <label for="province" class="form-label">Province</label>
                    <select class="form-control" id="province" name="province" required>
                        <option value="Cavite" <?php if ($province == 'Cavite') echo 'selected'; ?>>Cavite</option>
                    </select>
                </div>
                <?php
            // Fetch city list from the database
            $query = "SELECT id, city_name FROM city_list";
            $result = mysqli_query($conn, $query);

            // Check if city is set from user data (for editing purposes)
            $city = isset($user_data['city']) ? $user_data['city'] : '';
            ?>

                <div class="col-7">
                    <label for="city" class="form-label">City/Municipality</label>
                    <select class="form-control" id="city" name="city" required>
                        <option value="">Select City/Municipality</option>
                        <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $city_id = $row['id'];
                            $city_name = $row['city_name'];
                            // Check if this city is the currently selected city
                            $selected = ($city == $city_name) ? 'selected' : '';
                            echo "<option value='$city_id' $selected>$city_name</option>";
                        }
                    }
                    ?>
                    </select>
                </div>
                <div class="col-5">
                    <label for="barangay" class="form-label">Barangay</label>
                    <select class="form-control" id="barangay" name="barangay" required>
                        <option value="">Select Barangay</option>
                    </select>
                </div>
            </div>

            <!-- Birthday Field -->
            <div class="mb-3">
                <label for="bday" class="form-label">Birthday</label>
                <input type="date" class="form-control" id="bday" name="bday" value="<?php echo $bday; ?>" required>
            </div>

            <!-- Profile Picture Upload -->
            <<<<<<< HEAD=======<div class="mb-3">
                <label for="profile_pic" class="form-label">Upload Profile Picture</label>
                <input type="file" class="form-control" id="profile_pic" name="pic" accept="image/*">
    </div>
    >>>>>>> 389d98dd801711220654f3ba2e764b23636498c8

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AJAX to fetch barangays based on selected city -->
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
    </script>
</body>

</html>