<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");
    include(__DIR__ . "/../../server/includes/utils/function.php");

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

    <!-- Font Awesome Dependency -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

    <!-- Local Dependency -->
<!--    <link rel="stylesheet" href="../resources/css/style.css">-->
    <link rel="stylesheet" href="/OPCS/resources/css/general.css">
    <title>Shipping</title>
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
            <section id="footer-pages" class="section">
                <div class="title">Shipping</div>
                <hr class="straight-line">
                <div class="content container">
                    <div class="answer-content">
                        <div class="secondary-title">When will I receive my shipment?</div>
                        <div class="answer">
                            Since all products will be authenticated first before proceeding to shipment preparation thus, it will typically take 3-5 working days for
                            the shipment preparation. Interstate delivery times might be vary due to the distance. As soon as your products are authenticated, we'll ship
                            them out to you as soon as possible.
                        </div>
                    </div>
                    <div class="answer-content">
                        <div class="secondary-title">Cost of Shipping</div>
                        <div class="answer">
                            The cost of shipping is fixed for <strong>RM 15.00</strong> to all states within Malaysia. As all products are authenticated before shipping out,
                            therefore, orders will typically arrived within 5-7 working days for domestic delivery between states.
                        </div>
                    </div>
                    <div class="answer-content">
                        <div class="secondary-title">Is International Shipping Available?</div>
                        <div class="answer">
                          No, we are currently available in Malaysia only. 
                          Further expand on the location we support will be announced in the future.
                        </div>
                    </div>
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
        <?php
        if (!isset($_SESSION['user_id'])){
            echo "<script>document.getElementById('checkout-btn').removeAttribute('href');</script>";
        }
        ?>
</body>
</html>