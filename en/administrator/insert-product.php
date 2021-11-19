<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");

    global $conn;

    $fileName = $_FILES["file"]["name"];
    $tempName = $_FILES["file"]["tmp_name"];
    $temp = explode(".",$fileName);
    $newFileName = round(microtime(true)) . '.' . end($temp);

    $product_desc = mysqli_real_escape_string($conn, $_POST['product_description']);
    $sql="INSERT INTO product (Product_Name,Product_Image,Product_Brand,Product_SKU, Product_Quantity,Product_Description,Category_ID,Availability,Price,Supplier_ID) VALUES ('$_POST[product_name]','$fileName','$_POST[brand]','$_POST[product_sku]','$_POST[stock_quantity]','$product_desc','$_POST[category]','$_POST[Availability]','$_POST[price]','$_POST[Supplier]')";

    move_uploaded_file($tempName, $_SERVER["DOCUMENT_ROOT"] . "/OPCS/data/product_images/" . $newFileName);

    if (!mysqli_query($conn,$sql)){
        die("<script>alert('Duplicate Product_SKU');location.replace('/OPCS/en/administrator/add-product')</script>". mysqli_error($conn));
    }

    echo '<script>alert("1 record added!");
            window.location.href= "/OPCS/en/administrator/add-product";
            </script>';
    mysqli_close($conn);
?>



