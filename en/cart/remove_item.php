<?php
    session_start();

    include(__DIR__ . "/../../server/includes/utils/Conn.php");
    global $conn;

    $product_id = $_GET['product_id'];
    $user_id = $_SESSION['user_id'];

    $sql_remove = "DELETE FROM shopping_cart_item WHERE Product_id = $product_id AND Cart_id  = (SELECT Cart_id FROM Shopping_cart WHERE user_id = '$user_id')";

    if($conn -> query($sql_remove)){
        echo "<script>
                alert('Product Remove Successfully');
                let return_url = document.referrer;
                if (return_url.indexOf('choose-quantity') > -1){
                    history.go(-2);
                }
                else{
                    history.back();
                }
            </script>";

    }
?>

