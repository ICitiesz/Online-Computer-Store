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
<?php
global $conn;

function order_details($conn, $count, $order_id){
    $itemNum = 0;
    $detail_query = "SELECT
                            product.Product_Name,
                            product.Product_Image,
                            member_order_details.Quantity,
                            product.Price,
                            (product.Price * member_order_details.Quantity) AS Subtotal
                        FROM
                            member_order
                        INNER JOIN billing_information ON member_order.Billing_ID = billing_information.Billing_ID
                        INNER JOIN member_order_details ON member_order_details.Member_Order_ID = member_order.Member_Order_ID
                        INNER JOIN product ON member_order_details.Product_ID = product.Product_ID
                        WHERE member_order_details.member_order_id = '$order_id'";
    $itemResult = mysqli_query($conn, $detail_query);

    while($item = mysqli_fetch_assoc($itemResult)){
        ++$itemNum;
        $itemRecord = "
                            <tr class=\"hidden $count\">
                                <td class=\"detail-row\" colspan=\"11\">
                                    <div class=\"container\">
                                        <div>#". $itemNum ."</div>
                                        <div><img src=\"/OPCS/data/product_images/". $item['Product_Image'] ."\" alt=\"\"></div>
                                        <div class=\"details\">". $item['Product_Name'] ."</div>
                                        <div class=\"details\">". $item['Quantity'] ."</div>
                                        <div class=\"details\">RM ". $item['Price'] ."</div>
                                        <div class=\"details\">RM ". $item['Subtotal'] ."<div>
                                    </div>
                                </td>
                            </tr>";
        echo $itemRecord;
    }
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg">

    <title>Member</title>

    <!-- JS Dependency -->
    <script src="https://kit.fontawesome.com/55d23c11ec.js" crossorigin="anonymous"></script>

    <!-- CSS Dependency -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- Local Dependency -->
    <link href="/OPCS/resources/css/administrator/admin.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/OPCS/resources/css/administrator/admin-member.css">
    <script>
        function myFunction(){
            document.getElementById("myDropdown").classList.toggle("show");
        }
        function openside(){
            document.getElementById("side-bar").style.left = "0";
            document.getElementById("searchbar").style.marginLeft = "200px";
            document.querySelector('table').classList.remove('centered-table');
            document.getElementById("tittle").style.marginLeft = "180px";
        }
        function closeside(){
            document.getElementById("side-bar").style.left ="-100%";
            document.getElementById("searchbar").style.marginLeft = "0";
            document.getElementById("searchbar").style.maxWidth = "100%";
            document.querySelector('table').classList.add('centered-table');
            document.getElementById("tittle").style.marginLeft = "0";
            document.getElementById("tittle").style.marginRight = "120px";
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
                <li><a class="selected" href="/OPCS/en/administrator/member"><img src="https://img.icons8.com/plasticine/64/000000/crowd.png" class="function-image" alt="Member-icon"/>Member</a></li>
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
    <!-- content -->
    <div>
        <div id="tittle"><h1 style="text-align: center;margin-left: 180px;color: #48b1bd">Member Order</h1></div>
        <table class="product-table">
            <thead>
            <tr>
                <td>#</td>
                <td>Order ID</td>
                <td>Recipient Name</td>
                <td colspan="4">Address</td>
                <td>Contact Number</td>
                <td>Status</td>
                <td>Date Created</td>
                <td></td>
            </tr>

            </thead>
            <?php
            global $conn;

            if (User\UserSession::isSessionSet("sub-btn")) {
                $search_key = $_SESSION['search_key'];
                $order_query = "SELECT DISTINCT 
                                                member_order.User_ID,
                                                member_order.Member_Order_ID,
                                                billing_information.Recipient_Name, 
                                                member_order.Delivery_Status, 
                                                billing_information.Billing_Address, 
                                                billing_information.Recipient_Contact_Number, 
                                                member_order.Date 
                                        FROM member_order 
                                        INNER JOIN billing_information ON billing_information.Billing_ID = member_order.Billing_ID 
                                        INNER JOIN member_order_details ON member_order_details.Member_Order_ID = member_order.Member_Order_ID 
                                        INNER JOIN product ON member_order_details.Product_ID = product.Product_ID 
                                        WHERE billing_information.Recipient_Name LIKE '%$search_key%'";
                User\UserSession::removeSessionVar("sub-btn");
                User\UserSession::removeSessionVar("search_key");
            }else{
                $order_query = "SELECT DISTINCT 
                                                member_order.User_ID,
                                                member_order.Member_Order_ID,
                                                billing_information.Recipient_Name, 
                                                member_order.Delivery_Status, 
                                                billing_information.Billing_Address, 
                                                billing_information.Recipient_Contact_Number, 
                                                member_order.Date 
                                        FROM member_order 
                                        INNER JOIN billing_information ON billing_information.Billing_ID = member_order.Billing_ID 
                                        INNER JOIN member_order_details ON member_order_details.Member_Order_ID = member_order.Member_Order_ID 
                                        INNER JOIN product ON member_order_details.Product_ID = product.Product_ID";
            }
            $result = mysqli_query($conn, $order_query);
            $count = 0;
            while($row = mysqli_fetch_assoc($result)){
                $itemNum = 0;
                ++$count;
                echo "<tr>
                                <td>$count</td>
                                <td>". $row['Member_Order_ID'] ."</td>
                                <td>". $row['Recipient_Name'] ."</td>
                                <td colspan=\"4\">".$row['Billing_Address']."</td>
                                <td>". $row['Recipient_Contact_Number'] ."</td>
                                <td> 
                                    <input type=\"text\" hidden name=\"MemberOrderID\" value=\"". $row['Member_Order_ID'] ."\">
                                    <div class='flex-status'>
                                        <select class=\"selection\" name=\"status\" >";

                if ($row['Delivery_Status'] === "Preparing"){
                    echo '<option value="Preparing" selected>Preparing</option>
                                        <option value="Delivering">Delivering</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancelled">Cancelled</option>';
                }
                else if ($row['Delivery_Status'] === 'Delivering'){
                    echo '<option value="Preparing">Preparing</option>
                                        <option value="Delivering" selected>Delivering</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancelled">Cancelled</option>';
                }
                else if ($row['Delivery_Status'] === 'Completed'){
                    echo '<option value="Completed" selected>Completed</option>';
                }
                else {
                    echo '<option value="Cancelled" selected>Cancelled</option>';

                }


                echo "      </select>
                                        <a class=\"hidden orange-btn\" href=\"/OPCS/en/administrator/update_delivery?OrderID=". $row['Member_Order_ID']."&amp;status=\" >Update Status</a>
                                        </div>
                                </td>
                                <td>". $row['Date'] ."</td>
                                <td><div class=\"uil uil-angle-down view-more\"></div></td>
                            </tr>
                            <tr class=\"hidden $count hidden-header\">
                                <td class=\"detail-row\" colspan=\"11\">
                                    <div class=\"container\">
                                        <div class=\"details\">#</div>
                                        <div class=\"details\">Product Image</div>
                                        <div class=\"details\">Product Name</div>
                                        <div class=\"details\">Quantity</div>
                                        <div class=\"details\">Item Price</div>
                                        <div class=\"details\">Subtotal<div>
                                    </div>
                                </td>
                            </tr>
                        ";
                // echo $record;

                // Printing Details
                order_details($conn, $count, $row['Member_Order_ID']);

            };

            ?>
        </table>
    </div>

</div>
<script>
    function showDetails(item){
        var itemClass = item.toString();
        var detail_item = document.getElementsByClassName(itemClass);
        for (let x = 0; x < detail_item.length; x++) {
            detail_item[x].classList.toggle('hidden');

        }
    };

    var view_more = document.getElementsByClassName('view-more');
    for (let i = 0; i < view_more.length; i++) {
        view_more[i].addEventListener('click', ()=>{
            view_more[i].classList.toggle('rotate');
            showDetails(i + 1);
        })

    };

    var updateCheck = document.getElementsByClassName('selection');
    var updateBtn = document.getElementsByClassName('orange-btn');
    var hrefValue =[];
    for (let index = 0; index < updateCheck.length; index++) {
        updateCheck[index].addEventListener('change', ()=>{
            updateBtn[index].classList.remove('hidden');
            hrefValue[index] = document.getElementsByClassName('selection')[index].value;
        });
        updateBtn[index].addEventListener('click', () =>{
            updateBtn[index].href += hrefValue[index];
        })
    };
</script>
</body>