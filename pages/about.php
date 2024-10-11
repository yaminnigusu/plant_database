<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Jardin - About Us</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="../database/styles.css">
    <link rel="stylesheet" href="styles.css">

    <style>
        /* Hero Section is replaced by the gallery */
       /* Enhanced Gallery Section */
.gallery-section {
    padding: 50px 0;
}

.carousel-item {
    position: relative;
}

.carousel-item img {
    height: 500px;
    object-fit: cover;
    border-radius: 10px;
    transition: transform 0.3s ease-in-out;
}

.carousel-item:hover img {
    transform: scale(1.05); /* Slight zoom effect on hover */
}

.carousel-caption {
    position: absolute;
    bottom: 20%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    background-color: rgba(0, 0, 0, 0.5); /* Transparent black background */
    padding: 15px;
    border-radius: 10px;
}

.carousel-caption h5 {
    font-size: 1.75rem;
    font-weight: bold;
    color: #fff;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6); /* Adding text shadow for better readability */
}

.carousel-caption p {
    font-size: 1.1rem;
    color: #fff;
    margin-top: 10px;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: #28a745; /* Green background for carousel controls */
    border-radius: 50%;
    padding: 10px;
    transition: background-color 0.3s ease;
}

.carousel-control-prev-icon:hover,
.carousel-control-next-icon:hover {
    background-color: #1e7e34; /* Darker green on hover */
}

/* Responsive Design for Smaller Screens */
@media (max-width: 768px) {
    .carousel-item img {
        height: 300px;
    }

    .carousel-caption h5 {
        font-size: 1.5rem;
    }

    .carousel-caption p {
        font-size: 1rem;
    }
}


      /* Enhanced About Us Section */
.about-section {
    padding: 50px 0;
    background-color: #f9f9f9; /* Light background for contrast */
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); /* Soft shadow */
}

.about-intro {
    font-size: 1.2rem;
    color: #333;
    line-height: 1.7;
    max-width: 800px;
    margin: 0 auto;
    padding-bottom: 30px;
    font-weight: 300;
}

.about-values {
    display: flex;
    justify-content: space-around;
    align-items: center;
    text-align: center;
}

.value-box {
    width: 30%;
    padding: 20px;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.value-box:hover {
    transform: translateY(-10px); /* Lifting effect on hover */
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.value-box i {
    font-size: 3rem;
    color: #28a745; /* Green color for icons to match branding */
    margin-bottom: 15px;
}

.value-box h4 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
}

.value-box p {
    font-size: 1rem;
    color: #666;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .about-values {
        flex-direction: column;
    }

    .value-box {
        width: 80%;
        margin-bottom: 30px;
    }
}

.service-section {
    background-color: #f9f9f9;
    border-radius: 10px;
}
.service-box {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.service-box:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}
.service-item i {
    color: #28a745;
}
h5 {
    color: #333;
}
p {
    color: #666;
}

        .team-section {
            padding: 50px 0;
            background-color: #f9f9f9;
        }

        .team-member {
            text-align: center;
        }

        .team-member img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .team-member h5 {
            margin-top: 15px;
            font-weight: bold;
        }

        .cta-section {
            background-color: #28a745;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .cta-section a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid white;
            border-radius: 5px;
            font-size: 1.2rem;
            transition: background-color 0.3s;
        }

        .cta-section a:hover {
            background-color: white;
            color: #28a745;
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
    .service-item {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}
.btn-outline-success {
    border-color: #28a745;
    color: #28a745;
}
.btn-outline-success:hover {
    background-color: #28a745;
    color: #fff;
}


    </style>
</head>



<header class="header sticky-top p-3 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="logo">Le Jardin de Kakoo</h1>
        <button id="navToggleButton" onclick="toggleSidebar()" class="btn btn-light d-lg-none">
            <i class="fas fa-bars"></i> Menu
        </button>
        <nav class="d-none d-lg-flex">
            <a href="../pages/home.php" class="nav-link">Home</a>
            <a href="../pages/shop.php" class="nav-link">Shop</a>
            <a href="../pages/about.php" class="nav-link">About Us</a>
            <a href="../pages/contactus.php" class="nav-link">Contact Us</a>
        </nav>
    </div>
    <nav id="sidebar" class="mobile-sidebar d-lg-none">
        <a href="../pages/home.php" class="nav-link">Home</a>
        <a href="../pages/shop.php" class="nav-link">Shop</a>
        <a href="../pages/about.php" class="nav-link">About Us</a>
        <a href="../pages/contactus.php" class="nav-link">Contact Us</a>
    </nav>
</header>

<!-- Enhanced Image Gallery Section as a Slideshow -->
<section class="gallery-section container">
    <h2 class="text-center mb-5">Our Plant Nursery</h2>
    <div id="galleryCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#galleryCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#galleryCarousel" data-slide-to="1"></li>
            <li data-target="#galleryCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../images/garden1.jpg" alt="Garden Design" class="d-block w-100">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Beautiful Garden Design</h5>
                    <p>Creating stunning outdoor spaces with a natural touch.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../images/garden2.jpg" alt="Garden Landscape" class="d-block w-100">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Lush Garden Landscape</h5>
                    <p>Enhancing your garden with vibrant landscapes.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../images/garden3.jpg" alt="Garden Plants" class="d-block w-100">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Thriving Garden Plants</h5>
                    <p>Providing a variety of healthy, well-maintained plants.</p>
                </div>
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#galleryCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#galleryCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</section>


<!-- Enhanced About Us Section -->
<section class="about-section container">
    <h2 class="text-center mb-5">About Us</h2>
    <p class="text-center about-intro">
        At Le Jardin de Kakoo, we believe in the beauty of nature and the importance of nurturing plants 
        to create a greener world. Our nursery is home to a wide variety of plants, from exotic flowers 
        to everyday houseplants.
    </p>

    <div class="about-values mt-5">
        <div class="value-box">
            <i class="fas fa-seedling"></i>
            <h4>Quality Plants</h4>
            <p>We ensure all our plants are grown with the highest care, focusing on health and vitality.</p>
        </div>
        <div class="value-box">
            <i class="fas fa-leaf"></i>
            <h4>Eco-Friendly</h4>
            <p>We promote sustainable and eco-friendly gardening practices for a better tomorrow.</p>
        </div>
        <div class="value-box">
            <i class="fas fa-smile"></i>
            <h4>Customer Satisfaction</h4>
            <p>We prioritize providing excellent service and ensure customer happiness in every purchase.</p>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="service-section container py-5">
    <h2 class="text-center mb-5 font-weight-bold text-uppercase">Our Services</h2>
    <div class="row text-center">
        <div class="col-md-4 service-item mb-4">
            <div class="service-box p-4">
                <i class="fas fa-pencil-ruler fa-3x mb-3 text-success"></i>
                <h5 class="mb-3 font-weight-bold">Landscape Design</h5>
                <p>We offer expert landscape design services to transform your space into a natural wonder.</p>
            </div>
        </div>
        <div class="col-md-4 service-item mb-4">
            <div class="service-box p-4">
                <i class="fas fa-hammer fa-3x mb-3 text-success"></i>
                <h5 class="mb-3 font-weight-bold">Garden Development</h5>
                <p>Our team provides garden development and maintenance services for both small and large projects.</p>
            </div>
        </div>
        <div class="col-md-4 service-item mb-4">
            <div class="service-box p-4">
                <i class="fas fa-comments fa-3x mb-3 text-success"></i>
                <h5 class="mb-3 font-weight-bold">Consultation</h5>
                <p>Need expert advice? We offer consultation services to help you achieve your garden dreams.</p>
            </div>
        </div>
    </div>
    <div class="text-center mt-5">
        <a href="contactus.php" class="btn btn-lg btn-success">Contact Us Now</a>
    </div>
</section>

<section class="team-section container">
    <h2 class="text-center mb-5">Meet Our Team</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="team-member">
                <img src="../images/team1.jpg" alt="Team Member 1">
                <h5>John Doe</h5>
                <p>Founder & CEO</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="team-member">
                <img src="../images/team2.jpg" alt="Team Member 2">
                <h5>Jane Smith</h5>
                <p>Head of Operations</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="team-member">
                <img src="../images/team3.jpg" alt="Team Member 3">
                <h5>Mary Johnson</h5>
                <p>Lead Gardener</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <h3>Ready to Bring Nature Home?</h3>
    <br>
    <a href="../pages/shop.php">Shop Now</a>
</section>
<br>
<br>
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script>
<script>
         function toggleSidebar() {
    document.querySelector('header').classList.toggle('sidebar-open');
}
    </script>
    <script>
    $('#galleryCarousel').carousel({
    interval: 3000, // 3 seconds interval between slides
    pause: 'hover'  // Pause the slideshow when hovered
});
</scipt>
</body>
</html>
