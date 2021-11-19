<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");

    session_start();

    // Restrict access to non-accessible pages
    Utils\Utils::redirectIndex();
    Utils\Utils::redirectIndexAdmin();

    if (Utils\Utils::isMethodVarSet("post", "sub-btn")) {
        User\UserSession::addSessionVar("sub-btn", true);
    }

    if (Utils\Utils::isMethodVarSet("post", "search_key")) {
        User\UserSession::addSessionVar("search_key", $_POST["search_key"]);
    }


/* Discard any resubmission. */
    Utils\Utils::discardResubmission();
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg">

    <title>Rating</title>

    <!-- JS Dependency -->
    <script src="https://kit.fontawesome.com/55d23c11ec.js" crossorigin="anonymous"></script>

    <!-- CSS Dependency -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/thinline.css">

    <!-- Local Dependency -->
    <link href="/OPCS/resources/css/administrator/admin.css" rel="stylesheet" type="text/css">
    <style>
        body{
            background-image: url("/OPCS/resources/images/administrator/content.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        /* Rating section */
        table{
            border-collapse: collapse;
            border: 1px solid black;
            right: 0;
        }
        th,td{
            padding: 15px;
            border: 1px solid white;
            text-align: center;
            background: #48b1bd;
            color:white;
            margin-left: 200px;
        }
        tr{
            margin-left: 200px;
        }
    </style>
    <script>
        function myFunction(){
            document.getElementById("myDropdown").classList.toggle("show");
        }
        function openside(){
            document.getElementById("side-bar").style.left = "0";
            document.getElementById("searchbar").style.marginLeft = "200px";
            document.getElementById("member-review").style.marginLeft = "240px";
            document.getElementById("member-review").style.marginRight = "50px";
            document.getElementById("tittle").style.marginLeft = "150px";
        }
        function closeside(){
            document.getElementById("side-bar").style.left ="-100%";
            document.getElementById("searchbar").style.marginLeft = "0";
            document.getElementById("searchbar").style.maxWidth = "100%";
            document.getElementById("member-review").style.marginLeft = "auto";
            document.getElementById("member-review").style.marginRight = "auto";
            document.getElementById("tittle").style.marginLeft = "100px";
        }

    </script>
</head>
<body>
    <div class="wrapper">
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
                            <li><a href="/OPCS/en/administrator/our-products"><img src="https://img.icons8.com/plasticine/100/000000/move-by-trolley.png" class="function-image" alt="Byproduct-icon"/>Our Products</a></li>
                        </ul>
                    </li>
                    <li><a href="/OPCS/en/administrator/member"><img src="https://img.icons8.com/plasticine/64/000000/crowd.png" class="function-image" alt="Member-icon"/>Member</a></li>
                    <li><a class="selected" href="/OPCS/en/administrator/rating"><img src="https://img.icons8.com/cotton/64/000000/rating.png" class="function-image" alt="Rating-icon"/>Rating</a></li>
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
        <!-- content -->
        <div>
            <div id="tittle" style="text-align: center; margin-left: 150px"><u style="color: #BDD299"><h1 style="color: #48b1bd">Review</h1></u></div>
            <table id="member-review" style="margin-left: 240px; margin-right: 50px;margin-bottom: 75px">
                <tr>
                    <th>ID</th>
                    <th>Product_Name</th>
                    <th>Product_Image</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Average Rating</th>
                </tr>
                <?php
                global $conn;
                if (User\UserSession::isSessionSet("sub-btn")) {
                    $search_key = $_SESSION['search_key'];
                    $result = mysqli_query($conn,"SELECT p.Product_ID,p.Product_Name,p.Product_Image,p.Product_Brand,p.Price, SUM(r.Rating)/ count(r.Rating) as Average_Rating FROM review r INNER JOIN product p on r.Product_ID = p.Product_ID WHERE p.Product_Name LIKE '%$search_key%' GROUP BY p.Product_ID,p.Product_Name,p.Product_Image, p.Product_Brand, p.Price ORDER BY Average_Rating DESC ");

                    User\UserSession::removeSessionVar("sub-btn");
                    User\UserSession::removeSessionVar("search_key");
                }else{
                    $result = mysqli_query($conn,"SELECT p.Product_ID,p.Product_Name,p.Product_Image,p.Product_Brand,p.Price, SUM(r.Rating)/ count(r.Rating) as Average_Rating FROM review r INNER JOIN product p on r.Product_ID = p.Product_ID GROUP BY p.Product_ID,p.Product_Name,p.Product_Image, p.Product_Brand, p.Price ORDER BY Average_Rating DESC ");
                }
                while ($row=mysqli_fetch_array($result)){
                    $average_rating = $row['Average_Rating'];
                    $wholestar = floor($average_rating);
                    $halfstar = round( $average_rating * 2 ) % 2;
                    $rating = str_repeat('<img src="https://img.icons8.com/fluency/50/000000/filled-star.png"/>', $wholestar);
                    if ($halfstar){
                        $rating .='<img src="https://img.icons8.com/color/50/000000/star-half-empty.png"/>';
                    }
                    echo '<tr>'; // alternative way is : echo ‘<trbgcolor="#99FF66">’;
                    echo "<td>";
                    echo "$row[Product_ID]";
                    echo "</td>";
                    echo "<td>";
                    echo "$row[Product_Name]";
                    echo "</td>";
                    echo "<td>";
                    echo "<img style='width: 100px;height: 100px' src='/OPCS/data/product_images/$row[Product_Image]'>";
                    echo "</td>";
                    echo "<td>";
                    echo "$row[Product_Brand]";
                    echo "</td>";
                    echo "<td>";
                    echo "$row[Price]";
                    echo "</td>";
                    echo "<td>";
                    echo $rating;
                    echo "</td>";
                    echo '</tr>';
                }
                mysqli_close($conn);//to close the database connection

                ?>
            </table>
        </div>
    </div>
</body>