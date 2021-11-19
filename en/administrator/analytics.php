<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");

    session_start();

    // Restrict access to non-accessible pages
    Utils\Utils::redirectIndex();
    Utils\Utils::redirectIndexAdmin();

    /* Discard any resubmission. */
    Utils\Utils::discardResubmission();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg">

    <title>Analytics</title>

    <!-- JS Dependency -->
    <script src="https://kit.fontawesome.com/55d23c11ec.js" crossorigin="anonymous"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <!-- CSS Dependency -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/thinline.css">

    <!-- Local Dependency -->
    <link href="/OPCS/resources/css/administrator/admin.css" rel="stylesheet" type="text/css">

    <style>
        #content{
            margin-left: 200px;
            justify-content: space-evenly;
        }
    </style>
    <script>
        function myFunction(){
            document.getElementById("myDropdown").classList.toggle("show");
        }
        function openside(){
            document.getElementById("side-bar").style.left = "0";
            document.getElementById("searchbar").style.marginLeft = "200px";
            document.getElementById("content").style.marginLeft ="200px"
        }
        function closeside(){
            document.getElementById("side-bar").style.left ="-100%";
            document.getElementById("searchbar").style.marginLeft = "0";
            document.getElementById("searchbar").style.maxWidth = "100%";
            document.getElementById("content").style.marginLeft ="100px"
        }
    </script>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("Most-view", {
                theme: "dark2", // "light1", "light2", "dark1"
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "Top 10 Most Viewed OP Computer shop Product"
                },
                axisX: {
                    margin: 10,
                    labelPlacement: "inside",
                    tickPlacement: "inside"
                },
                axisY2: {
                    title: "Views (in billion)",
                    titleFontSize: 14,
                    includeZero: true,
                    suffix: "k"
                },
                data: [{
                    type: "bar",
                    axisYType: "secondary",
                    yValueFormatString: "#,###.##k",
                    indexLabel: "{y}",
                    dataPoints: [
                        { label: "Acer Nitro-5", y: 3.25 },
                        { label: "Lenovo Think pad", y: 3.32 },
                        { label: "Asus TUF Dash F15", y: 3.63 },
                        { label: "Alienware M17 R3", y: 3.72 },
                        { label: "Acer Swift 3", y: 3.90 },
                        { label: "HP Envy 13", y: 4.32 },
                        { label: "Dell XPS 13", y: 4.66 },
                        { label: "Apple Macbook pro", y: 4.91 },
                        { label: "Asus ROG strix G17", y: 6.13 },
                        { label: "MSI GF63", y: 6.88 }
                    ]
                }]
            });
            chart.render();

            var charts = new CanvasJS.Chart("expenses", {
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                exportEnabled: true,
                animationEnabled: true,
                title: {
                    text: "Overall Expenses of OP Computer Shop"
                },
                data: [{
                    type: "pie",
                    startAngle: 25,
                    toolTipContent: "<b>{label}</b>: {y}%",
                    showInLegend: "true",
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - {y}%",
                    dataPoints: [
                        { y: 51.08, label: "Salary" },
                        { y: 27.34, label: "Inventory" },
                        { y: 10.62, label: "Shipping" },
                        { y: 5.02, label: "Maintenance" },
                        { y: 4.07, label: "Travel" },
                        { y: 1.22, label: "Insurance" },
                        { y: 0.44, label: "Others" }
                    ]
                }]
            });
            charts.render()

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
                <li><a class="selected" href="/OPCS/en/administrator/analytics"><img src="https://img.icons8.com/external-kiranshastry-lineal-color-kiranshastry/64/000000/external-analytics-business-kiranshastry-lineal-color-kiranshastry-4.png" class="function-image" alt="analytics-icon"/>Analytics</a></li>
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
                <li><a href="/OPCS/en/administrator/purchase-history"><img src="https://img.icons8.com/external-wanicon-flat-wanicon/64/000000/external-history-university-courses-wanicon-flat-wanicon.png" class="function-image" alt="Rating-icon"/>History</a></li>
            </ul>
        </div>
    </nav>
    <!-- search bar-->
    <div id="searchbar">
        <div id="search-menu">
            <div class="search-input">
                <button class="open-btn" onclick="openside()"><i class="fas fa-bars"></i></button>
                <input type="text" class="search-box" placeholder="Search...">
                <a href="#" class="Message-icon"><i class="fas fa-search"></i></a>
            </div>
            <div class="user-icon">
                <a href="/OPCS/en/index" class="Message-icon"><i class="far fa-user"></i></a>
            </div>
        </div>
    </div>
    <!-- content -->
    <div id="content">
        <div id="Most-view" style="height: 370px; width: 100%;"></div>
        <br>
        <div id="expenses" style="height: 370px; width: 100%;"></div>
        <br>
    </div>
</div>
</body>