<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");

    session_start();

    // Restrict access to non-accessible pages
    Utils\Utils::redirectIndex();
    Utils\Utils::redirectIndexAdmin();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/OPCS/resources/images/dark-theme/store_logo-dark_theme.svg">
    <title>Document</title>

    <style>
    .Add-product{
        margin-left: 350px;
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
    .Add-product input[type=text],textarea{
        width: 700px;
        height: 30px;
    }
    select{
        width: 708px;
        height: 35px;
    }
    textarea{
        font-family: verdana, sans-serif;
    }
    input[type=number]{
        width: 700px;
        height: 25px;
    }
    input[type=file]{
        margin-top: 5px;
    }
    .Add-product textarea{
        height: 80px;
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
    <!-- JS Dependency -->
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>

    <!-- CSS Dependency -->
    <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />

</head>
<body>
    <?php function print_supplier($supplier_ID){
        global $conn;

        $result = mysqli_query($conn,"SELECT * FROM supplier");
        while ($row=mysqli_fetch_array($result)){
            if ($supplier_ID === $row['Supplier_ID']){
                $select = 'selected';
            }
            else{
                $select = '';
            }
            echo "<option value=$row[Supplier_ID] $select>$row[Supplier_Name]</option>";
        }
    }; 
    ?>
<script>
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
                    .width(200)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php
    global $conn;
    $id = (int)$_GET['Product_ID']; //intval — Get the integer value of a variable
    $result = mysqli_query($conn,"SELECT * FROM product WHERE Product_ID=$id");
    while($row = mysqli_fetch_array($result))
    {
?>
        <div align="center"><h1>Edit</h1></div>
        <div class="Add-product">
            <form action="/OPCS/en/administrator/update-product" method="post" enctype="multipart/form-data">
                <div class="container">
                    <div class="section">
                        <div class="label">
                            <h3>Product Name:</h3>
                        </div>
                        <div class="field">
                            <input type="text" name="product_name" required value="<?php echo $row['Product_Name']?>">
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Categories:</h3>
                        </div>
                        <div class="field">
                            <select name="category" required>
                                <option value="">Please Select</option>
                                <option value="1"<?php if ($row['Category_ID'] === "1") { ?>
                                    selected="selected"<?php } ?>
                                >Desktop
                                </option>
                                <option value="2"<?php if ($row['Category_ID'] === "2") { ?>
                                    selected="selected"<?php } ?>
                                >Laptop
                                </option>
                                <option value="3"<?php if ($row['Category_ID'] === "3") { ?>
                                    selected="selected"<?php } ?>
                                >Keyboard&Mouse
                                </option>
                                <option value="4"<?php if ($row['Category_ID'] === "4") { ?>
                                    selected="selected"<?php } ?>
                                >Storage Device
                                </option>
                                <option value="5"<?php if ($row['Category_ID'] === "5") { ?>
                                    selected="selected"<?php } ?>
                                >Printer&Scanner
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Product Description:</h3>
                        </div>
                        <div class="field">
                            <textarea name="product_description" required><?php echo $row['Product_Description'] ?></textarea>
                        </div>
                    </div>
                    <div class="section>">
                        <div class="label">
                            <h3>Brand:</h3>
                        </div>
                        <div class="field">
                            <input type="text" name="brand" required value="<?php echo $row['Product_Brand'];?>">
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Product SKU:</h3>
                        </div>
                        <div class="field">
                            <input type="text" name="product_sku" min="1" required value="<?php echo $row['Product_SKU'];?>">
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Stock Quantity:</h3>
                        </div>
                        <div class="field">
                            <input type="number" name="stock_quantity" min="1" max="15" required value="<?php echo $row['Product_Quantity'];?>">
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Availability:</h3>
                        </div>
                        <div class="field">
                            <select name="Availability" required>
                                <option value="">Please Select</option>
                                <option value="Yes" <?php if ($row['Availability'] === "Yes") { ?>
                                    selected="selected"<?php } ?>
                                >Yes
                                </option>
                                <option value="No"<?php if ($row['Availability'] === "No") { ?>
                                    selected="selected"<?php } ?>
                                >No
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Price:</h3>
                        </div>
                        <div class="field">
                            <input type="number" min="1" step="any" name="price" value="<?php echo $row['Price']?>">
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Supplier:</h3>
                        </div>
                        <div class="field">
                            <select name="Supplier" id="supplier" required>
                                <option value="">Please Select</option>
                                <?php print_supplier($row['Supplier_ID']); ?>
                            </select>
                        </div>
                        <a href="#" onclick="supplierform()"><img style="float: left;margin-top: 7px;font-size: 15px" src="https://img.icons8.com/bubbles/50/000000/add.png" /></a>
                    </div>
                    <div class="section">
                        <div class="label">
                            <h3>Product Image:</h3>
                        </div>
                        <div class="field" style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <img style="float: left; width: 200px;height: 200px" id="product-image" src="/OPCS/data/product_images/<?php echo $row['Product_Image'];?>" alt="image"/>
                            <input type="file" name="new_file" onchange="readURL(this)">
                            <input type="hidden" name="old_file" value="<?php echo $row['Product_Image'];?>">
                        </div>
                        <br>
                        <br>
                    </div>
                    <div class="submit"><input type="submit" value="save" name="save"></div>
                    <br>
                    <br>
                </div>
                <input type="hidden" name="Product_ID" value="<?php echo $row['Product_ID'] ?>">
            </form>
        </div>
        <div id="bg-modal">
            <div id="modal-content">
                <div id="close" onclick="closeform()">+</div>
                <div><h1><u>Add New Supplier</u></h1></div>
                <form action="insert-supplier.php" method="post">
                    <input type="text" placeholder="Supplier Name" name="newsupplier">
                    <button id="add-btn" style="border-radius: 12px;font-size: 30px;margin-top: 30px;background-color:#5a473a;cursor: pointer" type="submit">Add</button>
                </form>
            </div>
        </div>
<?php
    }
mysqli_close($conn);
?>
</body>
</html>

