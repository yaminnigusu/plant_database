<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Jardin-about</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="../database\styles.css">
    <link rel="stylesheet" href="styles.css">

    <style>
        
    </style>
</head>

<body  class="w3-light-gray">
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


    
        
       
    <!-- ... (previous code) ... -->

    <section id="contact" class="mt-4">
    <div class="container">
        <h3>Contact Us</h3>
        <div class="row">
            <div class="col-md-6">
                <p><a href="tel:0940384999">+251940384999</a></p>
                <p><a href="mailto:nigusuyamin@gmail.com">nigusuyamin@gmail.com</a></p>
                <p>Address: Addis Ababa, Bole Japan</p>
            </div>
            <div class="col-md-6 text-center">
                <a href="https://www.facebook.com/lejardindekakoo?mibextid=ZbWKwL"><i class="fab fa-facebook fa-2x mr-3"></i></a>
                <a href="https://www.instagram.com/lejardindekakoo?igsh=MWNzdWhwb3EwNDYwMw=="><i class="fab fa-instagram fa-2x mr-3"></i></a>
                <a href="https://t.me/LeGiardinDeKakoo"><i class="fab fa-telegram fa-2x mr-3"></i></a>
                <a href="https://wa.me/message/47J4IQW4KT3SL1"><i class="fab fa-whatsapp fa-2x mr-3"></i></a>
                <a href="https://www.tiktok.com/@plantparadise25?_t=8imWxp8efJd&_r=1"><i class="fab fa-tiktok fa-2x mr-3"></i></a>
            </div>
        </div>
    </div>
</section>

    

    <footer>
        <div class="container">
            <p>&copy; 2024 Le Jardin de kakoo</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
