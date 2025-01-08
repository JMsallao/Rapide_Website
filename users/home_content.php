<?php
// Include your database connection
include('../connection.php');

// Query to fetch content and associated images
$sql = "
    SELECT 
        c.id AS content_id, 
        c.heading, 
        c.description, 
        c.button_text, 
        c.button_link,
        i.image_url
    FROM homepage_content c
    LEFT JOIN bg_img i ON c.id = i.content_id
";
$result = $conn->query($sql);

// Group content and images together
$sliders = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $content_id = $row['content_id'];
        // Initialize content if not already done
        if (!isset($sliders[$content_id])) {
            $sliders[$content_id] = [
                'heading' => $row['heading'],
                'description' => $row['description'],
                'button_text' => $row['button_text'],
                'button_link' => $row['button_link'],
                'images' => [],
            ];
        }
        // Add images to the content
        if ($row['image_url']) {
            $sliders[$content_id]['images'][] = $row['image_url'];
        }
    }
}

// Word styling logic: Define words to style
$words_to_style = ["Automotive", "Trust"]; // Words to highlight

// Apply word styling to each slider's heading
foreach ($sliders as &$slider) {
    foreach ($words_to_style as $word) {
        $slider['heading'] = preg_replace(
            "/\b($word)\b/i", // Match the exact word (case-insensitive)
            '<span>$1</span>', // Wrap the word in <span>
            $slider['heading']
        );
    }
}
// Query to fetch schedule content
$sql_schedule = "
    SELECT 
        id AS content_id, 
        heading, 
        description, 
        button_text, 
        button_link, 
        subhead
    FROM homepage_content
    WHERE type = 'schedule'
";
$result_schedule = $conn->query($sql_schedule);

// Group schedule content
$schedule = [];
if ($result_schedule && $result_schedule->num_rows > 0) {
    while ($row = $result_schedule->fetch_assoc()) {
        // Apply styling to subheadings
        $styled_subheading = preg_replace(
            "/\b(Emergency Towing|Schedule|Time)\b/i", // Match specific subheadings
            '<span>$1</span>', // Wrap matched subheadings in <span>
            $row['subhead']
        );

        // Apply styling to headings
        $styled_heading = $row['heading'];
        foreach ($words_to_style as $word) {
            $styled_heading = preg_replace(
                "/\b($word)\b/i", // Match the exact word (case-insensitive)
                '<span>$1</span>', // Wrap the word in <span>
                $styled_heading
            );
        }

        $schedule[] = [
            'subhead' => $styled_subheading, // Store the styled subheading
            'heading' => $styled_heading,       // Store the styled heading
            'description' => $row['description'],
            'button_text' => $row['button_text'],
            'button_link' => $row['button_link'],
        ];
    }
}

// Fetch user data from the database securely using prepared statements
$username = "Guest"; // Default username for fallback

$query = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $user_id); // Bind the user ID to the query
    $stmt->execute(); // Execute the query
    $result = $stmt->get_result(); // Get the result of the query

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username']; // Assign the username
    } else {
        // Handle case where user data is not found
        $username = "Guest"; 
    }
    $stmt->close(); // Close the statement
} else {
    die("Failed to prepare the database query.");
}

// Fetch stats from the database
$stats_query = "SELECT subhead, heading FROM homepage_content WHERE type = 'stats'";
$stats_result = $conn->query($stats_query);

$stats = [];
if ($stats_result && $stats_result->num_rows > 0) {
    while ($row = $stats_result->fetch_assoc()) {
        $stats[] = $row; // Append the data to the $stats array
    }
}

// Query to fetch "clean" content and its associated images
$sql_clean = "
    SELECT 
        c.id AS content_id, 
        c.heading, 
        c.description, 
        i.image_url
    FROM homepage_content c
    LEFT JOIN bg_img i ON c.id = i.content_id
    WHERE c.id IN (SELECT id FROM homepage_content WHERE heading LIKE '%clean%')
";
$result_clean = $conn->query($sql_clean);

// Group "clean" content and images together
$clean_content = [];
if ($result_clean && $result_clean->num_rows > 0) {
    while ($row = $result_clean->fetch_assoc()) {
        $content_id = $row['content_id'];
        // Initialize content if not already done
        if (!isset($clean_content[$content_id])) {
            $clean_content[$content_id] = [
                'heading' => $row['heading'],
                'description' => $row['description'],
                'images' => [],
            ];
        }
        // Add images to the content
        if ($row['image_url']) {
            $clean_content[$content_id]['images'][] = $row['image_url'];
        }
    }
}

// Fetch Homepage Content with Images
$query = "
    SELECT hc.id, hc.subhead, hc.heading, hc.description, hc.button_text, hc.button_link, hc.type, bi.image_url 
    FROM homepage_content hc
    LEFT JOIN bg_img bi ON hc.id = bi.content_id
    ORDER BY hc.id
";
$result = $conn->query($query);

$contents = [];
$general_section = []; // For the section title
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $content_id = $row['id'];
        if ($row['type'] === 'general') {
            $general_section = [
                'heading' => $row['heading'],
                'description' => $row['description']
            ];
        } elseif (!isset($contents[$content_id])) {
            $contents[$content_id] = [
                'id' => $row['id'],
                'subhead' => $row['subhead'],
                'heading' => $row['heading'],
                'description' => $row['description'],
                'button_text' => $row['button_text'],
                'button_link' => $row['button_link'],
                'type' => $row['type'],
                'images' => []
            ];
        }
        if (!empty($row['image_url'])) {
            $contents[$content_id]['images'][] = $row['image_url'];
        }
    }
}

// Fetch pricing content from the homepage_content table
$query = "
    SELECT id, heading, subhead, description, button_text, button_link
    FROM homepage_content
    WHERE type = 'pricing'
    ORDER BY id
";
$result = $conn->query($query);

$pricing_cards = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pricing_cards[] = [
            'heading' => $row['heading'],
            'subhead' => $row['subhead'],
            'description' => $row['description'],
            'button_text' => $row['button_text'],
            'button_link' => $row['button_link']
        ];
    }
}

?>
