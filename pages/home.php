<?php
include("../database/config.php"); 

// Fetch total quantity of plants
$sql = "SELECT SUM(quantity) AS totalQuantity FROM plants";
$result = $conn->query($sql);

$totalQuantity = 0; // Initialize variable

if ($result && $row = $result->fetch_assoc()) {
    // Round down the total quantity to the nearest 500
    $totalQuantity = floor($row['totalQuantity'] / 500) * 500;
}

// Fetch total plants cultivated this year
$sqlThisYear = "SELECT SUM(quantity) AS totalPlantsThisYear FROM plants WHERE YEAR(plantation_date) = YEAR(CURDATE())";
$resultThisYear = $conn->query($sqlThisYear);

$totalPlantsThisYear = 0; // Initialize variable

if ($resultThisYear && $rowThisYear = $resultThisYear->fetch_assoc()) {
    // Round down to the nearest 500
    $totalPlantsThisYear = floor($rowThisYear['totalPlantsThisYear'] / 500) * 500;
}

// Fetch total unique plant types
$sqlTypes = "SELECT COUNT(DISTINCT plant_name) AS totalPlantVarieties FROM plants";
$resultTypes = $conn->query($sqlTypes);

$totalPlantVarieties = 0; // Initialize variable

if ($resultTypes && $rowTypes = $resultTypes->fetch_assoc()) {
    $totalPlantVarieties = $rowTypes['totalPlantVarieties'];
}

// Initialize products array
$products = [];

// Fetch featured products data
$sql_featured = "SELECT * FROM plants WHERE is_featured = 1 LIMIT 3"; // Fetch only featured products
$result = $conn->query($sql_featured);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row; // Add each featured product to the array
    }
} else {
    echo "Error fetching featured products: " . $conn->error; // Optional: Show error message
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Jardin - Home</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
    <style>
        .about {
    background-image: url('../images/green.jpg'); /* Use a soft-focus background image */
    background-size: cover; 
    background-position: center;
    color: #fff; /* White text for contrast */
    padding: 60px 20px;
    text-align: center;
    position: relative;
    overflow: hidden; /* To handle any overflow from the background */
}

.about::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5); /* Dark overlay for better text readability */
    z-index: 1; /* Place it behind the content */
}

.about .container {
    position: relative; /* To position content above the overlay */
    z-index: 2; /* Bring the container above the overlay */
}

.about h3 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    text-transform: uppercase; /* Uppercase for emphasis */
    letter-spacing: 1px; /* Slightly increase spacing */
}

.about p {
    font-size: 1.2rem;
    line-height: 1.6;
    margin: 15px 0;
}

.about ul {
    list-style-type: none; 
    padding: 0;
    text-align: left; 
    max-width: 600px; 
    margin: 0 auto; 
    font-size: 1.1rem; /* Slightly smaller font for list */
}

.about ul li {
    margin-bottom: 15px; /* Space between list items */
    background: rgba(255, 255, 255, 0.2); /* Light background for list items */
    padding: 10px; /* Padding around list items */
    border-radius: 5px; /* Rounded corners */
}

.about .btn {
    margin-top: 20px;
    font-size: 1.2rem;
    background-color: #4CAF50; /* Custom green color */
    border: none; 
    color: white;
    padding: 10px 20px; /* Increased padding for the button */
    text-align: center;
    text-decoration: none;
    display: inline-block;
    border-radius: 5px; /* Rounded corners */
    transition: background-color 0.3s ease; /* Smooth transition on hover */
}

.about .btn:hover {
    background-color: #45a049; /* Darker shade on hover */
}
/* Contact Section Styling */
.contact-section {
    background: #f9f9f9;
    padding: 50px 0;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 2.5rem;
    color: #2f6132;
    text-align: center;
    margin-bottom: 40px;
    font-weight: bold;
}

.contact-info p {
    font-size: 1.2rem;
    margin-bottom: 15px;
    color: #333;
    display: flex;
    align-items: center;
}

.contact-info p i {
    color: #2f6132;
    margin-right: 10px;
}

.contact-info a {
    color: #2f6132;
    text-decoration: none;
}

.contact-info a:hover {
    text-decoration: underline;
}

.contact-social a {
    color: #2f6132;
    margin-right: 15px;
    transition: transform 0.2s ease;
}

.contact-social a:hover {
    transform: scale(1.1);
}

.faq-section {
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    margin-top: 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.faq-item h5 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2f6132;
    margin-bottom: 10px;
}

.faq-item p {
    font-size: 1.1rem;
    color: #555;
    line-height: 1.6;
}

.order-link {
    color: #2f6132;
    font-weight: bold;
    text-decoration: none;
}

.order-link:hover {
    text-decoration: underline;
}

.order-cta a {
    background-color: #2f6132;
    color: #fff;
    padding: 15px 30px;
    border-radius: 50px;
    transition: background-color 0.3s ease;
}

.order-cta a:hover {
    background-color: #205020;
}

/* Responsive Design */
@media (max-width: 768px) {
    .contact-social a {
        margin: 10px 0;
        font-size: 1.5rem;
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


    <!-- Hero Section -->
    <section class="hero">
        <h2>Welcome to Le Jardin de Kakoo!</h2>
    </section>

    <!-- General Information Section -->
    <section class="statistics">
    <div class="container">
        <h3 class="text-center mb-4">Our Nursery At A Glance</h3>
        <div class="row">
            <div class="col-md-4 stat-item">
                <h4>Total Plants</h4>
                <p class="stat-number"><?php echo $totalQuantity; ?>+</p>
            </div>
            <div class="col-md-4 stat-item">
                <h4>Plant Variety</h4>
                 <p class="stat-number"><?php echo $totalPlantVarieties; ?></p>
            </div>
            <div class="col-md-4 stat-item">
                <h4>Total Plants Cultivated This Year</h4>
                <p class="stat-number"><?php echo $totalPlantsThisYear; ?>+</p>
            </div>
        </div>
    </div>
</section>




<section class="products">
    <div class="container">
        <h3 class="text-center">Featured Products</h3>
        <div class="row justify-content-center">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 col-sm-6 col-12 text-center mb-4">
                        <img src="../database/uploads/<?php echo htmlspecialchars($product['photo_path']); ?>" 
                             alt="<?php echo htmlspecialchars($product['plant_name']); ?>" 
                             class="img-fluid" 
                             style="width: 100%; max-width: 300px; height: 200px; object-fit: cover;">
                        <h5 class="mt-3"><?php echo htmlspecialchars($product['plant_name']); ?></h5>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No featured products available at the moment.</p>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="shop.php" class="btn btn-success btn-lg" 
               style="padding: 15px 30px; font-size: 1.2rem; border-radius: 25px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); transition: background-color 0.3s;">
                Shop Now
            </a>
        </div>
    </div>
</section>




  <!-- About Us Section -->
<section class="about">
    <div class="container">
        <h3>About Us 🌱</h3>
        <p>Welcome to <strong>Le Jardin de Kakoo</strong>, your premier destination for a diverse range of plants! We believe that every home deserves a touch of greenery, and we are here to help you find the perfect plants to suit your style and needs.</p>
        <p>Our nursery is dedicated to providing:</p>
        <ul>
            <li>🌿 <strong>Quality Plants:</strong> From vibrant houseplants to majestic trees and delicate ferns, we ensure that all our plants are healthy and ready to thrive in your care.</li>
            <li>🛠️ <strong>Expert Guidance:</strong> Our knowledgeable team is passionate about gardening and is always available to provide tips and support for your gardening journey.</li>
            <li>🌍 <strong>Sustainable Practices:</strong> We are committed to eco-friendly practices, ensuring our plants are sourced responsibly and our operations are sustainable.</li>
        </ul>
        <p>Join us in building your green paradise! Whether you're a seasoned gardener or just starting out, we have everything you need to create a beautiful, thriving garden.</p>
        <a href="about.php" class="btn btn-primary">Discover More About Us</a>
    </div>
</section>




 <!-- Contact Section -->
<section id="contact" class="mt-4 contact-section">
    <div class="container">
        <h3 class="section-title">Contact Us</h3>
        <div class="row">
            <div class="col-md-6 contact-info">
                <p><i class="fas fa-phone-alt"></i> <a href="tel:0940384999">+251 940 384 999</a></p>
                <p><i class="fas fa-envelope"></i> <a href="mailto:nigusuyamin@gmail.com">nigusuyamin@gmail.com</a></p>
                <p><i class="fas fa-map-marker-alt"></i> Address: Addis Ababa, Bole Japan</p>
            </div>
            <div class="col-md-6 text-center contact-social">
                <a href="https://www.facebook.com/lejardindekakoo"><i class="fab fa-facebook fa-2x mr-3"></i></a>
                <a href="https://www.instagram.com/lejardindekakoo"><i class="fab fa-instagram fa-2x mr-3"></i></a>
                <a href="https://t.me/LeGiardinDeKakoo"><i class="fab fa-telegram fa-2x mr-3"></i></a>
                <a href="https://wa.me/message/47J4IQW4KT3SL1"><i class="fab fa-whatsapp fa-2x mr-3"></i></a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="faq-section mt-5">
            <h4>Frequently Asked Questions</h4>
            <div class="faq-item">
                <h5>How can I place an order?</h5>
                <p>You can place an order through our <a href="order_page.php" class="order-link">order form</a> or contact us via phone, email, or social media to discuss your needs.</p>
            </div>
            <div class="faq-item">
                <h5>What is your delivery area?</h5>
                <p>We deliver within Addis Ababa and the surrounding areas. For orders outside of Addis, please contact us to arrange special delivery options.</p>
            </div>
            <div class="faq-item">
                <h5>Do you offer bulk orders for events?</h5>
                <p>Yes, we specialize in providing plants for events like weddings, corporate functions, and more. Please reach out to discuss your requirements.</p>
            </div>
        </div>

        <!-- Order Link -->
        <div class="order-cta text-center mt-5">
            <a href="shop.php" class="btn btn-primary btn-lg">Place an Order</a>
        </div>
    </div>
</section>


   <!-- Footer Section -->
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


    <script src="../js/script.js"></script>
    <script>
        
        
        function toggleSidebar() {
    document.querySelector('header').classList.toggle('sidebar-open');
}

    </script>
</body>
</html>
