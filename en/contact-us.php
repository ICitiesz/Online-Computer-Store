<?php
    include(__DIR__ . "/../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../server/includes/utils/Conn.php");
    include(__DIR__ . "/../server/includes/utils/function.php");

    session_start();

    /* User sign up, login, logout functions. */
    User\UserSession::userSignUp();
    User\UserSession::userLogin();
    User\UserSession::userLogout();

    Utils\Utils::discardResubmission();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg">

    <!-- Bootstrap 5.1 Dependency -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

    <!-- Swiper Dependency -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>

    <!-- IconOut Dependency -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- Local Dependency -->
<!--    <link rel="stylesheet" href="/OPCS/resources/css/style.css">-->
    <link rel="stylesheet" href="/OPCS/resources/css/general.css">
    <link rel="stylesheet" href="/OPCS/resources/css/contact-us.css">
    <title>Contact Us</title>
</head>
<body class="general-font">
    <!-- Nav bar -->
    <div class="navbar container-fluid navbar-expand-md bg-dark navbar-dark navbar-hide">
        <div class="container-fluid">

            <!-- Nav brand -->
            <a class="navbar-brand" href="/OPCS/en/index">
                <img src="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg" width="140" height="45" alt="">
            </a>

            <!-- Nav bar toggle for smaller screen. -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-items">
                <img src="/OPCS/resources/images/dark-theme/hamburger_menu-dark_theme.png" class="nav-icon" alt="">
            </button>

            <!-- Nav bar content with collapse -->
            <div class="collapse navbar-collapse" id="nav-items">

                <!-- Nav content 1 -->
                <ul class="navbar-nav align-items-center ms-auto">
                    <li class="nav-item nav-item-custom">
                        <a class="nav-link nav-item-custom" href="/OPCS/en/index">Home</a>
                    </li>

                    <li class="nav-item nav-item-custom dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Brands</a>
                        <ul class="dropdown-menu lighter-dark-2" aria-labelledby="navbarDropdownMenuLink">
                            <?php
                                global $conn;

                                print_brand($conn);
                            ?>
                        </ul>
                    </li>

                    <li class="nav-item nav-item-custom">
                        <a class="nav-link" href="/OPCS/en/all-product/products">Products</a>
                    </li>

                    <li class="nav-item nav-item-custom">
                        <a class="nav-link" href="/OPCS/en/contact-us">Contact Us</a>
                    </li>

                    <li class="nav-item nav-item-custom">
                        <a class="nav-link" href="/OPCS/en/about-us">About Us</a>
                    </li>
                </ul>

                <!-- Nav content 2 -->
                <ul class="navbar-nav align-items-center ms-auto">
                    <!-- Search Bar Overlay -->
                    <li class="nav-item nav-item-custom">
                        <div class="overlay" id="searchBar">
                            <span class="closeBtn" id="close-btn"><i class="uil uil-multiply"></i></span>
                            <div class="overlay-content">
                                <form class="search-bar" action="/OPCS/en/all-product/products" method="GET">
                                    <input type="text" name="search_result" placeholder="Search..." id='searchField'>
                                    <button type="submit" class="search-btn"><i class="uil uil-search norm-icon"></i></button>
                                </form>
                            </div>
                        </div>
                        <div id="search-btn" class="nav-btn nav-link" ><img src="/OPCS/resources/images/dark-theme/search-dark_theme.png" class="nav-icon" alt=""></div>
                    </li>


                    <li class="nav-item nav-item-custom">
                        <div>
                            <div class="nav-btn nav-link" id="cart-btn">
                                <img src="/OPCS/resources/images/dark-theme/shopping_cart-dark_theme.png"  class="nav-icon" alt="">
                            </div>
                            <div class="cart-menu">
                                <p class="subtitle">Shopping Cart</p>
                                <hr class="straight-line">
                                <div class="cart-product">
                                    <?php
                                    if(isset($_SESSION['user_id'])){
                                        show_cart($conn, $_SESSION['user_id']);
                                    }
                                    else{
                                        echo "<div class=\"no-review\">There is nothing inside the cart</div>";
                                    }
                                    ?>
                                </div>
                                <div class="cart-bottom">
                                    <div class="subtotal">
                                        Subtotal
                                    </div>
                                    <div class="price">

                                    </div>
                                    <div class="checkout-btn">
                                        <a href="/OPCS/en/cart/checkout" id="checkout-btn"><button type="submit" class="btn">Checkout</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>


                    <li class="nav-item nav-item-custom" id="profile-btn">
                        <a class="nav-link" id="profile-link">
                            <div class="container-fluid d-flex justify-content-center px-0">
                                <?php
                                    User\UserSession::setNavBarProfileName();
                                ?>
                            </div>
                        </a>

                        <?php
                            User\UserSession::addProfileDropDown();
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?php
        User\UserSession::addOverlay();
    ?>
        
    <main class="gradient-dark-2">
        <section class="section" id="contact-us">
            <p class="title contact-us-title">Contact Us</p>
            <hr class="straight-line">
            <div class="contact-detail row">
                <div class="contact-map col-lg-6">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.81143397764!2d101.70788771525208!3d3.1444180540573656!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc362901ba7a1b%3A0xdec8dfff0e8b22a2!2sViewnet%20Computer%20System%20Sdn%20Bhd!5e0!3m2!1sen!2smy!4v1631953851082!5m2!1sen!2smy"
                            height="450" style="border:0;" allowfullscreen="true" loading="lazy">
                    </iframe>
                </div>
                <div class="contact-info col-lg-6">
                    <div class="company-title">
                        <p class="subtitle">Company Details</p>
                    </div>
                    <hr class="straight-line">
                    <div class="contact-description row">
                        <i class="uil uil-store contact-icons"></i>
                        <p>Viewnet Computer System Sdn Bhd 3 - 012,
                            IT - 020, 021, 022 , 023, 023A , 023B, 023C , 024, 7, Jalan Bintang, Bukit Bintang,
                            Kuala Lumpur, Federal Territory of Kuala Lumpur
                        </p>
                    </div>
                    <div class="contact-description">
                        <i class="uil uil-phone-alt contact-icons"></i>
                        <a href="tel:+6018 315 7890">+6018 315 7890</a>
                    </div>
                    <div class="contact-description">
                        <i class="uil uil-envelope-alt contact-icons"></i>
                        <a href="mailto:shane0209@gmail.com">shane0209@gmail.com</a>
                    </div>
                    <div class="contact-description">
                        <i class="uil uil-briefcase-alt contact-icons"></i>
                        <p>Working Hours: 9am - 6pm</p>
                    </div>
                </div>
            </div>
            <hr class="straight-line">
            <div class="contact-form">
                <p class="subtitle">Want To Approach Us</p>
                <form action="mailto:shane0209@gmail.com" method="POST" enctype="multipart/form-data">
                    <div class="contact-inputs">
                        <label>Name</label>
                        <input type="text" class="form-control" name="Customer_Name" placeholder="Your Name" required>
                    </div>
                    <div class="contact-inputs">
                        <label>Contact Number</label>
                        <input type="tel" class="form-control" name="Customer_Contact_Number" placeholder="012-3456789" required>
                    </div>
                    <div class="contact-inputs">
                        <label>Message</label>
                        <textarea class="form-control" rows="5" name="Customer_message" placeholder="Your message and enquiries" required></textarea>
                    </div>
                    <div class="contact-inputs">
                        <button type="submit" class="btn orange-btn">Send <i class="uil uil-message btn-icon"></i></button>
                    </div>
                </form>
            </div>
        </section>
    </main>


    <!-- Footer -->
    <footer class="container-fluid py-2 footer-bg">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-between">
                <!-- Company logo -->
                <div class="col-lg-2 d-flex align-items-center justify-content-center">
                    <a class="navbar-brand" href="/OPCS/en/index">
                        <img src="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg" width="140" height="50" alt="">
                    </a>
                </div>

                <!-- Social media -->
                <div class="col-lg-2">
                    <div class="row flex-nowrap d-flex justify-content-center">
                        <h6 class="text-center mb-2">Follow us on:</h6>
                    </div>

                    <div class="row flex-nowrap">
                        <div class="col d-inline-flex justify-content-center">
                            <a href="https://www.facebook.com/OP-Computer-Shop-104843925344870"><img src="/OPCS/resources/images/general/facebook.png" class="footer-social" alt=""></a>
                        </div>

                        <div class="col d-inline-flex justify-content-center">
                            <a href="https://www.instagram.com/accounts/onetap/?next=%2F"><img src="/OPCS/resources/images/general/instagram.png" class="footer-social" alt=""></a>
                        </div>

                        <div class="col d-inline-flex justify-content-center">
                            <a href="https://twitter.com/opcomputershop"><img src="/OPCS/resources/images/general/twitter.png" class="footer-social" alt=""></a>
                        </div>
                    </div>
                </div>

                <!-- External link -->
                <div class="col-lg-7 gap-2 d-grid">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col d-flex justify-content-center"><a href="/OPCS/en/ask/terms-and-condition" class="footer-external-link">Terms and Condition</a></div>
                        <div class="col d-flex justify-content-center"><a href="/OPCS/en/ask/shipping" class="footer-external-link">Shipping</a></div>
                        <div class="col d-flex justify-content-center"><a href="/OPCS/en/ask/warranty-policy" class="footer-external-link">Warranty Policy</a></div>
                    </div>


                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col d-flex justify-content-center"><a href="/OPCS/en/ask/privacy-policy" class="footer-external-link">Privacy Policy</a></div>
                        <div class="col d-flex justify-content-center"><a href="/OPCS/en/ask/faq" class="footer-external-link">FAQ</a></div>
                    </div>
                </div>
            </div>

            <hr class="mt-3 mb-1">

            <!-- Copyright -->
            <div class="row mx-auto pb-0 my-0">
                <p><small>OP Computer Store | Copyright © 2021, All Rights Reserved</small></p>
            </div>
        </div>
    </footer>

    <!-- Swiper Script Dependency -->
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

    <!-- Font Awesome Script Dependency -->
    <script src="https://kit.fontawesome.com/6ca1324f65.js" crossorigin="anonymous"></script>

    <!-- Local Script -->
    <script src="/OPCS/resources/js/script.js"></script>
</body>

<?php
if (!isset($_SESSION['user_id'])){
    echo "<script>document.getElementById('checkout-btn').removeAttribute('href');</script>";
}
?>
</html>