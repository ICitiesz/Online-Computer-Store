<?php

namespace Utils;

use RuntimeException;
use User\BillingInfoQuery;
use User\User;
use User\UserQuery;
use User\UserSession;

class Utils {
    public static function redirectIndex():void {
        if (!UserSession::isSessionSet("user_id")) {
            header("Location: /OPCS/en/index");
            exit();
        }
    }

    public static function redirectIndexAdmin():void {
        if (UserSession::getSessionUserRole() !== "admin") {
            header("Location: /OPCS/en/index");
            exit();
        }
    }

    /* Discard the resubmission dialog box. */
    public static function discardResubmission($refreshDelay = 0.2):void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $scriptFileName = explode("/", $_SERVER['SCRIPT_FILENAME']);

            if (end($scriptFileName) === "display_product.php") {
                header("refresh:" . $refreshDelay . "; url=\"/OPCS/en/all-product/display_product?" . $_SERVER['QUERY_STRING']);
                exit();
            }

            if (end($scriptFileName) === "update_delivery.php") {
                header("refresh:" . $refreshDelay . "; url=\"/OPCS/en/administrator/update_delivery?" . $_SERVER['QUERY_STRING']);
                exit();
            }

            if (end($scriptFileName) === "delete-product.php") {
                header("refresh:" . $refreshDelay . "; url=\"/OPCS/en/administrator/delete-product?" . $_SERVER['QUERY_STRING']);
                exit();
            }

            if (end($scriptFileName) === "edit-product.php") {
                header("refresh:" . $refreshDelay . "; url=\"/OPCS/en/administrator/edit-product?" . $_SERVER['QUERY_STRING']);
                exit();
            }

            header("refresh:" . $refreshDelay . "; url=" . str_replace(".php", "", $_SERVER['PHP_SELF']));
            exit;
        }
    }

    /* Page redirection with method var set */
    public static function redirectPage(string $method, string $methodVar, string $pageURL):void {
        if (!self::isMethodVarSet($method, $methodVar)) {
            return;
        }

        echo '<script>window.location.href = "' . $pageURL . '"</script>';
    }

    /* Check is the method such as $_POST, $_GET, $_REQUEST set. */
    public static function isMethodVarSet(string $method, string $methodVar):bool {
        switch (strtoupper($method)) {
            case "POST": {
                return isset($_POST[$methodVar]);
            }

            case "GET": {
                return isset($_GET[$methodVar]);
            }

            case "REQUEST": {
                return isset($_REQUEST[$methodVar]);
            }

            default: {
                return false;
            }
        }
    }

    /* Get the method value */
    public static function getMethodVar(string $method, string $methodVar): ?string {
        switch (strtoupper($method)) {
            case "POST": {
                return $_POST[$methodVar];
            }

            case "GET": {
                return $_GET[$methodVar];
            }

            case "REQUEST": {
                return $_REQUEST[$methodVar];
            }

            default: {
                return null;
            }
        }
    }

    /* UUID Generator */
    public static function generateUUID(string $fetcherType, string $prefix = null, int $uuidLength = 32):string {
        $uuidContent = "0123456789abcdefghijklmnopqrstuvwxyz";
        $tempUUID = substr(str_shuffle($uuidContent), 0, $uuidLength);

        while (self::hasUUID($tempUUID, $fetcherType)) {
            $uuidContent = "0123456789abcdefghijklmnopqrstuvwxyz";
            $tempUUID = substr(str_shuffle($uuidContent), 0, $uuidLength);
        }

        if ($prefix === null) {
            return $tempUUID;
        }

        return $prefix . $tempUUID;
    }

    /* UUID Checker */
    /* Fetcher type: user, billing_info */
    private static function hasUUID(string $tempUUID, string $fetcherType): ?bool {
        switch ($fetcherType) {
            case "user": {
                return UserQuery::fetchUserData($tempUUID)->num_rows === 1;
            }

            case "billing_info": {
                return BillingInfoQuery::fetchBillingInfo($tempUUID)->num_rows === 1;
            }

            default: {
                return null;
            }
        }
    }

    // Create required folder if is not exist.
    public static function createDataFolder():void {
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/OPCS/data") && !mkdir($_SERVER["DOCUMENT_ROOT"] . "/OPCS/data") && !is_dir($_SERVER["DOCUMENT_ROOT"] . "/OPCS/data")) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', "/OPCS/data"));
        }

        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/OPCS/data/user_profile_images") && !mkdir($_SERVER["DOCUMENT_ROOT"] . "/OPCS/data/user_profile_image") && !is_dir($_SERVER["DOCUMENT_ROOT"] . "/OPCS/data/user_profile_image")) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', "/OPCS/data/user_profile_images"));
        }
    }

    public static function decodeImage(string $image):string {
        $imageParts = explode(",", $image);
        return base64_decode($imageParts[1]);
    }
}
?>