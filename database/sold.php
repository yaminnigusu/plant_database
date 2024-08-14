<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sold Plants</title>
    <link rel="stylesheet" href="styles.css"> <!-- Update the path as needed -->
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
<header class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col"></div>
                
                <h1>Le Jardin de Kakoo</h1>
            </div>
            <nav>
                <a href="../pages/home.php">Home</a>
                <a href="../pages/shop.php">Shop</a>
                <a href="../pages/about.php">About Us</a>
                <a href="../pages/contactus.php">Contact Us</a>
                <a href="database.php">Database</a>
                <div class="col-auto">
                    <button id="login-icon" onclick="toggleLoginForm()" aria-label="Login" class="btn btn-success">Login</button>
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
            <li><a href="sold.php"><b>sold units</b></a></li>
        </ul>
    </aside>
    <div class="main-content">
    <div class="container">
    <div class="page-header">
        <h1 class="mt-4">Sold Plants</h1>
        <a href="register_sale.php" class="register-sale-link">Register Sale </a>
        </div>
        <!-- Search Form -->
        <form method="GET" action="sold.php" class="mb-4">
            <div class="form-group">
                <label for="search">Search by Plant Name:</label>
                <input type="text" id="search" name="search" class="form-control" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <table class="table-custom">
            <thead class="thead-dark">
                <tr>
                    
                    <th>Plant Name</th>
                    <th>Quantity Sold</th>
                    <th>Sale Date</th>
                    <th>Selling Price</th>
                    <th>Total Amount</th>   
                </tr>
            </thead>
            <tbody>
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
    while ($row = $result->fetch_assoc()) {
        $total_amount = $row["selling_price"] * $row["quantity_sold"];
        echo "<tr>";
        
        echo "<td>" . $row["plant_name"] . "</td>";
        echo "<td>" . $row["quantity_sold"] . "</td>";
        echo "<td>" . $row["sale_date"] . "</td>";
        
        echo "<td>" . $row["selling_price"] . "</td>";
        echo "<td>" . number_format($total_amount, 2) .  " Birr</td>"; // Display total amount
        echo "</tr>";

        // Update total quantity sold and total sales amount
        $total_quantity_sold += $row["quantity_sold"];
        $total_sales_amount += $total_amount;
    }
} else {
    echo "<tr><td colspan='7'>No results found</td></tr>";
}

// Close database connection
$conn->close();
?>

            </tbody>
        </table>

        <div class="summary-container mt-4">
    <h3 class="total-label">Total Quantity Sold:</h3>
    <p class="total-value"><?php echo $total_quantity_sold; ?></p>
    <h3 class="total-label">Total Selling Price:</h3>
    <p class="total-value"><?php echo number_format($total_sales_amount, 2); ?> Birr</p>
</div>


    </div>
    </div>
    <script src="path/to/bootstrap.min.js"></script> <!-- Update the path as needed -->
    <script>
        function toggleFormVisibility() {
            var form = document.getElementById('plantForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        document.querySelectorAll('.has-submenu > a').forEach(function (menuLink) {
            menuLink.addEventListener('click', function (event) {
                event.preventDefault();
                var submenu = menuLink.nextElementSibling;
                submenu.style.display = (submenu.style.display === 'block') ? 'none' : 'block';
            });
        });
    </script>
</body>
</html>
