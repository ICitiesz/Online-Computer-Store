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

    <title>Add New Product</title>

    <!-- JS Dependency -->
    <script src="https://kit.fontawesome.com/55d23c11ec.js" crossorigin="anonymous"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>

    <!-- CSS Dependency -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/thinline.css">
    <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />

    <!-- Local Dependency -->
    <link href="/OPCS/resources/css/administrator/admin.css" rel="stylesheet" type="text/css">
    <style>
        body{
            background-image: url("/OPCS/resources/images/administrator/content.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        /* content section */
        .second-box i {
            font-size: 20px;
        }
        .tittle{
            text-align: center;
            margin-left: 150px;
        }
        #Add-product{
            margin-left: 400px;
        }
        .section {
            margin-bottom: 14px;
            width:100%;
            float:left;
        }
        .label {
            float: left;
            margin-right: 10px;
            width: 150px;
        }
        .field {
            float: left;
            margin-top: 15px;
        }
        #Add-product input[type=text],textarea{
            width: 700px;
            height: 30px;
        }
        select{
            width: 708px;
            height: 35px;
        }
        input[type=number]{
            width: 700px;
            height: 25px;
        }
        input[type=file]{
            margin-top: 5px;
        }
        #Add-product textarea{
            height: 100px;
        }
        .submit{
            clear: left;
            text-align: center;
            margin-right: 200px;
        }
        input[type=submit]{
            width: 400px;
            height: 35px;
        }
        /* Modal section */
        #bg-modal{
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            display: none;
            justify-content: center;
            align-items: center;
        }
        #modal-content{
            width: 500px;
            height: 300px;
            background-color: #c7bdb1;
            opacity: 100;
            border-radius: 10px;
            text-align: center;
            position: relative;
        }
        #modal-content input{
            width: 50%;
            height: 70px;
            display: block;
            margin: 10px auto;
        }
        #close{
            position: absolute;
            top: 0;
            right: 20px;
            font-size: 50px;
            transform: rotate(45deg);
            cursor: pointer;
        }
    </style>
    <script>
        function myFunction(){
            document.getElementById("myDropdown").classList.toggle("show");
        }
        function openside(){
            document.getElementById("side-bar").style.left = "0";
            document.getElementById("searchbar").style.marginLeft = "200px";
            document.getElementById("Add-product").style.marginLeft="400px"
        }
        function closeside(){
            document.getElementById("side-bar").style.left ="-100%";
            document.getElementById("searchbar").style.marginLeft = "0";
            document.getElementById("searchbar").style.maxWidth ="100%";
            document.getElementById("Add-product").style.marginLeft="340px"

        }
        function supplierform(){
            document.getElementById("bg-modal").style.display = "flex";
        }
        function closeform(){
            document.getElementById("bg-modal").style.display ="none";
        }
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#product-image')
                        .attr('src', e.target.result)
                        .width(300)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
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
                            <li><a class="selected" href="/OPCS/en/administrator/add-product"><img src="https://img.icons8.com/plasticine/100/000000/plus-2-math.png" class="function-image" alt="Add-icon"/><div class="Add-product">Add New Product</div></a></li>
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
        <!-- content add a new product table-->
        <div>
            <div class="tittle"><h1><u>Add a New Product</u></h1></div>
            <form action="/OPCS/en/administrator/insert-product.php" method="POST" id="Add-product" enctype="multipart/form-data">
                <div class="container">
                    <div class="section">
                        <div class="label">
                            <h3>Product Name:</h3>
                        </div>
                        <div class="field">
                            <input type="text" name="product_name" required>
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Categories:</h3>
                        </div>
                        <div class="field">
                            <select name="category" required>
                                <option value="">Please Select</option>
                                <option value="1">Desktop</option>
                                <option value="2">Laptop</option>
                                <option value="3">Keyboard&Mouse</option>
                                <option value="4">Storage Device</option>
                                <option value="5">Printer&Scanner</option>
                            </select>
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Product Description:</h3>
                        </div>
                        <div class="field">
                            <textarea name="product_description" required></textarea>
                        </div>
                    </div>
                    <div class="section>">
                        <div class="label">
                            <h3>Brand:</h3>
                        </div>
                        <div class="field">
                            <input type="text" name="brand" required>
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Product SKU:</h3>
                        </div>
                        <div class="field">
                            <input type="number" name="product_sku" min="1" required>
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Stock Quantity:</h3>
                        </div>
                        <div class="field">
                            <input type="number" name="stock_quantity" min="1" max="15" required>
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Availability:</h3>
                        </div>
                        <div class="field">
                            <select name="Availability" required>
                                <option value="">Please Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Price:</h3>
                        </div>
                        <div class="field">
                            <input type="number" min="1" step="any" name="price" />
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Supplier:</h3>
                        </div>
                        <div class="field">
                            <select name="Supplier" id="supplier" required>
                                <option value="">Please Select</option>
                                <?php
                                    global $conn;

                                    $result = mysqli_query($conn,"SELECT * FROM supplier");
                                    while ($row = mysqli_fetch_array($result)){
                                        echo "<option value=$row[Supplier_ID]>$row[Supplier_Name]</option>";
                                        }
                                ?>
                            </select>
                        </div>
                        <a href="#" onclick="supplierform()"><img style="float: left;margin-top: 7px;font-size: 15px" src="https://img.icons8.com/bubbles/50/000000/add.png" /></a>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Product Image:</h3>
                        </div>
                        <div class="field" style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <img style="float: left; width: 200px;height: 200px" id="product-image" src="" alt="image"/>
                            <input type="file" name="file" onchange="readURL(this)">
                        </div>
                        <br>
                        <br>
                    </div>
                </div>
                <div class="submit"><input type="submit" value="save" name="save"></div>
                <br>
                <br>
            </form>
        </div>
        <!-- Modal section -->
        <div id="bg-modal">
            <div id="modal-content">
                <div id="close" onclick="closeform()">+</div>
                <div><h1><u>Add New Supplier</u></h1></div>
                <form action="/OPCS/en/administrator/insert-supplier.php" method="post">
                    <input type="text" placeholder="Supplier Name" name="newsupplier">
                    <button id="add-btn" style="border-radius: 12px;font-size: 30px;margin-top: 30px;background-color:#5a473a;cursor: pointer" type="submit">Add</button>
                </form>
            </div>
        </div>
        <!-- Footer
        <footer id="footer">
            <div class="footer-top">
                <div class="footer-logo">
                    <a href="index.html"><img src="Untitled-2.png" class="logo" alt=""></a>
                </div>
                <div class="cta-follow-us">
                    <div>Follow us on:</div>
                    <div class="cta-icon">
                        <a href="#" class="cta-icons"><i class="uil uil-facebook"></i></a>
                        <a href="#" class="cta-icons"><i class="uil uil-instagram"></i></a>
                        <a href="#" class="cta-icons"><i class="uil uil-twitter-alt"></i></a>
                    </div>
                </div>

                <div class=cta-content>
                    <ul>
                        <li><a href="#" class="cta-link">Terms and Condition</a></li>
                        <li><a href="#" class="cta-link">Shipping</a></li>
                        <li><a href="#" class="cta-link">Warranty Policy</a></li>
                        <li><a href="#" class="cta-link">Privacy Policy</a></li>
                        <li><a href="#" class="cta-link">Refund Policy</a></li>
                        <li><a href="#" class="cta-link">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <hr class="hr-line">
            <div class="footer-bottom">
                <p class="company-name copyright">OP Computer Shop</p>
                <p class="copyright">Copyright &copy; 2021, All Right Reserved</span>
            </div>
        </footer>
        -->
    </div>
</body>