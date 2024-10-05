<?php
session_start();
include 'config.php'; // Include your database connection

// Redirect to database.php if already logged in
if (isset($_SESSION['username'])) {
    header("Location: database.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to get the user data from the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // Bind the username parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the entered password with the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['username'] = $user['username']; // Set session for logged-in user
            
            // Check if there's a stored redirect URL and redirect the user
            if (isset($_SESSION['redirect_to'])) {
                $redirect_url = $_SESSION['redirect_to'];
                unset($_SESSION['redirect_to']); // Clear the redirect URL from session
                header("Location: " . $redirect_url); // Redirect to the original page
            } else {
                header("Location: database.php"); // Default redirect if no specific URL
            }
            exit();
        } else {
            // Invalid password
            $error = "Invalid username or password!";
        }
    } else {
        // Username not found
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS file -->
    <style>
        /* styles.css */
        body, h2, form, p {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            width: 300px;
            margin: 100px auto;
            padding: 30px;
            background-color: #f4f4f4;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            margin-top: 15px;
            font-size: 14px;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        /* Error message styling */
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Display error message if any -->
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        
    </div>
</body>
</html>
