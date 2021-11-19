<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");

    session_start();

    if (Utils\Utils::isMethodVarSet("post", "sub-btn")) {
        User\UserSession::addSessionVar("sub-btn", true);
    }

    if (Utils\Utils::isMethodVarSet("post", "search_key")) {
        User\UserSession::addSessionVar("search_key", $_POST["search_key"]);
    }

    /* Discard any resubmission. */
    Utils\Utils::discardResubmission();
    Utils\Utils::redirectIndexAdmin();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg">

    <title>Our Products</title>

    <!-- JS Dependency -->
    <script src="https://kit.fontawesome.com/55d23c11ec.js" crossorigin="anonymous"></script>

    <!-- CSS Dependency -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/thinline.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- Local Dependency -->
    <link href="/OPCS/resources/css/administrator/admin.css" rel="stylesheet" type="text/css">
    <style>
        body{
            background-image: url("/OPCS/resources/images/administrator/content.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        /* Our Products */
        .my-product-btn i{
            font-size: 2rem;
        }
        #my-product-content{
            display: grid;
            grid-template-columns: repeat(4, minmax(190px, 1fr));
            margin-left: 220px;
            gap: 25px;
            /* max-width: 1100px; */
        }
        .my-products{
            display: grid;
            grid-template-rows: repeat(2, 1fr);
            justify-items: center;
            text-align: center;
        }

        .my-product-img{
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .my-product-img img{
            width: 100%;
            border-radius: 10px 10px 0 0;
        }

        .my-product-info{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-evenly;
            gap: 15px;
        }

        .product-name{
            font-size: 1.25rem;
            display: -webkit-box;
            /* determine the direction the content go, and to let the word go downward */
            -webkit-box-orient: vertical;
            /* Determining how many lines to show */
            -webkit-line-clamp: 2;
            overflow: hidden;
            width: 75%;
            text-align: center;
        }

        .my-product-stock{
            display: flex;
            justify-content: space-between;
            width: 75%;
        }

        .price,
        .stock{
            font-size: 1.125rem;
        }
        .my-product-btn{
            display: grid;
            grid-template-columns: repeat(2, 100px);
            justify-items: center;
        }
    </style>
    <script>
        function myFunction(){
            document.getElementById("myDropdown").classList.toggle("show");
        }
        function openside(){
            document.getElementById("side-bar").style.left = "0";
            document.getElementById("searchbar").style.marginLeft = "200px";
            document.getElementById("my-product-content").style.marginLeft = "220px";
        }
        function closeside(){
            document.getElementById("side-bar").style.left ="-100%";
            document.getElementById("searchbar").style.marginLeft = "0";
            document.getElementById("searchbar").style.maxWidth ="100%";
            document.getElementById("my-product-content").style.marginLeft = "30px";
        }
    </script>
</head>
<body>
    <div id="wrapper">
        <!--sidebar-->
        <nav id="side-bar" class="sidebar">
            <div class="toggle-menu">
                <div class="close-btn" onclick="closeside()">Menu</div>
            </div>
            <hr>
            <?php
            $user = new User\User(User\UserSession::getSessionUserID());
            ?>

            <div class="profile">
                <img src="<?php
                // https://img.icons8.com/doodle/48/000000/test-account.png
                if ($user->getProfilePic() === null) {
                    echo "/OPCS/resources/images/dark-theme/profile_user-dark_theme.png";
                } else {
                    echo "/OPCS/data/user_profile_images/" . $user->getProfilePic();
                }
                ?>" class="image" alt="Datoshane"/>
                <div class="name"><?php echo $user->getUsername(); ?></div>
                <div class="email"><?php echo $user->getEmail(); ?></div>
                <br>
                <hr>
            </div>
            <div class="Menu">
                <ul>
                    <li><a href="/OPCS/en/administrator/admin"><img src="https://img.icons8.com/emoji/48/000000/house-emoji.png" class="function-image" alt="house-icon"/>Dashboard</a></li>
                    <li><a href="/OPCS/en/administrator/analytics"><img src="https://img.icons8.com/external-kiranshastry-lineal-color-kiranshastry/64/000000/external-analytics-business-kiranshastry-lineal-color-kiranshastry-4.png" class="function-image" alt="analytics-icon"/>Analytics</a></li>
                    <li>
                        <a href="#" onclick="myFunction()" class="product-btn"><img src="https://img.icons8.com/plasticine/100/000000/laptop--v2.png" class="function-image" alt="Product-icon"/>Product
                            <i class="fas fa-caret-down"></i>
                        </a>
                        <ul id="myDropdown" class="element-product">
                            <li><a href="/OPCS/en/administrator/add-product"><img src="https://img.icons8.com/plasticine/100/000000/plus-2-math.png" class="function-image" alt="Add-icon"/><div class="Add-product">Add New Product</div></a></li>
                            <li><a class="selected" href="/OPCS/en/administrator/our-products"><img src="https://img.icons8.com/plasticine/100/000000/move-by-trolley.png" class="function-image" alt="Byproduct-icon"/>Our Products</a></li>
                        </ul>
                    </li>
                    <li><a href="/OPCS/en/administrator/member"><img src="https://img.icons8.com/plasticine/64/000000/crowd.png" class="function-image" alt="Member-icon"/>Member</a></li>
                    <li><a href="/OPCS/en/administrator/rating"><img src="https://img.icons8.com/cotton/64/000000/rating.png" class="function-image" alt="Rating-icon"/>Rating</a></li>
                    <li><a href="/OPCS/en/administrator/purchase-history"><img src="https://img.icons8.com/external-wanicon-flat-wanicon/64/000000/external-history-university-courses-wanicon-flat-wanicon.png" class="function-image" alt="Rating-icon"/>History</a></li>
                </ul>
            </div>
        </nav>
        <!-- search bar-->
        <div id="searchbar">
            <div id="search-menu">
                <form method="post" id="search-form">
                        <div class="search-input">
                            <button type="button" class="open-btn" onclick="openside()"><i class="fas fa-bars"></i></button>
                            <input type="text" class="search-box" name="search_key" placeholder="Search...">
                            <button type="submit" name="sub-btn" style="background: none;border: none;color: white"><i class="fas fa-search"></i></button>
                        </div>
                    
                </form>
                <div class="user-icon">
                    <a href="/OPCS/en/index" class="Message-icon"><i class="far fa-user"></i></a>
                </div>
            </div>
        </div>
        <!-- Our Products-->
        <div id="My-product">
            <div id="tittle"><h1 style="text-align: center;margin-left: 180px;color: #BDD299">Our Products</h1></div>
            <div id="my-product-content">
            <?php
            global $conn;

            if (User\UserSession::isSessionSet("sub-btn")){
                $search_key = $_SESSION['search_key'];
                $result = mysqli_query($conn,"SELECT * FROM product p INNER JOIN category c on p.Category_ID = c.Category_ID INNER JOIN supplier s on p.Supplier_ID = s.Supplier_ID WHERE p.Product_Name LIKE '%$search_key%'ORDER BY p.Product_ID ASC ");
                User\UserSession::removeSessionVar("sub-btn");
                User\UserSession::removeSessionVar("search_key");
            }else{
                $result = mysqli_query($conn,"SELECT * FROM product p INNER JOIN category c on p.Category_ID = c.Category_ID INNER JOIN supplier s on p.Supplier_ID = s.Supplier_ID ORDER BY p.Product_ID ASC ");
            }

            while($row = mysqli_fetch_array($result)){

                $date = '
                    
                        <div class="my-products" style="border: 1px solid dimgrey;border-radius: 10px">
                            <div class="my-product-img">
                                <a href=""><img src="/OPCS/data/product_images/'. $row['Product_Image'].'" alt=""></a>
                            </div>
                            <div class="my-product-info">
                                <div class="product-name">
                                    '.$row['Product_Name'].'
                                </div>
                                <div class="my-product-stock">
                                    <div class="price">RM'.$row['Price'].'</div>
                                    <div class="stock">Stocks: '.$row['Product_Quantity'].'</div>
                                </div>
                                <div class="my-product-btn">
                                    <div><a href="/OPCS/en/administrator/edit-product?Product_ID='.$row['Product_ID'].'"><i class="uil uil-edit-alt"></i></a></div>
                                    <div><a onclick="return confirm(\'Delete '.$row['Product_Name'].' record?\');" href="/OPCS/en/administrator/delete-product?Product_ID='.$row['Product_ID'].'&amp;Product_Image='.$row['Product_Image'].'"><i class="uil uil-trash-alt"></i></a></div>
                                </div>
                            </div>
                        
                    </div>';
                echo $date;
                }
            ?>
            </div>
        </div>
        <br>
        <br>
    </div>
<!-- Footer -->
</body>