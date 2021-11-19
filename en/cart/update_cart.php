<?php

    include(__DIR__ . "/../../server/includes/utils/Conn.php");
    global $conn;
    session_start();

    $user_id = $_SESSION['user_id'];
//    $return_url = $_SERVER['HTTP_REFERER'];
    $product_id = (int)$_GET['product_id'];
    $cart_Quantity = (int)$_GET['product_quantity'];
    $sql_update = "UPDATE shopping_cart_item SET Quantity=$cart_Quantity WHERE Product_id = $product_id AND  Cart_ID = (SELECT cart_id FROM shopping_cart WHERE user_id = '$user_id')";
    // print_r($return_url);
    if($conn -> query($sql_update)){
        echo "
        <script>
            alert('Cart Update Successfully!');
            let return_url = document.referrer;
            if (return_url.indexOf('choose-quantity') > -1){
                history.go(-2);
            }
            else{
                history.back();
            }
        </script>";
    }
    else{
        die($conn -> error);
    }
?>
<script>

</script>