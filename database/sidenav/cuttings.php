<?php
include("../config.php");

function fetchCuttingsData($conn, $search = '', $plasticSizeFilter = '', $startDate = '', $endDate = '') {
    $sql = "SELECT * FROM optional_plants WHERE 1=1";

    if ($search) {
        $sql .= " AND plant_name LIKE '%" . $conn->real_escape_string($search) . "%'";
    }

    if ($plasticSizeFilter) {
        $sql .= " AND plastic_size = '" . $conn->real_escape_string($plasticSizeFilter) . "'";
    }

    if ($startDate) {
        $sql .= " AND plantation_date >= '" . $conn->real_escape_string($startDate) . "'";
    }

    if ($endDate) {
        $sql .= " AND plantation_date <= '" . $conn->real_escape_string($endDate) . "'";
    }

    $result = $conn->query($sql);
    return $result;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Jardin-Plant Database</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" href="../../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="../styles.css">
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
            margin-top: 20px;
            text-align: center;
            background-color: #f0f0f0;
            padding: 15px;
        }

        .total-info p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .submenu {
            display: none;
        }

        #searchFilterForm {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        #searchFilterForm input[type="text"],
        #searchFilterForm select,
        #searchFilterForm input[type="date"],
        #searchFilterForm button {
            flex: 1;
            min-width: 150px;
        }

        #searchFilterForm label {
            margin-right: 5px;
        }

        .action-buttons a {
            margin-right: 10px;
        }

        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="w3-light-gray">
    <header class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col"></div>
                <div class="col-left">
                    <img src="../../images/logo.png" alt="Logo" width="50">
                </div>
                <h1>Le Jardin de Kakoo</h1>
            </div>
            <nav>
                <a href="../../pages/home.php">Home</a>
                <a href="../../pages/shop.php">Shop</a>
                <a href="../../pages/about.php">About Us</a>
                <a href="../../pages/contactus.php">Contact Us</a>
                <a href="../database.php">Database</a>
                <div class="col-auto">
                    <button id="login-icon" onclick="toggleLoginForm()" aria-label="Login" class="btn btn-success">Login</button>
                </div>
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
            <li><a href="cuttings.php"><b>Cuttings</b></a></li>
            <li><a href="../plan/plan.php"><b>Plan</a></li>
            <li><a href="../cost/cost.php"><b>Cost and Analytics</b></a></li>
        </ul>
    </aside>

    <div class="main-content">
        <div class="container">
            <h1 class="mt-4">Plant Database</h1>
            <button id="formToggleButton" onclick="toggleFormVisibility()" class="btn btn-primary mb-4">Add New Data</button>

            <form id="plantForm" enctype="multipart/form-data" method="post" action="../process_form.php" style="display: none;">
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
                    <label for="value">Cost:</label>
                    <input type="number" id="value" name="value" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>

            <!-- Search and Filter Form -->
            <form id="searchFilterForm" method="get" action="cuttings.php" class="form-inline my-4">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by Common Name">
                <select name="plasticSizeFilter" class="form-control mr-2">
                    <option value="">All Plastic Sizes</option>
                    <option value="xsmall">X-Small</option>
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                    <option value="xlarge">X-Large</</option>
                </select>
                <label for="startDate" class="mr-2">Start Date:</label>
                <input type="date" id="startDate" name="startDate" class="form-control mr-2">
                <label for="endDate" class="mr-2">End Date:</label>
                <input type="date" id="endDate" name="endDate" class="form-control mr-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>

            <div class="table-responsive">
                <h1 class="mt-4">Cuttings Data Display</h1>
                <div class="table-responsive">
                    <?php
                    // Handle record deletion
                    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
                        $id = $_GET['id'];

                        // Prepare the DELETE query using a parameterized statement to prevent SQL injection
                        $sql_delete = "DELETE FROM optional_plants WHERE id = ?";
                        $stmt = $conn->prepare($sql_delete);
                        $stmt->bind_param("i", $id); // "i" indicates integer parameter type

                        if ($stmt->execute()) {
                            echo '<p class="success-message">Record deleted successfully</p>';
                        } else {
                            echo '<p class="error-message">Error deleting record: ' . $conn->error . '</p>';
                        }

                        $stmt->close(); // Close the prepared statement
                    }

                    // Get search and filter parameters
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $plasticSizeFilter = isset($_GET['plasticSizeFilter']) ? $_GET['plasticSizeFilter'] : '';
                    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
                    $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';

                    $result = fetchCuttingsData($conn, $search, $plasticSizeFilter, $startDate, $endDate);

                    if ($result->num_rows > 0) {
                        echo '<table class="table table-bordered">';
                        echo '<thead><tr><th>Common Name</th><th>Quantity</th><th>Plastic Size</th><th>Plantation Date</th><th>Cost</th><th>Actions</th></tr></thead>';
                        echo '<tbody>';

                        $totalQuantity = 0;
                        $totalValue = 0;

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['plant_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['plastic_size']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['plantation_date']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['value']) . '</td>';
                            echo '<td class="action-buttons">';
                            echo '<a class="actionButton editButton" href="../edit_optional_plants.php?id=' . $row['id'] . '">Edit</a>';
                            echo '<a class="actionButton deleteButton" href="cuttings.php?action=delete&id=' . $row['id'] . '">Delete</a>';
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
                        echo '<p>No cutting records found</p>';
                    }

                    // Close result set and database connection
                    $result->close();
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
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
