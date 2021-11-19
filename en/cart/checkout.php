<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");
    include(__DIR__ . "/../../server/includes/utils/function.php");

    session_start();

    /* Restrict the access if user haven't log in. */
    Utils\Utils::redirectIndex();

    /* User sign up, login, logout functions. */
    User\UserSession::userSignUp();
    User\UserSession::userLogin();
    User\UserSession::userLogout();

    if (Utils\Utils::isMethodVarSet("post", "checkoutCartItemID")) {
        User\UserSession::addSessionVar("checkoutCartItemID", $_POST["checkoutCartItemID"]);
    }

    if (Utils\Utils::isMethodVarSet("post", "checkoutBillingProfile")) {
        User\UserSession::addSessionVar("checkoutBillingProfile", $_POST["checkoutBillingProfile"]);
    }

    if (Utils\Utils::isMethodVarSet("post", "selectedBillingProfile")) {
        User\UserSession::addSessionVar("selectedBillingProfile", $_POST["selectedBillingProfile"]);
    }

    if (Utils\Utils::isMethodVarSet("post", "place-order")) {
        User\UserSession::addSessionVar("place-order", true);
    }

    /* Perform cart item remove operation */
    User\Checkout::removeCartItem();

    /* Perform place order operation */
    User\Checkout::processCheckout(User\UserSession::getSessionUserID());

    /* Discard any resubmission. */
    Utils\Utils::discardResubmission();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg">

    <!-- IconOut Dependency -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/solid.css">

    <!-- Boostrap 5.1 Dependency -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

    <!-- Local Dependency -->
    <link href="/OPCS/resources/css/general.css" rel="stylesheet">
    <link href="/OPCS/resources/css/user/user-checkout.css" rel="stylesheet">
    <link href="/OPCS/resources/css/user/user-profile.css" rel="stylesheet">

    <title>Check Out</title>
</head>

<body class="general-font">
    <!-- Nav bar -->
    <div class="navbar position-sticky navbar-expand-md bg-dark navbar-dark z-forward">
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
                        <a class="nav-link" href="/OPCS/en/index">Home</a>
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

    <!-- Main Body Container -->
    <div class="container-fluid main-body-container gradient-dark-2">
        <div class="container-fluid pb-4">
            <div class="row">
                <h3 class="text-center py-3">Check Out</h3>
            </div>

            <div class="row d-flex justify-content-between">
                <div class="col-lg-5 pb-3 px-4">
                    <h5 class="text-center pt-3">Billing Details</h5>
                    <hr>

                    <div class="container-fluid mb-3 d-grid justify-content-center gap-1">
                        <form id="select-billing-profile-form" method="post">
                            <label for="checkout-billing-profile"><small>Select Billing Profile: </small></label>
                            <select class="form-select" id="checkout-billing-profile" type="text" onchange="getBillingInfo(this)" required>
                                <option selected value="" id="non-selected" disabled>None selected</option>
                                <?php User\BillingInfo::renderBillingProfileList(); ?>

                            </select>
                        </form>

                        <a href="/OPCS/en/account/billing-information" class="mt-0 pt-0"><small>Manage billing information</small></a>
                    </div>

                    <?php User\BillingInfo::renderBillingInfo(); ?>
                </div>

                <div class="col-lg-7 px-4">
                    <h5 class="text-center pt-3">Your Cart</h5>
                    <hr>

                    <div class="container-fluid mb-4 p-3 lighter-dark rounded-3" style="max-height: 560px; min-height: 560px">
                        <?php User\Checkout::renderCartItems(User\UserSession::getSessionUserID()); ?>
                    </div>
                </div>
            </div>

            <div class="row d-flex justify-content-center py-3">
                <div class="btn border-dark rounded-3 text-nowrap dark-gradient-btn" style="width: 150px" data-bs-toggle="modal" data-bs-target="#payment-overlay" id="place-order-btn" onmouseover="blockPayment(this)">Place Order</div>
            </div>

            <div class="modal fade" id="payment-overlay" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content d-flex">
                        <div class="container position-relative rounded-top bg-dark py-2">
                            <h5 class="modal-title text-center">Payment</h5>
                            <button type="button" class="overlay-close-btn position-absolute d-flex justify-content-center align-items-center" data-bs-dismiss="modal">
                                <img src="/OPCS/resources/images/dark-theme/close-dark_theme.png" class="overlay-close-btn-icon" alt="">
                            </button>
                        </div>

                        <div class="modal-body d-grid gap-5 p-0 rounded-bottom lighter-dark"">
                            <form class="d-grid gap-5 pt-3" action="" method="post">
                                <div class="row d-flex justify-content-center position-relative pt-3">
                                    <div class="col d-flex justify-content-center my-0 py-0 card-image-box"><img src="/OPCS/resources/images/general/visa.png" alt=""></div>
                                    <div class="col d-flex justify-content-center my-0 py-0 card-image-box"><img src="/OPCS/resources/images/general/mastercard.png" alt=""></div>
                                    <div class="col d-flex justify-content-center my-0 py-0 card-image-box"><img src="/OPCS/resources/images/general/amex.png" alt=""></div>
                                </div>

                                <div class="row justify-content-center position-relative">
                                    <div class="col-5 d-flex justify-content-center me-0 py-0">
                                        <div class="form-floating payment-card-input d-flex me-0 card-first-row">
                                            <input class="form-control d-flex align-items-center px-2" id="payment-card-holder" type="text" placeholder="exampleCardHolder" name="card-holder" required>
                                            <label for="payment-card-holder" class="px-2 align-self-center"><small>Card Holder</small></label>
                                        </div>
                                    </div>

                                    <div class="col-5 d-flex justify-content-center p-0">
                                        <div class="form-floating payment-card-input d-flex ms-0 card-first-row">
                                            <input class="form-control d-flex align-items-center px-2" id="payment-card-number" type="text" placeholder="exampleCardNumber" name="card-number" required>
                                            <label for="payment-card-number" class="px-2 align-self-center"><small>Card Number</small></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-center position-relative">
                                    <div class="col-6 input-group d-flex justify-content-center py-0 my-0 card-second-row">
                                        <div class="form-floating payment-card-input d-flex my-0 card-exp-input">
                                            <input class="form-control d-flex align-items-center px-2" id="payment-card-exp-month" type="number" placeholder="exampleExpMonth" name="exp-month" required>
                                            <label for="payment-card-exp-month" class="px-2 align-self-center"><small>Expiry Month</small></label>
                                        </div>

                                        <div class="form-floating payment-card-input d-flex my-0 card-exp-input">
                                            <input class="form-control d-flex align-items-center px-2" id="payment-card-exp-year" type="number" placeholder="exampleExpYear" name="exp-year" required>
                                            <label for="payment-card-exp-year" class="px-2 align-self-center"><small>Expiry Year</small></label>
                                        </div>
                                    </div>

                                    <div class="col-6 d-flex justify-content-center py-0 my-0 card-second-row">
                                        <div class="form-floating payment-card-input d-flex card-exp-input">
                                            <input class="form-control d-flex align-items-center px-2" id="payment-card-cvv" type="text" placeholder="exampleCVV" name="card-cvv" required>
                                            <label for="payment-card-cvv" class="px-2 align-self-center"><small>CVV</small></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer bg-dark border-dark d-flex payment-overlay-footer position-relative justify-content-around">
                                    <div class="d-inline-block">
                                        <button type="button" class="btn btn-lg payment-cancel dark-gradient-btn" data-bs-dismiss="modal">Cancel</button>
                                    </div>

                                    <div class="d-inline-block">
                                        <form action="" method="post" class="d-inline-block">
                                            <button type="submit" class="btn btn-lg payment-pay" name="place-order" onclick="placeOrder()">Pay</button>
                                        </form>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <!-- jQuery Dependency -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Local Script Dependency -->
    <script src="/OPCS/resources/js/user/user-checkout.js"></script>
    <script src="/OPCS/resources/js/script.js"></script>
</body>