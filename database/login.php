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
        body, h2, form, p, footer {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #333;
        }

        body {
            background-color: #f5f5f5; /* Light background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            font-size: 24px;
            color: #2d572c;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            background-color: #fff;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #34a853; /* Focus state with green border */
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #34a853;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2d572c; /* Darker green on hover */
        }

        /* Error message styling */
        .error {
            color: red;
            margin-bottom: 15px;
        }

        /* Footer Styling */
        footer {
            margin-top: 20px;
            font-size: 14px;
        }

        footer a {
            color: #2d572c;
            text-decoration: underline;
        }

        footer a:hover {
            color: #34a853;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Welcome to Le Jardin de Kakoo Database</h2>

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

        <!-- Footer for contact information and home page link -->
        <footer>
            <p>For support, contact us at <a href="mailto:contact@lejardin.com">contact@lejardin.com</a></p>
            <p><a href="http://localhost/lejardin/pages/home.php">Go back to Home Page</a></p>
        </footer>
    </div>
</body>
</html>
