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
<html lang="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg">

    <title>Admin dashboard</title>

    <!-- Icon CSS Dependency -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/thinline.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- JS Dependency -->
    <script src="https://kit.fontawesome.com/55d23c11ec.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- Local Dependency -->
    <link href="/OPCS/resources/css/administrator/admin.css" rel="stylesheet" type="text/css">

    <style>
        body{
            background-image: url("/OPCS/resources/images/administrator/content.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        /* content section*/
        .visit-image{
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-top: 20px;
        }
        .profit-image{
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-top: 20px;
        }
        .sales-image{
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-top: 20px;
        }
        .order-image{
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-top: 20px;
        }
        .Group1{
            display: grid;
            grid-template-columns: 100px 1fr;
            align-items: center;
        }
        .Group1-2{
            margin-top: 15px;
            color: #00785c;
        }
        .Group2{
            display: grid;
            grid-template-columns: 100px 1fr;
            align-items: center;
        }
        .Group2-2{
            margin-top: 15px;
            color: #00785c;
        }
        .Group3{
            display: grid;
            grid-template-columns: 100px 1fr;
            align-items: center;
        }
        .Group3-2{
            margin-top: 15px;
            color: red;
        }
        .Group4{
            display: grid;
            grid-template-columns: 100px 1fr;
            align-items: center;
        }
        .Group4-2{
            margin-top: 15px;
            color: orange;
        }
        .visit{
            background-image: url("/OPCS/resources/images/administrator/Boximage-1.jpg");
        }
        .profit{
            background-color:sandybrown ;
        }
        .sales{
            background-color:gainsboro;
        }
        .order{
            background-color:darkseagreen;
        }
        h2{
            margin-left: 210px;
            opacity: 0.85;
        }
        .second-box i {
            font-size: 20px;
        }
        .box{
            margin-left: 210px;
            display: grid;
            grid-template-columns: 250px 250px 250px 250px;
            align-items: center;
        }
        .visit,.sales,.profit,.order{
            border-radius: 15px 15px 80px 15px;
            border-style: solid;
            border-color: white;
            padding-left: 20px;
            padding-right: 5px;
            padding-top: 5px;
            width: 210px;
            height: 185px;
        }
        #performance-graph{
            margin-left: 100px;
            border: none;
        }
        #sales-piechart{
            margin-left: 110px;
            float: left;
        }
        #chart_div{
            float: left;
            margin-top: 25px;
            margin-right: 20px;
        }
        #content{
            margin-left: 30px;
        }
    </style>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Month', 'Sales', 'Expenses'],
                ['Jan-March',  1000,      400],
                ['April-June',  1170,      460],
                ['July-September',  660,       1120],
                ['October-December',  1030,      540]
            ]);
            var options = {
                title: 'OP computer shop performance',
                curveType: 'function',
                legend: { position: 'bottom' },
                backgroundColor: 'transparent',
            };

            var chart = new google.visualization.LineChart(document.getElementById('performance-graph'));

            chart.draw(data, options);
        }
    </script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Category', 'Sales per month'],
                ['Desktop', 1],
                ['Laptop', 1.5],
                ['Keyboard & Mouse',3],
                ['Storage Device',2],
                ['Printer & Scanner',2.5],
            ]);

            var options = {
                title: 'OP Computer shop product sales percentage%',
                backgroundColor: 'transparent',
            };

            var chart = new google.visualization.PieChart(document.getElementById('sales-piechart'));

            chart.draw(data, options);
        }
    </script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawVisualization);

        function drawVisualization() {
            // Some raw data (not necessarily accurate)
            var data = google.visualization.arrayToDataTable([
                ['Month', 'Desktop', 'Laptop', 'Accessories','Average'],
                ['2004/05',  165,      938,         522,       614.6],
                ['2005/06',  135,      1120,        599,        682],
                ['2006/07',  157,      1167,        587,        623],
                ['2007/08',  139,      1110,        615,       609.4],
                ['2008/09',  136,      691,         629,       569.6]
            ]);

            var options = {
                title : 'Monthly product sale in OP computer shop company',
                vAxis: {title: 'Product'},
                hAxis: {title: 'Month'},
                seriesType: 'bars',
                series: {5: {type: 'line'}},
                backgroundColor:'transparent',
            };

            var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
    <script>
        function myFunction(){
            document.getElementById("myDropdown").classList.toggle("show");
        }
        function openside(){
            document.getElementById("side-bar").style.left = "0";
            document.getElementById("searchbar").style.marginLeft = "200px";
            document.getElementById("search-menu").style.gridTemplateColumns ="25px 174px 65rem 1rem";
        }
        function closeside(){
            document.getElementById("side-bar").style.left ="-100%";
            document.getElementById("searchbar").style.marginLeft = "0";
            document.getElementById("searchbar").style.maxWidth = "100%";
            document.getElementById("search-menu").style.gridTemplateColumns ="25px 174px 77rem 1rem";
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
                <li><a class="selected" href="/OPCS/en/administrator/admin"><img src="https://img.icons8.com/emoji/48/000000/house-emoji.png" class="function-image" alt="house-icon"/>Dashboard</a></li>
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
    <!-- content-->
    <div id="content">
        <div><h2>Welcome Back, Admin</h2></div>
        <div class="box">
            <div class="visit">
                <div style="font-size: 20px"><i>Total number of visiting</i></div>
                <div class="Group1">
                    <h3 style="margin-top: 40px">355678</h3>
                    <img src="https://img.icons8.com/clouds/100/000000/domain.png" class="visit-image" alt="visit-icon"/>
                </div>
                <div class="Group1-2">
                    <i class="fas fa-angle-double-up"></i>10%
                </div>
            </div>
            <div class="profit">
                <div style="font-size: 20px"><i>Total number of profit</i></div>
                <div class="Group2">
                    <h3 style="margin-top: 40px">55678</h3>
                    <img src="https://img.icons8.com/clouds/100/000000/economic-improvement--v2.png" class="profit-image" alt="profit-icon"/>
                </div>
                <div class="Group2-2">
                    <i class="fas fa-angle-double-up"></i>10%
                </div>
            </div>
            <div class="sales">
                <div style="font-size: 20px"><i>Total number of sales</i></div>
                <div class="Group3">
                    <h3 style="margin-top: 40px">888</h3>
                    <img src="https://img.icons8.com/external-konkapp-flat-konkapp/64/000000/external-computer-electronic-devices-konkapp-flat-konkapp.png" class="sales-image" alt="sales-icon"/>
                </div>
                <div class="Group3-2">
                    <i class="fas fa-angle-double-down"></i>5%
                </div>
            </div>
            <div class="order">
                <div style="font-size: 20px"><i>Total number of order</i></div>
                <div class="Group4">
                    <h3 style="margin-top: 40px">7800</h3>
                    <img src="https://img.icons8.com/external-wanicon-flat-wanicon/64/000000/external-order-food-delivery-wanicon-flat-wanicon.png" class="order-image" alt="order-icon"/>
                </div>
                <div class="Group4-2">
                    <i class="fas fa-equals">0%</i>
                </div>
            </div>
        </div>
        <div id="Graph">
            <div id="performance-graph" style="width: 1250px; height: 500px"></div>
            <div id="sales-piechart" style="width: 650px; height: 500px;"></div>
            <div id="chart_div" style="height:400px; width: 600px;"></div>
        </div>
    </div>

</div>


</body>
</html>
