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

        .faq-section h3 {
            margin-top: 50px;
        }

        .faq-section .card {
            margin-bottom: 10px;
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
        <label for="name">Your Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" aria-label="Your name" required>
        <div class="invalid-feedback">Please enter your name.</div>
    </div>
    <div class="form-group">
        <label for="email">Your Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" aria-label="Your email" required>
        <div class="invalid-feedback">Please enter a valid email address.</div>
    </div>
    <div class="form-group">
        <label for="message">Your Message</label>
        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Write your message here" aria-label="Your message" required></textarea>
        <div class="invalid-feedback">Please enter your message.</div>
    </div>
    <div class="form-group">
        <label for="file">Attach a file (optional)</label>
        <input type="file" class="form-control-file" id="file" name="file" aria-label="File upload">
    </div>
    <button type="submit" class="btn btn-success btn-block">Send Message</button>
</form>
</div>
       

        <!-- Google Maps Embed -->
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3940.806200589748!2d38.78011627381029!3d8.989973289601116!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b85b929c6b7af%3A0x98d4d7aa2da96f42!2sLe%20jardin%20de%20kakoo!5e0!3m2!1sam!2set!4v1708430226241!5m2!1sam!2set" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" aria-label="Google map location of Le Jardin de Kakoo"></iframe>
        </div>
    </div>
</div>
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


<script>
    function toggleSidebar() {
        document.querySelector('header').classList.toggle('open');
    }
</script>
<script>
    // Example of Bootstrap validation
    (function () {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.contact-form')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })();
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
