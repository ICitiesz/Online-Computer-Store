<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");

    global $conn;
    $id = (int)$_GET['Product_ID'];
    $image = $_GET['Product_Image'];
    $path = $_SERVER["DOCUMENT_ROOT"] . "/OPCS/data/product_images/$image";
    $result = mysqli_query($conn,"DELETE FROM product WHERE Product_ID=$id");
    if (!unlink($path)){
        echo "Image Delete failed";
    }

    mysqli_close($conn); //close database connection
    header('Location: /OPCS/en/administrator/our-products');
    exit();
?>
