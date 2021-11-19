<?php

namespace User;

include(__DIR__ . "/../../../server/includes/utils/Conn.php");

class CheckoutQuery {
    /* Fetch user cart */
    public static function fetchUserCartID($userID) {
        global $conn;

        $sql_query = "SELECT Cart_ID FROM shopping_cart
                    INNER JOIN user ON user.User_ID = shopping_cart.User_ID
                    WHERE shopping_cart.User_ID = ?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escUserID = $conn->real_escape_string($userID);

        $sql_statement->bind_param("s", $escUserID);
        $sql_statement->execute();

        return $sql_statement->get_result()->fetch_assoc()["Cart_ID"];
    }

    /* Fetch user cart items */
    public static function fetchUserCartItems($cartID) {
        global $conn;

        $sql_query = "SELECT shopping_cart_item.Cart_ID, shopping_cart_item.Cart_Item_ID, shopping_cart_item.Product_ID, Product_Image, Product_Name, 
                    Price, shopping_cart_item.Quantity, (Price * Quantity) AS Total FROM shopping_cart_item
                    INNER JOIN shopping_cart ON shopping_cart_item.Cart_ID = shopping_cart.Cart_ID
                    INNER JOIN product ON shopping_cart_item.Product_ID = product.Product_ID
                    WHERE shopping_cart_item.Cart_ID =?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escCartID = $conn->real_escape_string($cartID);

        $sql_statement->bind_param("s", $escCartID);
        $sql_statement->execute();

        return $sql_statement->get_result();
    }

    /* Delete Cart Item from database */
    public static function deleteCartItem($cartItemID):void {
        global $conn;

        $sql_query = "DELETE FROM shopping_cart_item WHERE Cart_Item_ID = ?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escCartItemID = $conn->real_escape_string($cartItemID);

        $sql_statement->bind_param("s", $escCartItemID);
        $sql_statement->execute();
    }

    /* Fetch product quantity */
    public static function fetchProductQuantity($productID) {
        global $conn;

        $sql_query = "SELECT Product_Quantity FROM product WHERE Product_ID = ?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escProductID = $conn->real_escape_string($productID);

        $sql_statement->bind_param("s", $escProductID);
        $sql_statement->execute();

        return $sql_statement->get_result()->fetch_assoc()["Product_Quantity"];
    }

    /* Create cart */
    public static function createCart($userID):void {
        global $conn;

        $sql_query = "INSERT INTO shopping_cart (User_ID) VALUES (?);";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escUserID = $conn->real_escape_string($userID);

        $sql_statement->bind_param("s", $escUserID);
        $sql_statement->execute();
    }


    /* Create new order */
    public static function addMemberOrder($userID, $billingID):void {
        global $conn;

        $sql_query = "INSERT INTO member_order (opcs.member_order.User_ID, opcs.member_order.Billing_ID, opcs.member_order.Delivery_Status, opcs.member_order.Date) VALUES (?, ?, ?, ?);";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escUserID = $conn->real_escape_string($userID);
        $escBillingID = $conn->real_escape_string($billingID);
        $escDeliveryStatus = "Preparing";
        $escDate = date("Y-m-d");

        $sql_statement->bind_param("ssss", $escUserID, $escBillingID, $escDeliveryStatus, $escDate);
        $sql_statement->execute();
    }

    /* Insert cart item into member order details */
    public static function appendMemberOrderDetails($productID, $memberOrderID, $quantity):void {
        global $conn;

        $sql_query = "INSERT INTO member_order_details (opcs.member_order_details.Product_ID, opcs.member_order_details.Member_Order_ID, Quantity) VALUES (?, ?, ?);";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escProductID = $conn->real_escape_string($productID);
        $escMemberOrderID = $conn->real_escape_string($memberOrderID);
        $escQuantity = $conn->real_escape_string($quantity);

        $sql_statement->bind_param("sss",  $escProductID, $escMemberOrderID, $escQuantity);
        $sql_statement->execute();
    }

    /* Calculate the grand total of the cart */
    public static function calculateGrandTotalCart($cartID) {
        global $conn;

        $sql_query = "SELECT shopping_cart_item.Product_ID, Quantity, product.Product_Image, product.Product_Name, product.Price, SUM(product.Price * Quantity) AS Grand_Total FROM shopping_cart_item
                    INNER JOIN shopping_cart ON shopping_cart_item.Cart_ID = shopping_cart.Cart_ID
                    INNER JOIN product ON shopping_cart_item.Product_ID = product.Product_ID
                    WHERE shopping_cart.Cart_ID =?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escCartID = $conn->real_escape_string($cartID);

        $sql_statement->bind_param("s", $escCartID);

        $sql_statement->execute();

        return $sql_statement->get_result()->fetch_assoc()["Grand_Total"];
    }


    /* Update Product Quantity */
    public static function updateProductQuantity($productID, $productQuantity, $decreaseValue):void {
        global $conn;

        $sql_query = "UPDATE opcs.product SET Product_Quantity =? WHERE Product_ID =?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escDecreaseValue = $conn->real_escape_string($decreaseValue);
        $escProductQuantity = $conn->real_escape_string($productQuantity);
        $escProductID = $conn->real_escape_string($productID);

        $finalQuantity = (int) $escProductQuantity - (int) $escDecreaseValue;

        $sql_statement->bind_param("ss", $finalQuantity, $escProductID);
        $sql_statement->execute();
    }

}