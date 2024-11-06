
<?php
session_start();

// Store the current page in session if not already logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
} // Check if the user is logged in
include("config.php");
// Add or Update User
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security

    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        // Update user
        $user_id = $_POST['user_id'];
        $sql = "UPDATE users SET username=?, email=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $password, $user_id);
    } else {
        // Add new user
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);
    }

    if ($stmt->execute()) {
        header("Location: manage_users.php?message=success");
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Delete User
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        header("Location: manage_users.php?message=deleted");
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .submenu {
            display: none; /* Hide submenu by default */
        }
    </style>
</head>
<header class="sticky-top bg-light py-2">
    <div class="container-fluid">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <!-- Logo and Title -->
            <div class="col-auto d-flex align-items-center mb-3 mb-sm-0">
                <img src="../images/logo.png" alt="Logo" width="50">
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
                            <li class="nav-item"><a class="nav-link" href="../pages/home.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="../pages/shop.php">Shop</a></li>
                            <li class="nav-item"><a class="nav-link" href="../pages/about.php">About Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="../pages/contactus.php">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="database.php">Database</a></li>
                            <button id="login-icon" onclick="window.location.href='logout.php';" aria-label="Logout" class="btn btn-success ms-3">Logout</button>
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
        <li><a href="message/view_messages.php"><b>View Messages</b></a></li>
    </ul>
</aside>

<!-- Mobile Side Navigation Toggle -->

<div class="collapse" id="mobileSideNav">
    <aside class="side-nav">
        <ul>
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
            <li><a href="message/view_messages.php"><b>View Messages</b></a></li>
        </ul>
    </aside>
</div>

<div class="main-content">
    <h2 class="my-4">Manage Users</h2>

    <!-- Add/Edit User Form -->
    <form action="manage_users.php" method="POST">
        <input type="hidden" name="user_id" id="user_id">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save User</button>
    </form>

    <!-- Display Users -->
    <div class="table-wrapper">
    <table class="table table-striped my-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                    <button class="btn btn-info btn-sm" onclick="editUser(<?php echo $user['id']; ?>, '<?php echo $user['username']; ?>', '<?php echo $user['email']; ?>')">Edit</button>
                    <a href="manage_users.php?delete=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>

<script>
// JavaScript function to fill form for editing a user
function editUser(id, username, email) {
    document.getElementById('user_id').value = id;
    document.getElementById('username').value = username;
    document.getElementById('email').value = email;
    document.getElementById('password').required = false; // Password is not required for editing
}
</script>
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

    
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
