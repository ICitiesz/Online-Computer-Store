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

    global $conn;

    if(isset($_GET['addtocart'])) {
        $user_id = $_SESSION['user_id'];
        $product_id =  $_GET['product_id'];
        $product_quantity = $_GET['choose-quantity'];
        addToCart($conn, $product_id, $product_quantity, $user_id);
    }
    else if (isset($_GET['buynow'])){
        $user_id = $_SESSION['user_id'];
        $product_id =  $_GET['product_id'];
        $product_quantity = $_GET['choose-quantity'];
        addToCart($conn, $product_id, $product_quantity, $user_id);
        echo "<script>window.location.href='/OPCS/en/cart/checkout'</script>";
    }
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

        <!-- IconOut Dependency -->
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/solid.css">

        <!-- Local Dependency -->
<!--        <link rel="stylesheet" href="/OPCS/resources/css/style.css">-->
        <link rel="stylesheet" href="/OPCS/resources/css/general.css">
        <link rel="stylesheet" href="/OPCS/resources/css/products.css">
        <link rel="stylesheet" href="/OPCS/resources/css/display-product.css">
        <?php 
            global $conn;

            $id = (int) $_GET['product_id'];
            $sql = "SELECT * FROM product WHERE Product_ID = " . $id;
            $result = $conn->query($sql);
            if($result->num_rows === 1){
                while($row=$result->fetch_assoc()){
        ?>


        <title><?php echo $row['Product_Name']; ?></title>
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
            
            <main id="product_page" class="gradient-dark-2">
                <section>
                    <div class="container-fluid">
                        <form action="" method="GET">
                        <div class="row">
                            <div class="col-lg-6">
                                <img class="display-img" src="/OPCS/data/product_images/<?php echo $row["Product_Image"]; ?>" alt="">
                            </div>
                            <div class="product-details col-lg-6">
                                <div class="product-title product-data">
                                    <div class="product-title subtitle"><?php echo $row["Product_Name"]; ?></div>
                                    <input type="text" name="product_id" hidden value="<?php echo $row['Product_ID'] ?>">
                                    <hr class="straight-line">
                                </div>
                                <div class="product-data">
                                    <div class="product-desc">
                                        <p style="font-size: 1.875rem;">Price</p>
                                        <p style="font-size: 1.875rem;">RM <?php echo $row["Price"]; ?></p>
                                    </div>
                                    <div class="product-desc">
                                        <p>Product SKU</p>
                                        <p><?php echo $row["Product_SKU"]; ?></p>
                                    </div>
                                    <div class="product-desc">
                                        <p>Brand</p>
                                        <p><?php echo $row["Product_Brand"]; ?></p>
                                    </div>
                                    <div class="product-desc">
                                        <p>Stock Available</p>
                                        <p id="quantity"><?php echo $row["Product_Quantity"]; ?></p>
                                    </div>
                                    <div class="product-desc">
                                        <p>Availability</p>
                                        <?php 
                                            if($row['Product_Quantity'] > 0){
                                                echo "<p>In Stock</p>";
                                            }   
                                            else {
                                                echo "<p>Out of Stock</p>";
                                            }
                                        ?>
                                        
                                    </div>
                                </div>
                                <hr class="straight-line">
                                <div class="product-details">
                                    <div class="product-desc">
                                    <span>Quantity</span>
                                    <div class="item-quantity d-flex">
                                        <div class="minus">
                                            <button type="button" class="plus-minus-btn reduce-btn"><i class="uil uil-minus"></i></button>
                                        </div>
                                        <input type="number" id="choose-quantity" name="choose-quantity" class="choose-quantity" readonly min="1" value="1">
                                        <div class="plus">
                                            <button type="button" class="plus-minus-btn add-btn"><i class="uil uil-plus"></i></button>
                                        </div>
                                    </div>
                                    </div>
                                    <span id="warning-stock" class="warning-stock warning">You have reached the maximum quantity available for this item</span>
                                </div>
                                <div class="product-details">
                                    <div class="product-buttons">
                                        <button type="submit" name="buynow" value="buynow" class="flex-button orange-btn btn buynow-btn"><i class="uil uil-dollar-alt norm-icon"></i>Buy Now</button>
                                        <button type="submit" name="addtocart" value="addtocart" class="flex-button orange-btn btn addToCart-btn"><i class="uil uil-shopping-bag norm-icon"></i> Add To Cart</button>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </form>
                    </div>
                    <hr class="straight-line">
                </section>
                <section class="product-all">
                    <div class="container desc-review-tab">
                        <div class="tab-title" id="description-tab">Product Description</div>
                        <div class="tab-title" id="review-tab">Review</div>
                    </div>
                    <div class="tab_content">
                        <div class="container description" id="description-content">
                           <span><?php echo $row["Product_Description"]; ?></span>
                        </div>
            <?php 
                    }
                }
            ?>
                        <div class="container review hide" id="review-content">
                            <?php
                            $sql_query = "SELECT
                                                    user.Username,
                                                    Rating,
                                                    Feedback
                                                FROM
                                                    review
                                                INNER JOIN User ON review.User_ID = User.User_ID
                                                WHERE Product_ID =  $id";
                            $rating = $conn -> query($sql_query);
                            if ($rating -> num_rows >= 1){
                                while($review = $rating -> fetch_assoc()){
                                    switch($review['Rating']){
                                        case 5.0:
                                            $star = str_repeat("<i class=\"uis uis-star rating-icon\"></i>", 5);
                                            break;
                                        case 4.0:
                                            $star = str_repeat("<i class=\"uis uis-star rating-icon\"></i>", 4) . "<i class=\"uil uil-star rating-icon\"></i>";
                                            break;
                                        case 3.0:
                                            $star = str_repeat("<i class=\"uis uis-star rating-icon\"></i>", 3) . str_repeat("<i class=\"uil uil-star rating-icon\"></i>", 2);
                                            break;
                                        case 2.0:
                                            $star = str_repeat("<i class=\"uis uis-star rating-icon\"></i>", 2) . str_repeat("<i class=\"uil uil-star rating-icon\"></i>", 3);
                                            break;
                                        case 1.0:
                                            $star = "<i class=\"uis uis-star rating-icon\"></i>" . str_repeat("<i class=\"uil uil-star rating-icon\"></i>", 4);
                                            break;
                                        case 0.0:
                                            $star = str_repeat("<i class=\"uil uil-star rating-icon\"></i>", 5);
                                            break;
                                    }

                                    echo"<div class=\"reviews\">
                                            <div class=\"username-rating\">
                                                <div class=\"username\">
                                                    ". $review['Username'] ."
                                                </div>
                                                <div class=\"rating\">
                                                    " . $star . "  (" . $review['Rating'] .")

                                                </div>
                                            </div>
                                            <div class=\"comment\">
                                                ". $review['Feedback'] ."
                                            </div>
                                        </div>";
                                }
                            }
                            else{
                                echo "
                                            <div class=\"no-review\">No Review Available For This Product</div>
                                        ";
                            }


                            ?>
                            
                        </div>
                    </div>
                </section>
                <section class="suggestion">
                    <p class="subtitle">Suggested For you</p>
                    <hr class="straight-line">
                    <div class="box-content">
                        <?php 
                            print_product($conn, "SELECT Product_ID, Product_Image, Product_Name, Price, Category_ID, Product_Quantity FROM product ORDER BY RAND() LIMIT 4");
                            $conn->close();
                        ?>
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

        <!-- Local Script -->
        <script src="/OPCS/resources/js/script.js"></script>
        <script>
            let stock_quantity = parseInt(document.getElementById('quantity').innerHTML);
            let chosen_quantity = parseInt(document.querySelector('.choose-quantity').value);
            document.querySelector('.reduce-btn').addEventListener('click', () => {
                if(parseInt(document.querySelector('.choose-quantity').value) !== 1){
                    document.querySelector('.choose-quantity').value--;
                    document.getElementById('warning-stock').classList.remove('show');
                    chosen_quantity = parseInt(document.querySelector('.choose-quantity').value);
                }
            });
    
            document.querySelector('.add-btn').addEventListener('click', () => {
                // let stock_quantity = parseInt(document.getElementById('quantity').innerHTML);
                let selected_quantity = parseInt(document.getElementById('choose-quantity').value);
                if(stock_quantity > selected_quantity){
                    document.querySelector('.choose-quantity').value++;
                    if(parseInt(document.getElementById('choose-quantity').value) === stock_quantity){
                        document.getElementById('warning-stock').classList.add('show');
                    }
                    chosen_quantity = parseInt(document.querySelector('.choose-quantity').value);
                }                
            });



            <?php

                 if(!isset($_SESSION['user_id'])){
                     echo "document.querySelector('.addToCart-btn').setAttribute('data-bs-target', '#login-overlay');
                           document.querySelector('.addToCart-btn').setAttribute('data-bs-toggle', 'modal');
                           document.querySelector('.addToCart-btn').type = 'Button';
                           document.querySelector('.buynow-btn').type = 'Button';
                           document.querySelector('.buynow-btn').setAttribute('data-bs-target', '#login-overlay');
                           document.querySelector('.buynow-btn').setAttribute('data-bs-toggle', 'modal');";
                     echo "document.getElementById('checkout-btn').removeAttribute('href');";
                 }
            ?>



            document.getElementById('description-tab').addEventListener('click', () => {
                document.getElementById('description-content').classList.remove('hide');
                document.getElementById('review-content').classList.add('hide');
            });
    
            document.getElementById('review-tab').addEventListener('click', () => {
                document.getElementById('review-content').classList.remove('hide');
                document.getElementById('description-content').classList.add('hide');
            });

        </script>
</body>
</html>