<?php
session_start();
?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Jardin - Contact Us</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Custom styles */
        .contact-section {
            padding: 50px 0;
            background-color: #f8f9fa;
        }

        .contact-info {
            margin-bottom: 30px;
        }

        .contact-info h3, .contact-info p {
            color: #333;
        }

        .contact-info i {
            color: #28a745;
        }

        .contact-form input, .contact-form textarea {
            border-radius: 10px;
            border: 1px solid #ced4da;
        }

        .contact-form button {
            background-color: #28a745;
            color: white;
        }

        .social-icons a {
            color: #28a745;
            margin-right: 15px;
        }

        .social-icons a:hover {
            color: #006600;
        }

        .map-container {
            position: relative;
            padding-bottom: 56.25%; /* Aspect ratio */
            height: 0;
            overflow: hidden;
            max-width: 100%;
            background-color: #eee;
            margin-top: 30px;
        }

        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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

<body class="w3-light-gray">

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



<div class="container mt-5">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> <?php echo $_SESSION['success_message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
</div>

<div class="contact-section">
    <div class="container">
        <div class="row">
            <!-- Contact Information -->
            <div class="col-md-6 contact-info">
                <h3>Contact Information</h3>
                <p><i class="fas fa-phone-alt"></i> <a href="tel:0940384999" aria-label="Phone number">+251940384999</a></p>
                <p><i class="fas fa-envelope"></i> <a href="mailto:nigusuyamin@gmail.com" aria-label="Email address">nigusuyamin@gmail.com</a></p>
                <p><i class="fas fa-map-marker-alt"></i> Addis Ababa, Bole Japan</p>
                <div class="social-icons mt-3">
                    <a href="https://www.facebook.com/lejardindekakoo" aria-label="Facebook"><i class="fab fa-facebook fa-2x"></i></a>
                    <a href="https://www.instagram.com/lejardindekakoo" aria-label="Instagram"><i class="fab fa-instagram fa-2x"></i></a>
                    <a href="https://t.me/LeGiardinDeKakoo" aria-label="Telegram"><i class="fab fa-telegram fa-2x"></i></a>
                    <a href="https://wa.me/message/47J4IQW4KT3SL1" aria-label="WhatsApp"><i class="fab fa-whatsapp fa-2x"></i></a>
                    <a href="https://www.tiktok.com/@plantparadise25" aria-label="TikTok"><i class="fab fa-tiktok fa-2x"></i></a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-md-6">
                <h3>Send Us a Message</h3>
                <form class="contact-form" aria-label="Contact form" id="contactForm" method="POST" action="process_contact.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
        <!-- Map Section -->
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126494.30914619711!2d9.03675487403893!3d8.980501360859866!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b85b6aa6e9061%3A0xd3e09f1546337351!2sLe%20Jardin%20de%20Kakoo!5e0!3m2!1sen!2set!4v1632579082244!5m2!1sen!2set" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container text-center">
        <div class="footer-title">Follow Us</div>
        <div class="footer-social">
            <a href="https://www.facebook.com/lejardindekakoo" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="https://www.instagram.com/lejardindekakoo" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="https://t.me/LeGiardinDeKakoo" aria-label="Telegram"><i class="fab fa-telegram"></i></a>
            <a href="https://wa.me/message/47J4IQW4KT3SL1" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.tiktok.com/@plantparadise25" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Le Jardin de Kakoo. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
         function toggleSidebar() {
    document.querySelector('header').classList.toggle('sidebar-open');
}
    </script>
<script>
   

    // Optional: Close sidebar when a link is clicked
    document.querySelectorAll('.sidebar a').forEach(item => {
        item.addEventListener('click', () => {
            document.getElementById('sidebarMenu').classList.remove('show');
        });
    });
</script>

</body>
</html>
