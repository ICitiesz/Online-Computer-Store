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

    /* Buttons links to other page. */
    Utils\Utils::redirectPage("post", "container-profile-btn", "/OPCS/en/account/profile");
    Utils\Utils::redirectPage("post", "container-security-btn", "/OPCS/en/account/account-security");
    Utils\Utils::redirectPage("post", "container-purchase-history-btn", "/OPCS/en/account/purchase-history");

    /* Store temporary POST value in session */
    if (Utils\Utils::isMethodVarSet("POST", "create-billing-profile")) {
        User\UserSession::addSessionVar("create-billing-profile", true);
    }

    if (Utils\Utils::isMethodVarSet("POST", "reference")) {
        User\UserSession::addSessionVar("reference", $_POST["reference"]);
    }

    if (Utils\Utils::isMethodVarSet("post", "croppedImage")) {
        User\UserSession::addSessionVar("croppedImage", $_POST["croppedImage"]);
    }

    /* Main Function */
    User\BillingInfo::addBillingProfile();
    User\User::updateProfilePic(User\UserSession::getSessionUserID());

    /* Discard any resubmission. */
    Utils\Utils::discardResubmission();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg">

    <!-- Cropper JS Dependency -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" integrity="sha512-0SPWAwpC/17yYyZ/4HSllgaK7/gg9OlVozq8K7rf3J8LvCjYEEIfzzpnA2/SSjpGIunCSD18r3UhvDcu/xncWA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js" integrity="sha512-ooSWpxJsiXe6t4+PPjCgYmVfr1NS5QXJACcR/FPpsdm6kqG1FmQ2SVyg2RXeVuCRBLr0lWHnWJP6Zs1Efvxzww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- IconOut Dependency -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/solid.css">

    <!-- Boostrap 5.1 Dependency -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

    <!-- Local Dependency -->
    <link href="/OPCS/resources/css/general.css" rel="stylesheet">
    <link href="/OPCS/resources/css/user/user-general.css" rel="stylesheet">
    <link href="/OPCS/resources/css/user/user-billing-information.css" rel="stylesheet">

    <title>Billing Information</title>
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

<!-- Main body container -->
<div class="container-fluid main-body-container gradient-dark-2">
    <div class="container-fluid pt-1 pb-3">

        <!-- Profile picture and username -->
        <div class="row mx-5 mt-3">
            <div class="col-lg-3 d-flex justify-content-center">
                <div class="position-relative">
                    <img class="rounded-circle" id="profile-image" src="<?php
                    $user = new User\User(User\UserSession::getSessionUserID());

                    if ($user->getProfilePic() === null) {
                        echo "/OPCS/resources/images/dark-theme/profile_user-dark_theme.png";
                    } else {
                        echo "/OPCS/data/user_profile_images/" . $user->getProfilePic();
                    }

                    ?>" alt="">
                    <button class="position-absolute add-profile-image" data-bs-toggle="modal" data-bs-target="#profile-image-upload"></button>
                </div>
            </div>

            <div class="modal fade" id="profile-image-upload" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content d-flex">
                        <div class="container position-relative rounded-top bg-dark py-2">
                            <h5 class="modal-title text-center">Update Profile Image</h5>
                            <button type="button" class="overlay-close-btn position-absolute d-flex justify-content-center align-items-center" onclick="destroyCropper()" data-bs-dismiss="modal">
                                <img src="/OPCS/resources/images/dark-theme/close-dark_theme.png" class="overlay-close-btn-icon" alt="">
                            </button>
                        </div>

                        <div class="modal-body p-0 rounded-bottom lighter-dark"">
                        <div class="row justify-content-center position-relative p-0">
                            <div class="col-8 ">
                                <canvas class="container-fluid profile-canvas" style="min-width: 100%; max-width: 100%; min-height: 100%; max-height: 100%;" id="profile-canvas"></canvas>
                            </div>

                            <div class="col-4 d-flex justify-content-center align-items-center border-start">
                                <img class="rounded-circle" src="" id="cropped-preview">
                            </div>
                        </div>

                        <div class="modal-footer bg-dark border-dark d-flex justify-content-center">
                            <form class="container-fluid position-relative py-2 d-flex justify-content-center " method="post" name="upload_form" id="upload_form" enctype="multipart/form-data">
                                <input type="file" name="profile-image-file" accept="image/*"  id="profile-image-file">

                                <button type="button" class="btn btn-lg position-absolute cancel-profile-pic-btn" data-bs-dismiss="modal" onclick="destroyCropper()">Cancel</button>
                                <button type="button" class="btn btn-lg position-absolute update-profile-pic-btn" id="profile-image-save" onclick="updateProfileImage()">Update</button>
                            </form>
                        </div>
                        <script>
                            let profileImage = document.getElementById("profile-image-file");
                            let cropper;

                            // Create a preview of the image and create a crop for it.
                            profileImage.onchange = function() {
                                // Check if the files is not more than 1
                                if (profileImage.files.length > 1) {
                                    alert("Please select only one image");
                                    profileImage.value = "";
                                    return;
                                }

                                // Check if the file is an image
                                if (!this.files[0].type.match(/image.*/)) {
                                    alert("Please select an image file");
                                    profileImage.value = "";
                                    return;
                                }

                                let reader = new FileReader();
                                reader.onload = function() {
                                    let img = new Image();
                                    img.src = reader.result;
                                    img.onload = function() {
                                        let canvas = document.getElementById("profile-canvas");
                                        let ctx = canvas.getContext("2d");
                                        canvas.width = img.width;
                                        canvas.height = img.height;
                                        ctx.drawImage(img, 0, 0);
                                        cropper = new Cropper(canvas, {
                                            aspectRatio: 1,
                                            viewMode: 1,
                                            crop: function(e) {
                                                let data = cropper.getData();
                                                let image = cropper.getCroppedCanvas();
                                                document.getElementById("cropped-preview").src = image.toDataURL();
                                                document.getElementById("cropped-preview").style.maxWidth = "300px";
                                                document.getElementById("cropped-preview").style.maxHeight = "300px";
                                                document.getElementById("cropped-preview").style.minWidth = "300px";
                                                document.getElementById("cropped-preview").style.minHeight = "300px";
                                            }
                                        });
                                    }
                                }
                                reader.readAsDataURL(this.files[0]);
                            }

                            function destroyCropper() {
                                if (cropper) {
                                    cropper.destroy();
                                    cropper = null;
                                }

                                // Reset the image preview.
                                document.getElementById("cropped-preview").src = "";

                                // Reset the canvas.
                                let canvas = document.getElementById("profile-canvas");
                                let ctx = canvas.getContext("2d");
                                ctx.clearRect(0, 0, canvas.width, canvas.height);

                                // Reset the file input.
                                profileImage.value = "";
                            }

                            // Get the cropped canvas in blob format and send it to the server.
                            function updateProfileImage() {
                                if (cropper === undefined) {
                                    alert("Please select an image first.");
                                    return;
                                }

                                let cropped = cropper.getCroppedCanvas();
                                let croppedImage = cropped.toDataURL();

                                $.ajax({
                                    type: "POST",
                                    dataType: "text",
                                    uri: "/OPCS/en/account/profile.php",
                                    data: {croppedImage: croppedImage},
                                    success: function(data) {
                                    }
                                });

                                document.getElementById("upload_form").submit();
                                alert('Your profile picture has been updated!');
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>

            <div class="col-lg-9 d-inline-flex pb-1 align-items-end username-display-box">
                <?php
                echo '<h2 id="username-display">' . $user->getUsername() . '</h2>';
                ?>
            </div>
        </div>

        <hr>

        <!-- Panel container -->
        <div class="row mx-5 panel-container"> <!-- Height = 900px -->
            <!-- Button container -->
            <div class="col-lg-3 button-container">
                <!-- Buttons -->
                <div class="d-grid px-4 py-5 gap-3">
                    <form class="d-grid py-5 gap-3" action="" method="post">
                        <button class="btn p-3 button-border gradient-btn rounded-3" name="container-profile-btn">
                            Profile
                        </button>

                        <button class="btn p-3 button-border gradient-btn rounded-3" name="container-security-btn">
                            Account Security
                        </button>

                        <button class="btn p-3 button-border selected-btn gradient-btn rounded-3" name="container-billing-info-btn">
                            Billing Information
                        </button>

                        <button class="btn p-3 button-border gradient-btn rounded-3" name="container-purchase-history-btn">
                            Purchase History <?php Utils\Utils::redirectPage("post", "container-purchase-history-btn", "/OPCS/en/account/purchase-history") ?>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content frame -->
            <div class="col-lg-9 px-5">
                <!-- Inner content frame -->
                <div class="container rounded-3 my-2 px-5 position-relative inner-content-frame lighter-dark"> <!-- Height = 650px -->
                    <div class="row pt-3">
                        <h3 class="text-center pb-1">Billing Information</h3>
                        <hr>
                    </div>

                    <div class="container overflow-auto" style="max-height: 500px;">
                        <div class="accordion d-grid" id="billing-info" style="gap: 2px">
                            <?php
                                User\BillingInfo::prepareBillingProfile();
                                User\BillingInfo::createBillingProfile();
                            ?>

                            <div class="container rounded-3 px-0 white" style="height: 50px" id="add-new-billing-profile">
                                <form action="" class="w-100 h-100" method="post">
                                    <button class="btn rounded-3 w-100 h-100" name="create-billing-profile">
                                        <span class="text-center fw-bold" style="font-size: inherit;">Add New Billing Profile</span>
                                    </button>
                                </form>
                            </div>

                            <!-- Billing Profile Delete Confirmation -->
                            <div class="modal fade" id="billing-profile-delete-confirmation" data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered" >
                                    <div class="modal-content d-flex">
                                        <div class="container position-relative rounded-top bg-dark py-2">
                                            <h5 class="modal-title text-center">Billing Profile Delete Confirmation</h5>
                                            <button type="button" class="overlay-close-btn d-flex justify-content-center position-absolute" data-bs-dismiss="modal">
                                                <img class="overlay-close-btn-icon" src="/OPCS/resources/images/dark-theme/close-dark_theme.png" alt="">
                                            </button>
                                        </div>

                                        <div class="modal-body p-0 rounded-bottom lighter-dark">
                                            <form action="" method="post">
                                                <div class="container d-flex justify-content-center pt-2">
                                                    <p class="text-center">Are you sure to delete this profile?</p>
                                                </div>

                                                <div class="row d-flex justify-content-around billing-info-option mb-3">
                                                    <div class="col d-flex justify-content-center">
                                                        <button class="btn btn-lg billing-info-delete px-4 bg-info" type="button" data-bs-dismiss="modal" name="confirm_delete" onclick="removeBillingProfile()">Yes</button>
                                                    </div>

                                                    <div class="col d-flex justify-content-center billing-info-save-container">
                                                        <button class="btn btn-lg billing-info-save px-4 bg-info" type="button" data-bs-dismiss="modal">No</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script src="/OPCS/resources/js/user/user-billing-information.js"></script>

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
    <?php
    if (!isset($_SESSION['user_id'])){
        echo "<script>document.getElementById('checkout-btn').removeAttribute('href');</script>";
    }
    ?>

    <!-- jQuery Dependency -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Local Script -->
    <script src="/OPCS/resources/js/script.js"></script>
</html>