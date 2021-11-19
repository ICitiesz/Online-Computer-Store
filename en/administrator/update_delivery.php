<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");

    global $conn;

    session_start();

    // Restrict access to non-accessible pages
    Utils\Utils::redirectIndex();
    Utils\Utils::redirectIndexAdmin();

    $member_order_id = (int)$_GET['OrderID'];
    $order_status = $_GET['status'];
    $sql_query ="UPDATE member_order SET Delivery_Status='$order_status' WHERE Member_Order_ID = $member_order_id";
    if ($order_status === 'Cancelled'){
        $result = mysqli_query($conn, "SELECT Product_ID FROM member_order_details WHERE member_order_id = $member_order_id");
        while ($item = mysqli_fetch_assoc($result)){
            $product_id = (int)$item['Product_ID'];

            $sql_return_stock = "UPDATE product SET product_quantity = product_Quantity + ( SELECT quantity FROM member_order_details WHERE Member_Order_ID = $member_order_id  AND product_id = $product_id) WHERE product_id = $product_id";
            mysqli_query($conn, $sql_return_stock);
        }
    }


    if(!mysqli_query($conn, $sql_query)){
        die('Error: ' . mysqli_error($conn));
    }

    echo "<script>alert('Delivery Status Updated Successfully!');
                window.location.href='/OPCS/en/administrator/member';</script>";
?>

