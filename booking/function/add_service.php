<?php
include('../../sessioncheck.php');
include('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_POST['image'];

    // Validate category to ensure it matches a known table
    $valid_categories = ['package_list', 'service_list', 'brake_service', 'ac_service'];
    if (!in_array($category, $valid_categories)) {
        echo "Invalid category selected.";
        exit();
    }

    // Prepare the SQL statement to insert into the selected category table
    $query = "INSERT INTO $category (name, description, price, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        die("Error preparing query: " . $conn->error);
    }
    
    // Bind the parameters and execute the statement
    $stmt->bind_param("ssds", $name, $description, $price, $image);
    
    if ($stmt->execute()) {
        // Redirect back to the service list page with a success message
        header("Location: ../adminMoMamaMo/service_list.php?success=1");
    } else {
        echo "Error adding service: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
