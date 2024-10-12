<?php
session_start();

// Store the current page in session if not already logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store the requested URL
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include database configuration
include("../config.php");

// Handle deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $deleteSql = "DELETE FROM plants WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Plant deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting plant: " . $conn->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Database-Herbs</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="icon" href="../../images/logo.png" type="image/jpg">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background: #4CAF50; /* Green background */
            color: white;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .main-content {
            padding: 20px;
        }

        .plant-table-section {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .actionButton {
            color: #4CAF50;
            text-decoration: none;
            margin-right: 10px;
        }

        .deleteButton {
            color: red;
        }

        .checkbox-container {
            display: flex;
            flex-wrap: wrap;
            margin: 10px 0;
        }

        .checkbox-item {
            margin-right: 20px;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .checkbox-container {
                flex-direction: column;
            }
        }

    </style>
</head>
<body>
<header class="sticky-top">
    <div class="container">
        <h1>Le Jardin de Kakoo</h1>
        <nav>
            <a href="../../pages/home.php">Home</a>
            <a href="../../pages/shop.php">Shop</a>
            <a href="../../pages/about.php">About Us</a>
            <a href="../../pages/contactus.php">Contact Us</a>
            <a href="../database.php">Database</a>
            <button id="login-icon" onclick="window.location.href='../logout.php';" aria-label="Logout" class="btn btn-success">Logout</button>
        </nav>
    </div>
</header>

<aside class="side-nav" id="sideNav">
    <ul>
        <li><a href="../database.php"><b>Home</b></a></li>
        <li><a href="home.php"><b>Search</b></a></li>
        <li class="has-submenu">
            <a href="#"><b>Plants</b></a>
            <ul class="submenu">
                <li><a href="tress.php">Trees</a></li>
                <li><a href="shrubs.php">Shrubs</a></li>
                <li><a href="ferns.php">Ferns</a></li>
                <li><a href="climbers.php">Climbers</a></li>
                <li><a href="waterplants.php">Water Plants</a></li>
                <li><a href="palms.php">Palms</a></li>
                <li><a href="cactus.php">Cactus</a></li>
                <li><a href="succulent.php">Succulent</a></li>
                <li><a href="annuals.php">Annuals</a></li>
                <li><a href="perinnals.php">Perennials</a></li>
                <li><a href="indoorplants.php">Indoor Plants</a></li>
                <li><a href="herbs.php">Herbs</a></li>
            </ul>
        </li>
        <li><a href="../plan/plan.php"><b>Plan</b></a></li>
        <li><a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
        <li><a href="../sold.php"><b>Sold Units</b></a></li>
        <li><a href="../manage_users.php"><b>Users</b></a></li>
        <li><a href="../receive_orders.php"><b>Orders</b></a></li>
        <li><a href="../message/view_messages.php"><b>View Messages</b></a></li>
    </ul>
</aside>

<div class="main-content">
    <h1>Plant Database</h1>

    <!-- Button to toggle form display -->
    <button id="formToggleButton" onclick="toggleFormVisibility()">Add New Data</button>

    <!-- Form to add new plant data (initially hidden) -->
    <form id="plantForm" enctype="multipart/form-data" method="post" action="../process_form.php" style="display: none;">
        <div class="form-group">
            <label for="photo">Add Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="plantName">Common Name:</label>
            <input type="text" id="plantName" name="plantName" required>
        </div>
        <div class="form-group">
            <label for="scientificName">Scientific Name:</label>
            <input type="text" id="scientificName" name="scientificName" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="plasticSize">Plastic Size:</label>
            <select id="plasticSize" name="plasticSize" required>
                <option value="xsmall">X-Small</option>
                <option value="small">Small</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option>
                <option value="xlarge">X-Large</option>
            </select>
        </div>
        <label>Plant Type:</label>
        <div class="form-group checkbox-container">
            <div class="checkbox-item">
                <input type="checkbox" id="tree" name="plantType[]" value="tree">
                <label for="tree">Tree</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="shrub" name="plantType[]" value="shrub">
                <label for="shrub">Shrub</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="fern" name="plantType[]" value="fern">
                <label for="fern">Fern</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="climber" name="plantType[]" value="climber">
                <label for="climber">Climber</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="waterPlant" name="plantType[]" value="water_plant">
                <label for="waterPlant">Water Plant</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="palm" name="plantType[]" value="palm">
                <label for="palm">Palm</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="cactus" name="plantType[]" value="cactus">
                <label for="cactus">Cactus</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="succulent" name="plantType[]" value="succulent">
                <label for="succulent">Succulent</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="annual" name="plantType[]" value="annual">
                <label for="annual">Annual</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="perennial" name="plantType[]" value="perennial">
                <label for="perennial">Perennial</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="indoorPlant" name="plantType[]" value="indoor_plant">
                <label for="indoorPlant">Indoor Plant</label>
            </div>
            <div class="checkbox-item">
                <input type="checkbox" id="herb" name="plantType[]" value="herb">
                <label for="herb">Herb</label>
            </div>
        </div>
        <button type="submit">Add Plant</button>
    </form>

    <div class="plant-table-section">
        <h2>Herbs in Database</h2>
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Common Name</th>
                    <th>Scientific Name</th>
                    <th>Quantity</th>
                    <th>Plastic Size</th>
                    <th>Plant Type</th>
                    <th>plantation date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch data from the plants table
                // Fetch plant data where 'succulent' is among the plant types
$sql = "SELECT * FROM plants WHERE FIND_IN_SET('herb', REPLACE(plant_type, ', ', ',')) > 0";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='../uploads/" . htmlspecialchars($row['photo_path']) . "' alt='" . htmlspecialchars($row['plant_name']) . "' width='100'></td>";
                        echo "<td>" . htmlspecialchars($row['plant_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['scientific_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['plastic_size']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['plant_type']) . "</td>";
                        echo '<td>' . htmlspecialchars($row['plantation_date']) . '</td>';
                        echo "<td>
                                <a href='../edit.php?id=" . $row['id'] . "' class='actionButton'>Edit</a>
                                <a href='?action=delete&id=" . $row['id'] . "' class='actionButton deleteButton' onclick='return confirm(\"Are you sure you want to delete this plant?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No plants found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleFormVisibility() {
        var form = document.getElementById("plantForm");
        if (form.style.display === "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }
</script>

</body>
</html>
