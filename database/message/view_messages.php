<?php
session_start();

// Set session timeout duration (30 minutes)
$timeoutDuration = 1800; // 30 minutes in seconds

// Check if the user is logged in and if the session is set
if (isset($_SESSION['username'])) {
    // Check if the last activity timestamp is set
    if (isset($_SESSION['last_activity'])) {
        // Calculate the time since the last activity
        $elapsedTime = time() - $_SESSION['last_activity'];

        // If the time elapsed is greater than the timeout duration, log the user out
        if ($elapsedTime >= $timeoutDuration) {
            session_unset(); // Remove session variables
            session_destroy(); // Destroy the session
            header("Location: ../login.php?message=Session expired. Please log in again."); // Redirect to login page
            exit();
        }
    }
    
    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
} else {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: ../login.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection
include("../config.php");

// Handle AJAX request to delete a message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id']; // Cast to int for safety
    $deleteStmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $deleteStmt->bind_param("i", $delete_id);

    if ($deleteStmt->execute()) {
        echo "success"; // Send success response
    } else {
        echo "Error: " . $deleteStmt->error; // Send error response
    }

    $deleteStmt->close();
    $conn->close();
    exit(); // Stop script execution after handling the AJAX request
}

// Fetch messages from the database
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css"> 
    <link rel="icon" href="../../images/logo.png" type="image/jpg">
    <style>
        .submenu {
            display: none;
        }
    </style>
</head>
<body>
<header class="sticky-top bg-light py-2">
    <div class="container-fluid">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <!-- Logo and Title -->
            <div class="col-auto d-flex align-items-center mb-3 mb-sm-0">
                <img src="../../images/logo.png" alt="Logo" width="50">
                <h1 class="h4 mb-0 ms-2">Le Jardin de Kakoo</h1>
            </div>

            <!-- Navigation and Logout Button -->
            <div class="col-auto d-flex align-items-center">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <!-- Navbar toggler for smaller screens -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- Navbar Links -->
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-3">
                            <li class="nav-item"><a class="nav-link" href="../../pages/home.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="../../pages/shop.php">Shop</a></li>
                            <li class="nav-item"><a class="nav-link" href="../../pages/about.php">About Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="../../pages/contactus.php">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="../database.php">Database</a></li>
                            <button id="login-icon" onclick="window.location.href='../logout.php';" aria-label="Logout" class="btn btn-success ms-3">Logout</button>
                        </ul>
                    </div>
                </nav>
                <!-- Logout Button -->
                <div class="d-lg-none text-end">
    <button class="btn btn-primary mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSideNav" aria-expanded="false" aria-controls="mobileSideNav">
        Menu
    </button>
</div>
            </div>
        </div>
    </div>
</header>


<aside class="side-nav d-lg-block d-none" id="sideNav">
    <ul>
        <br><br><br>
        <li><a href="../database.php"><b>Home</b></a></li>
        <li><a href="../sidenav/home.php"><b>Search</b></a></li>
        <li class="has-submenu">
            <a href="#"><b>Plants</b></a>
            <ul class="submenu">
                <li><a href="../sidenav/tress.php">Trees</a></li>
                <li><a href="../sidenav/shrubs.php">Shrubs</a></li>
                <li><a href="../sidenav/ferns.php">Ferns</a></li>
                <li><a href="../sidenav/climbers.php">Climbers</a></li>
                <li><a href="../sidenav/waterplants.php">Water Plants</a></li>
                <li><a href="../sidenav/palms.php">Palms</a></li>
                <li><a href="../sidenav/cactus.php">Cactus</a></li>
                <li><a href="../sidenav/succulent.php">Succulent</a></li>
                <li><a href="../sidenav/annuals.php">Annuals</a></li>
                <li><a href="../sidenav/perinnals.php">Perennials</a></li>
                <li><a href="../sidenav/indoorplants.php">Indoor Plants</a></li>
                <li><a href="../sidenav/herbs.php">Herbs</a></li>
            </ul>
        </li>
        <li><a href="../sidenav/cuttings.php"><b>Cuttings</b></a></li>
        <li><a href="../plan/plan.php"><b>Plan</b></a></li>
        <li><a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
        <li><a href="../sold.php"><b>Sold Units</b></a></li>
        <li><a href="../manage_users.php"><b>Users</b></a></li>
        <li><a href="../receive_orders.php"><b>Orders</b></a></li>
        <li><a href="view_messages.php"><b>View Messages</b></a></li>
    </ul>
</aside>

<!-- Mobile Side Navigation Toggle -->

<div class="collapse" id="mobileSideNav">
    <aside class="side-nav">
        <ul>
            <li><a href="../database.php"><b>Home</b></a></li>
            <li><a href="../sidenav/home.php"><b>Search</b></a></li>
            <li class="has-submenu">
                <a href="#"><b>Plants</b></a>
                <ul class="submenu">
                    <li><a href="../sidenav/tress.php">Trees</a></li>
                    <li><a href="../sidenav/shrubs.php">Shrubs</a></li>
                    <li><a href="../sidenav/ferns.php">Ferns</a></li>
                    <li><a href="../sidenav/climbers.php">Climbers</a></li>
                    <li><a href="../sidenav/waterplants.php">Water Plants</a></li>
                    <li><a href="../sidenav/palms.php">Palms</a></li>
                    <li><a href="../sidenav/cactus.php">Cactus</a></li>
                    <li><a href="../sidenav/succulent.php">Succulent</a></li>
                    <li><a href="../sidenav/annuals.php">Annuals</a></li>
                    <li><a href="../sidenav/perinnals.php">Perennials</a></li>
                    <li><a href="../sidenav/indoorplants.php">Indoor Plants</a></li>
                    <li><a href="../sidenav/herbs.php">Herbs</a></li>
                </ul>
            </li>
            <li><a href="../sidenav/cuttings.php"><b>Cuttings</b></a></li>
            <li><a href="../plan/plan.php"><b>Plan</b></a></li>
            <li><a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
            <li><a href="../sold.php"><b>Sold Units</b></a></li>
            <li><a href="../manage_users.php"><b>Users</b></a></li>
            <li><a href="../receive_orders.php"><b>Orders</b></a></li>
            <li><a href="view_messages.php"><b>View Messages</b></a></li>
        </ul>
    </aside>
</div>
<div class="main-content">
    <div class="container mt-5">
        <h2>Received Contact Messages</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-wrapper">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>File</th>
                        <th>Submitted At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
        </div>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr id="message-<?php echo $row['id']; ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                            <td>
                                <?php if ($row['file_path']): ?>
                                    <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View File</a>
                                <?php else: ?>
                                    No File
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <button class="btn btn-danger" onclick="deleteMessage(<?php echo $row['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No messages found.</p>
        <?php endif; ?>

        <?php
        // Close the connection
        $conn->close();
        ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    function toggleSubmenu(element) {
        const submenu = element.nextElementSibling;
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    }

    document.querySelectorAll('.has-submenu > a').forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            toggleSubmenu(event.target);
        });
    });

    function deleteMessage(id) {
        if (confirm("Are you sure you want to delete this message?")) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true); // Send to the same page
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Check for success response
                        if (xhr.responseText === "success") {
                            // Remove the message row from the table
                            const row = document.getElementById("message-" + id);
                            row.parentNode.removeChild(row);
                        } else {
                            alert("Error deleting message: " + xhr.responseText);
                        }
                    } else {
                        alert("Error with the request.");
                    }
                }
            };
            xhr.send("delete_id=" + id); // Send the ID of the message to delete
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
