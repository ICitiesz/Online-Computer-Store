<?php

namespace User;

use mysqli_result;
use Utils\Utils;

include(__DIR__ . "/../../../server/includes/utils/Conn.php");

class BillingInfoQuery {
    public static function fetchBillingInfo($value):mysqli_result {
        global $conn;

        $sql_query = "SELECT * FROM billing_information WHERE User_ID=?";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escValue = $conn->real_escape_string($value);

        $sql_statement->bind_param("s", $escValue);
        $sql_statement->execute();

        return $sql_statement->get_result();
    }

    public static function registerBillingInfo($userID):bool {
        global $conn;

        $sql_query = "INSERT INTO billing_information (Billing_ID, Billing_Profile_Name, Recipient_Name, Recipient_Contact_Number, Billing_Address, User_ID) VALUES (?, ?, ?, ?, ?, ?)";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escBillingID = $conn->real_escape_string(Utils::generateUUID("billing_info", "BID_"));
        $escBillingProfileName = $conn->real_escape_string($_POST["new-billing-profile-name"]);
        $escRecipientName = $conn->real_escape_string($_POST["new-billing-recipient-name"]);
        $escRecipientContactNumber = $conn->real_escape_string($_POST["new-billing-recipient-contact-number"]);
        $escBillingAddress = $conn->real_escape_string($_POST["new-recipient-billing-address"]);

        $sql_statement->bind_param("ssssss", $escBillingID,$escBillingProfileName, $escRecipientName, $escRecipientContactNumber, $escBillingAddress, $userID);
        $sql_statement->execute();

        return $sql_statement->affected_rows > 0;
    }

    public static function deleteBillingInfo($userID, $billingID):bool {
        global $conn;

        $sql_query = "DELETE FROM billing_information WHERE User_ID=? AND Billing_ID=?";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escUserID = $conn->real_escape_string($userID);
        $escBillingID = $conn->real_escape_string($billingID);

        $sql_statement->bind_param("ss", $escUserID, $escBillingID);
        $sql_statement->execute();

        return $sql_statement->affected_rows > 0;
    }
}