<?php

namespace User;

include(__DIR__ . "/../../../server/includes/utils/Conn.php");

class PurchaseHistoryQuery {
    /* Fetch purchase history for a user */
    public static function fetchPurchaseHistory($userID, $isGetAll = true) {
        global $conn;
        global $sql_query;

        if ($isGetAll) {
            $sql_query = "SELECT * FROM member_order WHERE user_id =? ORDER BY Date DESC;";
        } else {
            $sql_query = "SELECT * FROM member_order WHERE user_id =? ORDER BY Member_Order_ID DESC limit 1;";
        }

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escUserID = $conn->real_escape_string($userID);

        $sql_statement->bind_param("s", $escUserID);
        $sql_statement->execute();

        return $sql_statement->get_result();
    }

    /* Fetch purchase history details for a user with given memberOrderID */
    public static function fetchPurchaseHistoryDetails($userID, $memberOrderID) {
        global $conn;

        $sql_query = "SELECT member_order.User_ID, member_order_details.Member_Order_ID, member_order.Date, 
                    member_order_details.Product_ID, product.Product_Name, Product_Image, member_order_details.Quantity, 
                    member_order.Delivery_Status, (Price * member_order_details.Quantity) AS Total FROM opcs.member_order_details
                    INNER JOIN opcs.member_order ON member_order_details.Member_Order_ID = member_order.Member_Order_ID
                    INNER JOIN opcs.product ON member_order_details.Product_ID = product.Product_ID
                    INNER JOIN user ON member_order.User_ID = user.User_ID
                    WHERE user.User_ID =? AND member_order.Member_Order_ID=?";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escUserID = $conn->real_escape_string($userID);
        $escMemberOrderID = $conn->real_escape_string($memberOrderID);

        $sql_statement->bind_param("ss", $escUserID, $escMemberOrderID);
        $sql_statement->execute();

        return $sql_statement->get_result();
    }

    public static function calculateGrandTotal($user_id, $member_order_id) {
        global $conn;

        $sql_query = "SELECT SUM(Price * Quantity) AS Grand_Total, member_order_details.Member_Order_ID, member_order.Delivery_Status, member_order.User_ID FROM opcs.member_order_details
                    INNER JOIN opcs.member_order ON member_order_details.Member_Order_ID = member_order.Member_Order_ID
                    INNER JOIN opcs.product ON member_order_details.Product_ID = product.Product_ID
                    INNER JOIN user ON member_order.User_ID = user.User_ID
                    WHERE user.User_ID =? AND member_order.Member_Order_ID =?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escUserID = $conn->real_escape_string($user_id);
        $escMemberOrderID = $conn->real_escape_string($member_order_id);

        $sql_statement->bind_param("ss", $escUserID, $escMemberOrderID);
        $sql_statement->execute();

        return $sql_statement->get_result();
    }

    public static function fetchReview($userID, $productID) {
        global $conn;

        $sql_query = "SELECT * FROM review WHERE User_ID =? AND Product_ID =?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escUserID = $conn->real_escape_string($userID);
        $escProductID = $conn->real_escape_string($productID);

        $sql_statement->bind_param("ss", $escUserID, $escProductID);
        $sql_statement->execute();

        return $sql_statement->get_result();
    }

    public static function appendReview($rating, $feedback, $productID, $userID) {
        global $conn;

        $sql_query = "INSERT INTO review(opcs.review.Rating , opcs.review.Feedback, opcs.review.Product_ID, opcs.review.User_ID) VALUES (?, ?, ?, ?);";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escReviewRating = $conn->real_escape_string($rating);
        $escReviewContent = $conn->real_escape_string($feedback);
        $escProductID = $conn->real_escape_string($productID);
        $escUserID = $conn->real_escape_string($userID);

        $sql_statement->bind_param("ssss", $escReviewRating, $escReviewContent, $escProductID, $escUserID);
        $sql_statement->execute();
    }

}