<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");

    session_start();

    // Restrict access to non-accessible pages
    Utils\Utils::redirectIndex();
    Utils\Utils::redirectIndexAdmin();

    global $conn;
    $new_image = $_FILES['new_file']['name'];
    $old_image = $_POST['old_file'];

    if ($new_image !== ''){
        $filename = $_FILES['new_file']['name'];
        $tempname = $_FILES["new_file"]["tmp_name"];
        $temp = explode(".",$filename);
        $update_image = round(microtime(true)) . '.' . end($temp);
        move_uploaded_file($tempname, $_SERVER["DOCUMENT_ROOT"] . "/OPCS/data/product_images/".$update_image);
        unlink($_SERVER["DOCUMENT_ROOT"] . "/OPCS/data/product_images/" . $old_image);
    }else{
        $update_image = $old_image;
    }

    $product_desc = mysqli_real_escape_string($conn, $_POST['product_description']);
    $sql = "UPDATE product SET 
            Product_Name='$_POST[product_name]', 
            Product_Image='$update_image', 
            Product_Brand='$_POST[brand]', 
            Product_Quantity='$_POST[stock_quantity]',
            Product_Description='$_POST[product_description]',
            Product_SKU ='$_POST[product_sku]',
            Availability ='$_POST[Availability]',
            Price ='$_POST[price]',           
            Category_ID='$_POST[category]',
            Supplier_ID='$_POST[Supplier]'           
            WHERE Product_ID=$_POST[Product_ID];";
    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        header('Location: /OPCS/en/administrator/our-products');
}

?>