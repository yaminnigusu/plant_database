<?php
session_start();

include("../database/config.php"); // Include database configuration

// Calculate total costs from the 'costs' table
$sql_total_costs = "SELECT SUM(cost_amount) AS totalCosts FROM costs";
$result_total_costs = $conn->query($sql_total_costs);
$totalCosts = $result_total_costs->fetch_assoc()['totalCosts'] ?? 0; // Total costs from 'costs' table

// Calculate total quantity of all plants
$sql_total_quantity = "SELECT SUM(quantity) AS totalQuantity FROM plants";
$result_total_quantity = $conn->query($sql_total_quantity);
$totalQuantity = $result_total_quantity->fetch_assoc()['totalQuantity'] ?? 0; // Total quantity of all plants

// Calculate additional cost per plant by evenly distributing the total costs
$additionalCostPerPlant = ($totalCosts > 0 && $totalQuantity > 0) ? ($totalCosts / $totalQuantity) : 0;

// Desired profit margin (adjust as needed)
$profitMargin = 2.5; // 20% profit margin

// Retrieve search term, selected plant types, and price range from URL parameters or session
$searchTerm = $_GET['search'] ?? '';
$selectedPlantType = $_GET['plantType'] ?? '';
$selectedPriceRange = $_GET['priceRange'] ?? '';

// If parameters are empty in URL, reset session variables
if (empty($searchTerm) && empty($selectedPlantType) && empty($selectedPriceRange)) {
    unset($_SESSION['search']);
    unset($_SESSION['plantType']);
    unset($_SESSION['priceRange']);
} else {
    // Store query parameters in session for future reference
    $_SESSION['search'] = $searchTerm;
    $_SESSION['plantType'] = $selectedPlantType;
    $_SESSION['priceRange'] = $selectedPriceRange;
}

// Initialize $availablePlantTypes array
$availablePlantTypes = [];

// Construct SQL query to fetch distinct plant types
$plantTypeSql = "SELECT DISTINCT plant_type FROM plants";
$plantTypeResult = $conn->query($plantTypeSql);

if ($plantTypeResult && $plantTypeResult->num_rows > 0) {
    // Populate $availablePlantTypes array with fetched plant types
    while ($row = $plantTypeResult->fetch_assoc()) {
        $availablePlantTypes[] = $row['plant_type'];
    }
}

// Pagination settings
$itemsPerPage = 15; // Number of items to display per page
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Construct SQL query to fetch total count of plants
$countSql = "SELECT COUNT(*) AS total FROM plants WHERE 1=1";

// Add search conditions based on the search term
if (!empty($searchTerm)) {
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    $countSql .= " AND (plant_name LIKE '%$searchTerm%' OR scientific_name LIKE '%$searchTerm%' OR plant_type LIKE '%$searchTerm%')";
}

// Add plant type filter based on selected type
if (!empty($selectedPlantType)) {
    $selectedPlantType = mysqli_real_escape_string($conn, $selectedPlantType);
    $countSql .= " AND plant_type LIKE '%$selectedPlantType%'";
}

// Execute SQL query to fetch total count of plants
$countResult = $conn->query($countSql);
$totalRecords = $countResult->fetch_assoc()['total'];

// Calculate total number of pages
$totalPages = ceil($totalRecords / $itemsPerPage);

$sql = "
SELECT 
    id, 
    plant_name, 
    scientific_name,
    SUM(quantity) AS total_quantity, 
    GROUP_CONCAT(DISTINCT photo_path) AS photo_paths,  -- Use GROUP_CONCAT to get all photo paths
    GROUP_CONCAT(DISTINCT plant_type) AS plant_types, 
    AVG(value) AS value
FROM plants 
WHERE 1=1
";

// Add search conditions based on the search term
if (!empty($searchTerm)) {
    $sql .= " AND (plant_name LIKE '%$searchTerm%' OR scientific_name LIKE '%$searchTerm%' OR plant_type LIKE '%$searchTerm%')";
}

// Add plant type filter based on selected type
if (!empty($selectedPlantType)) {
    $sql .= " AND plant_type LIKE '%$selectedPlantType%'";
}

// Add price range filter based on selected range
if (!empty($selectedPriceRange)) {
    list($minPrice, $maxPrice) = explode('-', $selectedPriceRange);
    $sql .= " AND (value / quantity) BETWEEN $minPrice AND $maxPrice";
}

// Group by plant_name and scientific_name
$sql .= " GROUP BY plant_name, scientific_name LIMIT $itemsPerPage OFFSET $offset";

// Execute SQL query to fetch plants
$result = $conn->query($sql);

// Initialize $plants array
$plants = [];

if ($result && $result->num_rows > 0) {
    // Populate $plants array with fetched plant data
    while ($row = $result->fetch_assoc()) {
        $plantTypes = explode(',', $row['plant_types']);
        $costPerPlant = $row['value'] / $row['total_quantity'];

        // Calculate selling price including additional cost and profit 
        $costWithAdditional = $costPerPlant + $additionalCostPerPlant;
        $sellingPrice = $costWithAdditional + ($costWithAdditional * 1.5);

        // Calculate 65% of the total quantity
        $reducedQuantity = (int)($row['total_quantity'] * 0.65); // Get 65% of the total quantity

        // Create plant array with required data, including ID and reduced quantity
        $plants[] = [
            'id' => $row['id'], // Include the ID
            'plant_name' => htmlspecialchars($row['plant_name']),
            'photo_path' => htmlspecialchars($row['photo_paths']), // Updated this line to store concatenated paths
            'quantity' => $reducedQuantity, // Use reduced quantity here
            'plant_type' => $plantTypes,
            'sellingPrice' => $sellingPrice, // Adjusted selling price
        ];
    }
}

$conn->close();
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Le Jardin Shop</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="icon" href="../images/logo.png" type="image/jpg">
        <link rel="stylesheet" href="styles.css">
        <style>
            .container {
        flex: 1; /* Allows the container to grow and fill the available space */
    }
            /* Custom styles for card hover effects */
            .card {
                transition: all 0.3s ease;
            }
            .card:hover {
                transform: scale(1.05);
                box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            }
            .card img {
                height: 200px;
                object-fit: cover;
                border-bottom: 2px solid #f0f0f0;
            }
            .price-text {
                color: #28a745;
                font-size: 1.2rem;
                font-weight: bold;
            }
            .sidebar {
                background-color: #f8f9fa;
                padding: 20px;
                border-radius: 5px;
            }
            .sidebar a {
                display: block;
                margin-bottom: 10px;
                color: #000;
            }
            .sidebar a:hover {
                color: #28a745;
            }
            .pagination {
                margin-top: 20px;
            }
            @media (max-width: 768px) {
                .card {
                    margin-bottom: 20px;
                }
            }
            @media (max-width: 768px) {
        .form-inline {
            width: 100%; /* Make the form full width on small screens */
        }

        .form-control {
            flex: 1; /* Allow the input field to grow */
            min-width: 0; /* Prevent overflow */
        }

        .btn {
            width: 25%; /* Make buttons full width on small screens */
        }
    }

    /* Footer Styling */
    .footer {
        background-color: #2f6132;
        color: #fff;
        padding: 50px 0;
        font-family: 'Arial', sans-serif;
    }

    .footer .footer-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #fff;
        margin-bottom: 15px;
    }

    .footer p {
        font-size: 1rem;
        line-height: 1.6;
        color: #ddd;
    }

    .footer-links {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 10px;
    }

    .footer-links a {
        color: #fff;
        text-decoration: none;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: #c5e1a5; /* Light green hover effect */
    }

    .footer-social a {
        color: #fff;
        font-size: 1.5rem;
        margin-right: 15px;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .footer-social a:hover {
        color: #c5e1a5;
        transform: scale(1.2); /* Slight scale effect on hover */
    }

    .footer-bottom {
        margin-top: 30px;
        border-top: 1px solid #c5e1a5;
        padding-top: 15px;
    }

    .footer-bottom p {
        margin: 0;
        font-size: 0.9rem;
        color: #ccc;
    }

    .footer-bottom a {
        color: #c5e1a5;
        text-decoration: none;
    }

    .footer-bottom a:hover {
        text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .footer-title {
            font-size: 1.3rem;
        }

        .footer-social a {
            font-size: 1.3rem;
        }
    }
    .modal-backdrop {
        display: none !important; /* Force the backdrop to be hidden */
    }
    #abc {
        width: 100%;
    }
    /* General Card Styles */
    .card {
        transition: transform 0.2s;
        border-radius: 10px;
        overflow: hidden; /* Prevent overflow of images */
    }

    .card:hover {
        transform: scale(1.05); /* Scale up on hover */
    }

    .slider {
        position: relative;
        overflow: hidden;
    }

    .slides {
        display: flex;
        transition: transform 0.5s ease;
        width: 100%;
    }

    .slide {
        min-width: 100%; /* Each slide takes full width */
    }

    .prev, .next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(255, 255, 255, 0.7);
        border: none;
        padding: 10px;
        cursor: pointer;
        z-index: 1;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    .prev {
        left: 10px;
    }

    .next {
        right: 10px;
    }

    /* Button Styles */
    .btn-outline-success {
        border-color: #28a745; /* Customize the border color */
    }

    .btn-outline-success:hover {
        background-color: #28a745; /* Background color on hover */
        color: #fff; /* Text color on hover */
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .card {
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.25rem; /* Adjust title size for mobile */
        }

        .price-text {
            font-weight: bold; /* Bold price text for emphasis */
        }

        .prev, .next {
            padding: 8px;
        }
    }

    @media (min-width: 769px) {
        .card-title {
            font-size: 1.5rem; /* Adjust title size for desktop */
        }
    }

    .suggestions-list {
                background: white; /* White background for suggestions */
                border: 1px solid #ccc; /* Border for suggestions */
                border-radius: 0 0 4px 4px; /* Rounded corners at the bottom */
                max-height: 200px; /* Max height for scrolling */
                overflow-y: auto; /* Scroll for overflow */
                z-index: 1000; /* Above other elements */
            }

            /* Individual suggestion item styles */
            .suggestion-item {
                padding: 10px; /* Padding for suggestion items */
                cursor: pointer; /* Pointer on hover */
                transition: background-color 0.3s ease; /* Smooth transition */
            }

            /* Hover effect for suggestion items */
            .suggestion-item:hover {
                background-color: #f0f8ff; /* Light blue background on hover */
            }


        </style>
    </head>

    <body>
    <header class="header sticky-top p-3 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="logo">Le Jardin de Kakoo</h1>
            <!-- Mobile Menu Toggle Button -->
            <button id="navToggleButton" onclick="toggleSidebar()" class="btn btn-light d-lg-none">
                <i class="fas fa-bars"></i> Menu
            </button>
            <!-- Desktop Navigation -->
            <nav class="d-none d-lg-flex">
                <a href="../pages/home.php" class="nav-link">Home</a>
                <a href="../pages/shop.php" class="nav-link">Shop</a>
                <a href="../pages/about.php" class="nav-link">About Us</a>
                <a href="../pages/contactus.php" class="nav-link">Contact Us</a>
                
            </nav>
        </div>
        <!-- Mobile Sidebar Navigation -->
        <nav id="sidebar" class="mobile-sidebar d-lg-none">
            <a href="../pages/home.php" class="nav-link">Home</a>
            <a href="../pages/shop.php" class="nav-link">Shop</a>
            <a href="../pages/about.php" class="nav-link">About Us</a>
            <a href="../pages/contactus.php" class="nav-link">Contact Us</a>
            
        </nav>
    </header>

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="container">
        <!-- Toggle Button for Sidebar -->
    

        <div class="row mt-3">
            <!-- Sidebar -->
            <div class="col-md-3 mb-4 collapse d-md-block" id="sidebar">
                <div class="sidebar border rounded p-3">
                    <h4 class="text-center">Categories</h4>
                    <hr>
                <!-- Available Plant Types -->
    <div class="list-group">
        <!-- Link to return to the default shop page -->
        <a href="shop.php" class="list-group-item list-group-item-action text-primary">
        <i class="fas fa-th-list"></i> View All Plants
    </a>

        <?php foreach ($availablePlantTypes as $plantType) : ?>
            <a href="shop.php?plantType=<?= urlencode($plantType); ?>" class="list-group-item list-group-item-action">
                <?= htmlspecialchars($plantType); ?>
            </a>
        <?php endforeach; ?>
    </div>


                    <h4 class="mt-4 text-center">Price Range</h4>
                    <hr>
                    <form method="GET" action="shop.php" class="mb-3">
                        <label for="priceRange" class="form-label">Select a price range:</label>
                        <select id="priceRange" name="priceRange" class="form-select" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="0-10" <?= $selectedPriceRange == '0-10' ? 'selected' : ''; ?>>0 - 10</option>
                            <option value="10-50" <?= $selectedPriceRange == '10-50' ? 'selected' : ''; ?>>10 - 50</option>
                            <option value="50-100" <?= $selectedPriceRange == '50-100' ? 'selected' : ''; ?>>50 - 100</option>
                            <option value="100-500" <?= $selectedPriceRange == '100-500' ? 'selected' : ''; ?>>100 - 500</option>
                        </select>
                    </form>
                </div>
            </div>

        <!-- Main Content -->
    <div class="col-md-9">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h2 class="mb-4">Shop Plants</h2>
            <div class="container mt-4 position-relative"> <!-- Added position-relative to the container -->
        <form method="GET" class="form-inline" id="searchForm">
            <div class="input-group w-100">
                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search..." value="<?= htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-outline-success" aria-label="Search">
                    <i class="bi bi-search"></i> <!-- Bootstrap Icon -->
                </button>
            </div>
            <div id="suggestions" class="suggestions-list mt-2"></div> <!-- Suggestions below the form -->
        </form>
    </div>


            <br>
            <button id="abc" class="btn btn-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-expanded="false" aria-controls="sidebar">
                +
            </button>
            <br>
        </div>

        <div class="row">
    <?php if (count($plants) > 0) : ?>
        <?php foreach ($plants as $plant) : ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card shadow border-0 rounded">
                    <div class="slider" id="slider-<?= $plant['id']; ?>">
                        <div class="slides">
                            <?php 
                            // Split the photo_paths into an array of unique image paths
                            $photos = explode(',', $plant['photo_path']); // Now this uses the concatenated photo paths

$uniquePhotos = array_unique(array_map('trim', $photos)); // Remove duplicates and trim spaces

foreach ($uniquePhotos as $photo): 
?>
    <div class="slide">
        <img src="<?= '../database/uploads/' . htmlspecialchars($photo); ?>"
             alt="<?= htmlspecialchars($plant['plant_name']); ?>"
             class="card-img-top img-fluid"
             style="max-height: 300px; object-fit: contain;"
             onclick="openModal(<?= json_encode($uniquePhotos); ?>)">
    </div>
<?php endforeach; ?>
                        </div>
                        <button class="prev" onclick="changeSlide(<?= $plant['id']; ?>, -1)">&#10094;</button>
                        <button class="next" onclick="changeSlide(<?= $plant['id']; ?>, 1)">&#10095;</button>
                    </div>

                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($plant['plant_name']); ?></h5>
                        <p class="card-text">Quantity: <?= $plant['quantity']; ?></p>
                        <p class="price-text">Price: <?= number_format($plant['sellingPrice'], 2); ?> Birr</p>
                        <div class="text-center">
                            <a href="order_form.php?plant_id=<?= $plant['id']; ?>&plant_name=<?= urlencode($plant['plant_name']); ?>&selling_price=<?= $plant['sellingPrice']; ?>" 
                               class="btn btn-outline-success">
                                Order Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center">
            <p>No plants available at the moment.</p>
        </div>
    <?php endif; ?>
</div>



          <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="shop.php?page=1" aria-label="First">
                        <span aria-hidden="true">&laquo;&laquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="shop.php?page=<?= $currentPage - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Page number links -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $currentPage ? 'active' : ''; ?>">
                    <a class="page-link" href="shop.php?page=<?= $i; ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="shop.php?page=<?= $currentPage + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="shop.php?page=<?= $totalPages; ?>" aria-label="Last">
                        <span aria-hidden="true">&raquo;&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

<!-- Quick View Modal -->

<footer class="footer text-center">
            <div class="container">
                <div class="row">
                    <!-- Company Info -->
                    <div class="col-md-4 mb-4">
                        <h5 class="footer-title">LE JARDIN DE KAKOO</h5>
                        <p>Your trusted plant nursery for premium quality plants and gardening supplies.</p>
                    </div>
                    <!-- Quick Links -->
                    <div class="col-md-4 mb-4">
                        <h5 class="footer-title">Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="shop.php">Shop</a></li>
                            <li><a href="contact.php">Contact Us</a></li>
                        
                        </ul>
                    </div>
                    <!-- Social Media -->
                    <div class="col-md-4 mb-4">
                        <h5 class="footer-title">Follow Us</h5>
                        <div class="footer-social">
                            <a href="https://www.facebook.com/lejardindekakoo"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/lejardindekakoo"><i class="fab fa-instagram"></i></a>
                            <a href="https://t.me/LeGiardinDeKakoo"><i class="fab fa-telegram-plane"></i></a>
                            <a href="https://wa.me/message/47J4IQW4KT3SL1"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Copyright & Legal -->
                <div class="footer-bottom mt-4">
                    <p>&copy; 2024 LE JARDIN DE KAKOO. All Rights Reserved.</p>
                    <p><a href="#" class="text-white">Privacy Policy</a> | <a href="#" class="text-white">Terms of Service</a></p>
                </div>
            </div>
        </footer>

    </div>

  
    <!-- Footer Section -->
    

    
    <!-- Modal -->


        <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#searchInput').on('input', function() {
                let searchTerm = $(this).val();
                if (searchTerm.length > 0) {
                    $.ajax({
                        url: 'get_suggestions.php', // Your PHP file to get suggestions
                        type: 'GET',
                        data: { search: searchTerm },
                        success: function(data) {
                            $('#suggestions').html(data);
                        }
                    });
                } else {
                    $('#suggestions').empty();
                }
            });

            // Handle click on suggestion item
            $(document).on('click', '.suggestion-item', function() {
                $('#searchInput').val($(this).text());
                $('#suggestions').empty();
            });
        });
    </script>


    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            if (sidebar.classList.contains("d-none")) {
                sidebar.classList.remove("d-none");
            } else {
                sidebar.classList.add("d-none");
            }
        }
    </script>
    <script>
            
            
            function toggleSidebar() {
        document.querySelector('header').classList.toggle('sidebar-open');
    }

        </script>
    <script>
        // Track current slide index for each slider
        const slideIndex = {};

        function changeSlide(sliderId, direction) {
            const slider = document.getElementById('slider-' + sliderId);
            const slides = slider.querySelector('.slides');
            const totalSlides = slides.children.length;

            // Initialize slideIndex for this slider if not already set
            if (!slideIndex[sliderId]) {
                slideIndex[sliderId] = 0;
            }

            // Update the slide index
            slideIndex[sliderId] += direction;

            // Ensure the slide index wraps around correctly
            if (slideIndex[sliderId] >= totalSlides) {
                slideIndex[sliderId] = 0; // Go to the first slide
            } else if (slideIndex[sliderId] < 0) {
                slideIndex[sliderId] = totalSlides - 1; // Go to the last slide
            }

            // Move the slides container to show the current slide
            const slideWidth = slider.querySelector('.slide').offsetWidth;
            slides.style.transform = `translateX(${-slideIndex[sliderId] * slideWidth}px)`;
        }
    </script>

        <!-- Include jQuery -->
    
    </body>

    </html>
