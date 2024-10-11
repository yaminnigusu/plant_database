<?php
session_start(); // Ensure session is started

include("../database/config.php");

// Initialize variables
$name = $email = $message = $file_path = '';
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validate inputs
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    if (empty($message)) {
        $errors[] = "Message cannot be empty.";
    }

    // Handle file upload (optional)
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Define your upload directory
        $fileName = basename($_FILES['file']['name']);
        $filePath = $uploadDir . uniqid() . '_' . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            $errors[] = "Failed to upload file.";
        } else {
            $file_path = $filePath; // Set file path for database entry
        }
    }

    // Insert data into the database if there are no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, file_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $message, $file_path);

        if ($stmt->execute()) {
            // Set success message and redirect to contactus.php
            $_SESSION['success_message'] = "Your message has been sent successfully!";
            header("Location: contactus.php");
            exit;
        } else {
            // Handle execution failure
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        // Display errors if there are any
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }

    $conn->close();
}
?>
