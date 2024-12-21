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
    <title>Le Jardin-sold plants</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="../images/logo.png" type="image/jpg"> <!-- Update the path as needed -->
    <style>
      .submenu {
            display: none; /* Hide submenu by default */
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .page-header h1 {
            margin: 0;
            font-size: 2rem; /* Adjust font size if needed */
        }
        .register-btn {
            margin-left: 20px; /* Adjust this value as needed */
            font-size: 1rem; /* Adjust font size if needed */
            padding: 10px 20px; /* Adjust padding if needed */
        }
        .register-sale-link {
        text-decoration: none;
        font-weight: bold;
        padding: 8px 16px;
        margin-right: 10px;
        background-color: #007bff; /* Bootstrap primary color */
        color: #fff; /* White text color */
        border-radius: 4px;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .register-sale-link:hover {
        background-color: #0056b3; /* Darker shade of primary color */
    }
    .table-custom {
    border-collapse: collapse; /* Ensure borders are collapsed */
    width: 100%;
    background-color: #f9f9f9;
    margin-top: 20px; /* Add some margin on top */
  }

  .table-custom th, .table-custom td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center; /* Center align text in headers and cells */
  }

  .table-custom th {
    background-color: #007bff; /* Bootstrap primary color */
    color: #fff; /* White text color */
    font-weight: bold; /* Bold text for headers */
  }

  .table-custom tbody tr:nth-child(even) {
    background-color: #f2f2f2; /* Light grey for even rows */
  }

  .table-custom tbody tr:hover {
    background-color: #e9ecef; /* Light grey on hover */
  }

  .table-custom td {
    font-size: 14px; /* Adjust font size if needed */
  }

  .table-custom th,
  .table-custom td {
    border: 1px solid #ddd; /* Border color */
  }

  .table-custom th {
    background-color: #343a40; /* Darker color for the header */
    color: #fff; /* White text color */
  }

  .table-custom td {
    background-color: #fff; /* White background for table cells */
  }

  .table-custom .total-row {
    font-weight: bold; /* Bold font for total row */
    background-color: #e9ecef; /* Light grey background for total row */
  }
  .summary-container {
    margin: 20px auto; /* Center the container horizontally with auto margins */
    padding: 15px; /* Padding inside the summary section */
    border: 1px solid #ddd; /* Border color */
    border-radius: 8px; /* Rounded corners */
    background-color: #f9f9f9; /* Background color */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow for depth */
    max-width: 500px; /* Set a max width for the container */
    text-align: center; /* Center align text */
  }

  .summary-container h3 {
    margin: 10px 0; /* Margin for spacing between headings */
    font-size: 1.25rem; /* Font size for headings */
    color: #333; /* Text color */
  }

  .summary-container .total-label {
    font-weight: bold; /* Bold label text */
    color: #007bff; /* Bootstrap primary color for label */
  }

  .summary-container .total-value {
    font-weight: normal; /* Normal weight for values */
    color: #333; /* Text color for values */
  }

    </style>
</head>
<body>
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
    <div class="container">
    <div class="page-header">
        <h1 class="mt-4">Sold Plants</h1>
        <a href="register_sale.php" class="register-sale-link">Register Sale</a>
    </div>
    <!-- Search Form -->
    <form method="GET" action="sold.php" class="mb-4">
        <div class="form-group">
            <label for="search">Search by Plant Name:</label>
            <input type="text" id="search" name="search" class="form-control" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <div class="table-wrapper">
    <table class="table-custom">
        <thead class="thead-dark">
            <tr>
                <th>Plant Name</th>
                <th>Quantity Sold</th>
                <th>Sale Date</th>
                <th>Selling Price</th>
                <th>Total Amount</th>   
                <th>Actions</th>   
            </tr>
        </thead>
        <tbody>
</div>
        <?php
        // Database connection parameters
        include("config.php");

        // Get the search term from the query string
        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

        // Construct the SQL query with search functionality
        $sql = "SELECT * FROM sold";
        if ($search) {
            $sql .= " WHERE plant_name LIKE '%$search%'";
        }

        $result = $conn->query($sql);

        // Variables to store total quantity sold and total selling price
        $total_quantity_sold = 0;
        $total_sales_amount = 0.0; // Initialize the variable

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $total_amount = $row['quantity_sold'] * $row['selling_price'];
                $total_quantity_sold += $row['quantity_sold'];
                $total_sales_amount += $total_amount;

                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['plant_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantity_sold']) . "</td>";
                echo "<td>" . htmlspecialchars($row['sale_date']) . "</td>";
                echo "<td>
                        <form method='POST' action='update_price.php'>
                            <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                            <input type='number' name='selling_price' value='" . htmlspecialchars($row['selling_price']) . "' step='0.01' required>
                            <button type='submit' class='btn btn-primary'>Update</button>
                        </form>
                      </td>";
                echo "<td>" . htmlspecialchars(number_format($total_amount, 2)) . "</td>";
                echo "<td><form method='POST' action='delete_sold.php'>
                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                        <button type='submit' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</button>
                      </form></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No records found</td></tr>";
        }

        $conn->close();
        ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>Total</td>
                <td><?php echo htmlspecialchars($total_quantity_sold); ?></td>
                <td colspan="2"></td>
                <td><?php echo htmlspecialchars(number_format($total_sales_amount, 2)); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    </div>
    <div class="summary-container">
        <h3>Summary</h3>
        <p><span class="total-label">Total Quantity Sold:</span> <span class="total-value"><?php echo htmlspecialchars($total_quantity_sold); ?></span></p>
        <p><span class="total-label">Total Sales Amount:</span> <span class="total-value"><?php echo htmlspecialchars(number_format($total_sales_amount, 2)); ?></span></p>
    </div>

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
