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

    <title>Purchase History</title>

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
        /* History Section */
        #title{
            text-align: center;
            background: #ADEFD1FF;
            width: 700px;
            padding: 10px;
            border-radius: 25px;
            margin-left: 500px;
            margin-top: 20px;
            color: #00203FFF;
            font-size: 20px;
        }
        #container{
            display: grid;
            grid-template-columns: repeat(2,1fr);
            margin-left: 220px;
            gap: 20px;
            margin-top: 20px;
        }
        .history{
            border: 1px solid black;
            border-radius: 15px;
            max-width: 90%;
            padding: 10px;
            margin-top:10px;
            overflow: hidden;
            background: #FAEBEFFF;
            color: #333D79FF;
        }
        .section{
            float: left;
            width: 100%;
            padding-top: 10px;
        }
        .label{
            float: left;
        }
        .field{
            float: left;
            border-radius: 10px;
            background:#FAEBEFFF;
        }
    </style>
    <script>
        function myFunction(){
            document.getElementById("myDropdown").classList.toggle("show");
        }
        function openside(){
            document.getElementById("side-bar").style.left = "0";
            document.getElementById("searchbar").style.marginLeft = "200px";
            document.getElementById("container").style.marginLeft = "220px";
            document.getElementById("title").style.marginLeft = "500px";
        }
        function closeside(){
            document.getElementById("side-bar").style.left ="-100%";
            document.getElementById("searchbar").style.marginLeft = "0";
            document.getElementById("searchbar").style.maxWidth = "100%";
            document.getElementById("container").style.marginLeft = "80px";
            document.getElementById("title").style.marginLeft = "400px";
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
                <li><a href="/OPCS/en/administrator/rating"><img src="https://img.icons8.com/cotton/64/000000/rating.png" class="function-image" alt="Rating-icon"/>Rating</a></li>
                <li><a class="selected" href="/OPCS/en/administrator/purchase-history"><img src="https://img.icons8.com/external-wanicon-flat-wanicon/64/000000/external-history-university-courses-wanicon-flat-wanicon.png" class="function-image" alt="Rating-icon"/>History</a></li>
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
    <div id="content">
        <div id="title">Purchase History</div>
        <div id="container">
        <?php
            global $conn;

            if (User\UserSession::isSessionSet("sub-btn")){
                $search_key = $_SESSION['search_key'];
                $result = mysqli_query($conn, "SELECT u.User_ID,u.Username,bi.Billing_Address,mo.Date,mo_d.Member_Order_Details_ID,mo_d.Quantity,(p.Price * mo_d.Quantity) as Amount FROM
            member_order_details mo_d INNER JOIN member_order mo ON mo_d.Member_Order_ID = mo.Member_Order_ID
            INNER JOIN user u ON mo.User_ID = u.User_ID INNER JOIN billing_information bi ON mo.Billing_ID = bi.Billing_ID
            INNER JOIN product p ON mo_d.Product_ID = p.Product_ID
            WHERE u.Username LIKE '%$search_key%' AND u.Role ='member' AND mo.Delivery_Status = 'Completed'");
                User\UserSession::removeSessionVar("sub-btn");
                User\UserSession::removeSessionVar("search_key");
            }else{
                $result = mysqli_query($conn, "SELECT u.User_ID,u.Username,bi.Billing_Address,mo.Date,mo_d.Member_Order_Details_ID,mo_d.Quantity,(p.Price * mo_d.Quantity) as Amount FROM
            member_order_details mo_d INNER JOIN member_order mo ON mo_d.Member_Order_ID = mo.Member_Order_ID
            INNER JOIN user u ON mo.User_ID = u.User_ID INNER JOIN billing_information bi ON mo.Billing_ID = bi.Billing_ID
            INNER JOIN product p ON mo_d.Product_ID = p.Product_ID
            WHERE u.Role ='member' AND mo.Delivery_Status = 'Completed'");
            }
            while ($row = mysqli_fetch_array($result)){
                $data = '<div class="history">
                            <div class="section">
                                <div class="label">
                                    Username:
                                </div>
                                
                                <br>
                                
                                <div class="field">
                                    '.$row['Username'].'
                                </div>
                            </div>
                            <div class="section">
                                <div class="label">
                                    Address:
                                </div>
                                
                                <br>
                                
                                <div class="field">
                                    '.$row['Billing_Address'].'
                                </div>
                            </div>
                            <div class="section">
                                <div class="label">
                                    Date:
                                </div>
                                
                                <br>
                                
                                <div class="field">
                                    '.$row['Date'].'
                                </div>
                            </div>
                            <div class="section">
                                <div class="label">
                                    Invoice_ID:
                                </div>
                                
                                <br>
                                
                                <div class="field">
                                    INV00'.$row['Member_Order_Details_ID'].'
                                </div>
                            </div>
                            <div class="section">
                                <div class="label">
                                    Number of purchased:
                                </div>
                                
                                <br>
                                
                                <div class="field">
                                    '.$row['Quantity'].'
                                </div>
                            </div>
                            <div class="section">
                                <div class="label">
                                    Amount Paid:
                                </div>
                                
                                <br>
                                
                                <div class="field">
                                    RM'.$row['Amount'].'
                                </div>
                            </div>
                        </div>';
                echo $data;
            };

        ?>
        </div>
    </div>
</div>
</body>