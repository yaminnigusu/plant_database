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
            header("Location: login.php?message=Session expired. Please log in again."); // Redirect to login page
            exit();
        }
    }
    
    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
} else {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include your database connection file
include("../database/config.php"); 

// Fetch orders from the database
$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="../images/logo.png" type="image/jpg">
    <style>
         .submenu {
            display: none; /* Hide submenu by default */
        }
        
       
        table {
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .alert-info {
            text-align: center;
            font-weight: bold;
        }
        .btn-success {
            margin: 15px 0;
        }
    </style>
</head>
<body>
<header class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col"></div>
                <div class="col-left">
                    <img src="../images/logo.png" alt="Logo" width="50">
                </div>
                <h1>Le Jardin de Kakoo</h1>
            </div>
            <nav>
                <a href="../pages/home.php">Home</a>
                <a href="../pages/shop.php">Shop</a>
                <a href="../pages/about.php">About Us</a>
                <a href="../pages/contactus.php">Contact Us</a>
                <a href="database.php">Database</a>
                <div class="col-auto">
                <button id="login-icon" onclick="window.location.href='logout.php';" aria-label="Login" class="btn btn-success">Logout</button>

                </div>
            </nav>
        </div>
    </header>
<aside class="side-nav" id="sideNav">
    <ul>
        <br>
        <br>
       
        <li><a href="database.php"><b>Home</b></a></li>
        <li><a href="sidenav/home.php"><b>Search</b></a></li>
        <li class="has-submenu">
            <a href="#"><b>Plants</b></a>
            <ul class="submenu">
                <li><a href="sidenav/tress.php">Trees</a></li>
                <li><a href="sidenav/shrubs.php">Shrubs</a></li>
                <li><a href="sidenav/ferns.php">Ferns</a></li>
                <li><a href="sidenav/climbers.php">Climbers</a></li>
                <li><a href="sidenav/waterplants.php">Water Plants</a></li>
                <li><a href="sidenav/palms.php">Palms</a></li>
                <li><a href="sidenav/cactus.php">Cactus</a></li>
                <li><a href="sidenav/succulent.php">Succulent</a></li>
                <li><a href="sidenav/annuals.php">Annuals</a></li>
                <li><a href="sidenav/perinnals.php">Perennials</a></li>
                <li><a href="sidenav/indoorplants.php">Indoor Plants</a></li>
                <li><a href="sidenav/herbs.php">Herbs</a></li>
            </ul>
        </li>
        <li><a href="sidenav/cuttings.php"><b>Cuttings</b></a></li>
        <li><a href="plan/plan.php"><b>Plan</b></a></li>
        <li><a href="cost/cost.php"><b>Cost and Analytics</b></a></li>
        <li><a href="sold.php"><b>Sold Units</b></a></li>
        <li><a href="manage_users.php"><b>Users</b></a></li>
        <li><a href="receive_orders.php"><b>Orders</b></a></li>
        <li><a href="message/view_messages.php"><b>view messages</b></a></li>
    </ul>
</aside>
<div class="main-content">
<div class="container">
    <h1>Received Orders</h1>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Plant Name</th>
                    <th>Quantity</th>
                    <th>Delivery Date</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Comments</th>
                    <th>Delivery location</th>
                    <th>Transaction Number</th>
                    <th>Payment Confirmation Image</th>
                    
                    <th>Actions</th> <!-- New Actions Column -->
                </tr>
            </thead>
            <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']); ?></td>
            <td><?= htmlspecialchars($row['plant_name']); ?></td>
            <td><?= htmlspecialchars($row['quantity']); ?></td>
            <td><?= date('l, F j', strtotime($row['delivery_date'])); ?></td>
            <td><?= htmlspecialchars($row['customer_name']); ?></td>
            <td><?= htmlspecialchars($row['customer_email']); ?></td>
            <td><?= htmlspecialchars($row['customer_phone']); ?></td>
            <td><?= htmlspecialchars($row['comments']); ?></td>
            <td><?= htmlspecialchars($row['delivery_location']); ?></td>
            
            <!-- Transaction Number Column -->
            <td><?= htmlspecialchars($row['transaction_number']); ?></td>
            
            <!-- Payment Confirmation Image Column -->
            <td>
                <?php if (!empty($row['payment_confirmation'])): ?>
                    <?php $imagePath = "../images/" . htmlspecialchars($row['payment_confirmation']); ?>
                    <?php if (file_exists($imagePath)): ?>
                        <img src="<?= $imagePath; ?>" alt="Payment Confirmation" class="image-preview" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                    <?php else: ?>
                        <p>Image not found at <?= htmlspecialchars($imagePath); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    No image uploaded.
                <?php endif; ?>
            </td>

            <td>
                <a href="view_order.php?id=<?= htmlspecialchars($row['id']); ?>" class="btn btn-info btn-sm">View</a>
                <a href="edit_order.php?id=<?= htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_order.php?id=<?= htmlspecialchars($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

    <?php else: ?>
        <div class="alert alert-info">No orders have been placed yet.</div>
    <?php endif; ?>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>