<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Jardin-Plant Database</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" href="../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Custom CSS for specific styling */
        .checkbox-item {
            margin-right: 20px;
        }

        .photo-cell img {
            max-width: 100px;
            max-height: 100px;
        }

        .total-info {
            margin-top: 20px; /* Add space above the total info section */
            text-align: center; /* Center-align the content */
            background-color: #f0f0f0; /* Background color for the total info section */
            padding: 15px; /* Add padding around the content */
        }

        .total-info p {
            font-size: 18px; /* Increase font size for total quantity and total value */
            margin-bottom: 10px; /* Add space below each paragraph */
        }

        .submenu {
            display: none;
        }
    </style>
</head>

<body class="w3-light-gray">
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
                    <button id="login-icon" onclick="toggleLoginForm()" aria-label="Login" class="btn btn-success">Login</button>
                </div>
            </nav>
        </div>
    </header>

    <aside class="side-nav" id="sideNav">
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
        </ul>
    </aside>

    <div class="main-content">
        <div class="container">
            <h1 class="mt-4">Plant Database</h1>
            <button id="formToggleButton" onclick="toggleFormVisibility()" class="btn btn-primary mb-4">Add New Data</button>

            <form id="plantForm" enctype="multipart/form-data" method="post" action="process_form.php" style="display: none;">
                <div class="form-group">
                    <input type="checkbox" id="optionalData" name="optionalData" value="1" onchange="toggleOptionalFields()">
                    <label for="optionalData">Add Optional Data</label>
                </div>
                <div class="form-group" id="photoGroup">
                    <label for="photo">Add Photo:</label>
                    <input type="file" id="photo" name="photo" accept="image/*" class="form-control">
                </div>
                <div class="form-group" id="plantNameGroup">
                    <label for="plantName">Common Name:</label>
                    <input type="text" id="plantName" name="plantName" class="form-control">
                </div>
                <div class="form-group" id="scientificNameGroup">
                    <label for="scientificName">Scientific Name:</label>
                    <input type="text" id="scientificName" name="scientificName" class="form-control">
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control">
                </div>
                <div class="form-group">
                    <label for="plasticSize">Plastic Size:</label>
                    <select id="plasticSize" name="plasticSize" class="form-control">
                        <option value="xsmall">X-Small</option>
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                        <option value="xlarge">X-Large</option>
                    </select>
                </div>

                <label>Plant Type:</label><br>
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
                        <input type="checkbox" id="indoorPlant" name="plantType[]" value="indoorplant">
                        <label for="indoorPlant">Indoor Plant</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="herb" name="plantType[]" value="herb">
                        <label for="herb">Herb</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="plantationDate">Plantation Date:</label>
                    <input type="date" id="plantationDate" name="plantationDate" class="form-control">
                </div>
                <div class="form-group">
                    <label for="value">Value:</label>
                    <input type="number" id="value" name="value" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>

            <div class="table-responsive">
            <?php
include("config.php");

// Handle record deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the DELETE query using a parameterized statement to prevent SQL injection
    $sql_delete = "DELETE FROM plants WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id); // "i" indicates integer parameter type

    if ($stmt->execute()) {
        echo '<p class="success-message">Record deleted successfully</p>';
    } else {
        echo '<p class="error-message">Error deleting record: ' . $conn->error . '</p>';
    }

    $stmt->close(); // Close the prepared statement
}

$records_per_page = 15;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

$sql_total = "SELECT COUNT(*) FROM plants";
$result_total = $conn->query($sql_total);
$total_records = $result_total->fetch_row()[0];
$total_pages = ceil($total_records / $records_per_page);

$sql = "SELECT * FROM plants LIMIT $records_per_page OFFSET $offset";
$result = $conn->query($sql);
$totalQuantity = 0;
$totalValue = 0;

if ($result->num_rows > 0) {
    echo '<table id="plantTable">';
    echo '<thead><tr><th>Photo</th><th>Common Name</th><th>Scientific Name</th><th>Quantity</th><th>Plastic Size</th><th>Plantation Date</th><th>Plant Type</th><th>Value</th><th>Actions</th></tr></thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td class="photo-cell"><img src="uploads/' . htmlspecialchars($row['photo_path']) . '" alt="' . htmlspecialchars($row['plant_name']) . '"></td>';
        echo '<td>' . htmlspecialchars($row['plant_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['scientific_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
        echo '<td>' . htmlspecialchars($row['plastic_size']) . '</td>';
        echo '<td>' . htmlspecialchars($row['plantation_date']) . '</td>';
        echo '<td>';
        $plantTypes = explode(', ', $row['plant_type']);
        foreach ($plantTypes as $type) {
            echo htmlspecialchars($type) . '<br>';
        }
        echo '</td>';
        echo '<td>' . htmlspecialchars($row['value']) . '</td>';
        echo '<td class="action-buttons">';
        echo '<a class="actionButton editButton" href="edit.php?id=' . $row['id'] . '">Edit</a>';
        echo '<a class="actionButton deleteButton" href="database.php?action=delete&id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this record?\')">Delete</a>';
        echo '</td>';
        echo '</tr>';
        $totalQuantity += intval($row['quantity']);
        $totalValue += intval($row['value']);
    }

    echo '</tbody></table>';
    echo '<div class="total-info">';
    echo '<p>Total Quantity: ' . $totalQuantity . '</p>';
    echo '<p>Total Value: ' . $totalValue . '</p>';
    echo '</div>';
} else {
    echo '<p>No plant records found</p>';
}

$result->close();
$conn->close();
?>

            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($current_page > 1): ?>
                        <li class="page-item"><a class="page-link" href="database.php?page=<?php echo $current_page - 1; ?>">Previous</a></li>
                    <?php endif; ?>

                    <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                        <li class="page-item <?php if ($page == $current_page) echo 'active'; ?>">
                            <a class="page-link" href="database.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="database.php?page=<?php echo $current_page + 1; ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script>
            function toggleFormVisibility() {
                const plantForm = document.getElementById('plantForm');
                if (plantForm.style.display === 'none') {
                    plantForm.style.display = 'block';
                } else {
                    plantForm.style.display = 'none';
                }
            }

            function toggleOptionalFields() {
                const isOptional = document.getElementById('optionalData').checked;
                document.getElementById('photo').required = !isOptional;
                document.getElementById('photoGroup').style.display = isOptional ? 'none' : 'block';
                document.getElementById('scientificNameGroup').style.display = isOptional ? 'none' : 'block';
                document.getElementById('plantName').required = !isOptional;
                document.getElementById('quantity').required = !isOptional;
                document.getElementById('plasticSize').required = !isOptional;
                document.getElementById('plantationDate').required = !isOptional;
                document.getElementById('value').required = !isOptional;
            }
        </script>
        <script>
            function toggleNavVisibility() {
                const sideNav = document.getElementById('sideNav');
                sideNav.classList.toggle('open');
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../js/script2.js"></script>
    </body>

    </html>
