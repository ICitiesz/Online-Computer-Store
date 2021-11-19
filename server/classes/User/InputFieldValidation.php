<?php

namespace User;

class InputFieldValidation {
    // Username input validation.
    public static function validateUsername(string $profileUsername):bool {
        // Check if the username is empty.
        if (empty($profileUsername)) {
            echo "<script>alert('Please fill in the username field!')</script>";
            return false;
        }

        // Check if the username is at least 4 characters long.
        if (strlen($profileUsername) < 4) {
            echo "<script>alert('The username must be at least 4 characters long!')</script>";
            return false;
        }

        // Check if the username is no more than 15 characters long.
        if (strlen($profileUsername) > 15) {
            echo "<script>alert('The username must not be more than 15 characters long!')</script>";
            return false;
        }

        // Check if the username contains invalid characters.
        if (!preg_match("/^[a-zA-Z0-9\w]{4,}$/", $profileUsername)) {
            echo "<script>alert('The username must not contain invalid characters!')</script>";
            return false;
        }

        // Check if the username has spaces.
        if (preg_match("/\s/", $profileUsername)) {
            echo "<script>alert('The username must not contain whitespace!')</script>";
            return false;
        }

        return true;
    }

    // Email input validation.
    public static function validateEmail(string $profileEmail):bool {
        // Check if the email is empty.
        if (empty($profileEmail)) {
            echo "<script>alert('Please fill in the email field!')</script>";
            return false;
        }

        // Remove invalid characters from the email
        $profileEmail = filter_var($profileEmail, FILTER_SANITIZE_EMAIL);

        // Check if the email is valid.
        if (!filter_var($profileEmail, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('The email address is invalid!')</script>";
            return false;
        }

        return true;
    }

    // Gender input validation.
    public static function validateGender(string $profileGender):bool {
        $tempArray = array("MALE", "FEMALE", "OTHER");

        // Check if the profileGender is empty.
        if (empty($profileGender)) {
            echo "<script>alert('Please select a gender!')</script>";
            return false;
        }

        // Check if the profileGender in tempArray.
        if (!in_array( strtoupper($profileGender), $tempArray)) {
            echo "<script>alert('There is no such gender as \"" . $profileGender . "\"')</script>";
            return false;
        }

        return true;
    }

    // Contact number input validation.
    public static function validateContactNumber(string $profileContact):bool {
        // Check if the contact number is empty.
        if (empty($profileContact)) {
            echo "<script>alert('Please fill in the contact number field!')</script>";
            return false;
        }

        // Check if the contact number is valid.
        if (!preg_match("/^[-\d]{10,}$/", $profileContact)) {
            echo "<script>alert('The contact number is invalid!')</script>";
            return false;
        }

        // Check if the contact number has spaces.
        if (preg_match("/\s/", $profileContact)) {
            echo "<script>alert('The contact number must not contain any spaces!')</script>";
            return false;
        }

        // Check if the contact number is not more than 12 digits including the '-'.
        if (strlen($profileContact) > 12) {
            echo "<script>alert('The contact number must not more than 12 digits including the \'-\' !')</script>";
            return false;
        }

        return true;
    }

    // Terms and conditions validation.
    public static function validateTermsAgree(string $termsAgree):bool {
        if ($termsAgree !== "agree") {
            echo "<script>alert('You must agree to the terms and conditions!')</script>";
            return false;
        }
        return true;
    }

    // Password field validation.
    public static function validatePassword($profilePassword, $profileConfirmPassword):bool {
        // Check if the password is empty.
        if (empty($profilePassword) || empty($profileConfirmPassword)) {
            echo "<script>alert('Please fill in the password field!')</script>";
            return false;
        }

        // Check if both password fields are the same.
        if ($profilePassword !== $profileConfirmPassword) {
            echo "<script>alert('The password does not match!')</script>";
            return false;
        }

        // Check if the password is valid.
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/", $profileConfirmPassword)) {
            echo "<script>alert('The password must contain at least one lowercase letter, one uppercase letter, one digit and one special character!')</script>";
            return false;
        }

        // Check if the password is at least 8 characters long and no more than 128 characters long.
        if (strlen($profileConfirmPassword) < 8) {
            echo "<script>alert('The password must be at least 8 characters long!')</script>";
            return false;
        }

        // Check if the password is not more than 128 characters long.
        if (strlen($profileConfirmPassword) > 128) {
            echo "<script>alert('The password must not more than 128 characters long!')</script>";
            return false;
        }

        return true;
    }

    /* Validate feedback field */
    public static function validateFeedback($feedback):bool {
        // Check if the feedback is empty.
        if (empty($feedback)) {
            echo "<script>alert('Please fill in the feedback field!')</script>";
            return false;
        }

        return true;
    }

    // Valid the card holder name.
    public static function validateCardHolderName($cardHolderName):bool {
        // Check if the card holder name is empty.
        if (empty($cardHolderName)) {
            echo "<script>alert('Please fill in the card holder name field!')</script>";
            return false;
        }

        // Check if the card holder name is valid.
        if (!preg_match("/^[a-zA-Z ]*$/", $cardHolderName)) {
            echo "<script>alert('The card holder name is invalid!')</script>";
            return false;
        }

        // Check if the card holder name is not more than 128 characters long.
        if (strlen($cardHolderName) > 128) {
            echo "<script>alert('The card holder name must not more than 128 characters long!')</script>";
            return false;
        }

        return true;
    }

    // Valid the card number.
    public static function validateCardNumber($cardNumber):bool {
        // Check if the card number is empty.
        if (empty($cardNumber)) {
            echo "<script>alert('Please fill in the card number field!')</script>";
            return false;
        }

        // Check if the card number is valid.
        if (!preg_match("/^\d*$/", $cardNumber)) {
            echo "<script>alert('The card number is invalid!')</script>";
            return false;
        }

        // Check if the card number is at least 16 digits long.
        if (strlen($cardNumber) < 16) {
            echo "<script>alert('The card number must be at least 16 digits long!')</script>";
            return false;
        }

        // Check if the card number is not more than 16 digits long.
        if (strlen($cardNumber) > 16) {
            echo "<script>alert('The card number must not more than 16 digits long!')</script>";
            return false;
        }
        return true;
    }

    // Valid the card CVV.
    public static function validateCardCVV($cardCVV):bool {
        // Check if the card CVV is empty.
        if (empty($cardCVV)) {
            echo "<script>alert('Please fill in the card CVV field!')</script>";
            return false;
        }

        // Check if the card CVV is valid.
        if (!preg_match("/^\d*$/", $cardCVV)) {
            echo "<script>alert('The card CVV is invalid!')</script>";
            return false;
        }

        // Check if the card CVV is at least 3 digits long.
        if (strlen($cardCVV) < 3) {
            echo "<script>alert('The card CVV must be at least 3 digits long!')</script>";
            return false;
        }

        // Check if the card CVV is not more than 3 digits long.
        if (strlen($cardCVV) > 3) {
            echo "<script>alert('The card CVV must not more than 3 digits long!')</script>";
            return false;
        }
        return true;
    }

    // Valid the card expiry month.
    public static function validateCardExpiryMonth($cardExpiryMonth):bool {
        // Check if the card expiry month is empty.
        if (empty($cardExpiryMonth)) {
            echo "<script>alert('Please fill in the card expiry month field!')</script>";
            return false;
        }

        // Check if the card expiry month is valid.
        if (!preg_match("/^\d*$/", $cardExpiryMonth)) {
            echo "<script>alert('The card expiry month is invalid!')</script>";
            return false;
        }

        // Check if the card expiry month is at least 2 digits long.
        if (strlen($cardExpiryMonth) < 2) {
            echo "<script>alert('The card expiry month must be at least 2 digits long!')</script>";
            return false;
        }

        // Check if the card expiry month is not more than 2 digits long.
        if (strlen($cardExpiryMonth) > 2) {
            echo "<script>alert('The card expiry month must not more than 2 digits long!')</script>";
            return false;
        }

        // Check if the card expiry month is between 1 and 12.
        if ($cardExpiryMonth < 1 || $cardExpiryMonth > 12) {
            echo "<script>alert('The card expiry month must be between 1 and 12!')</script>";
            return false;
        }

        return true;
    }

    // Valid the card expiry year.
    public static function validateCardExpiryYear($cardExpiryYear):bool {
        // Check if the card expiry year is empty.
        if (empty($cardExpiryYear)) {
            echo "<script>alert('Please fill in the card expiry year field!')</script>";
            return false;
        }

        // Check if the card expiry year is valid.
        if (!preg_match("/^\d*$/", $cardExpiryYear)) {
            echo "<script>alert('The card expiry year is invalid!')</script>";
            return false;
        }

        // Check if the card expiry year is at least 4 digits long.
        if (strlen($cardExpiryYear) < 4) {
            echo "<script>alert('The card expiry year must be at least 2 digits long!')</script>";
            return false;
        }

        // Check if the card expiry year is not more than 4 digits long.
        if (strlen($cardExpiryYear) > 4) {
            echo "<script>alert('The card expiry year must not more than 2 digits long!')</script>";
            return false;
        }

        // Check if the card expiry year is not in the past.
        if ($cardExpiryYear < date("y")) {
            echo "<script>alert('The card expiry year must not be in the past!')</script>";
            return false;
        }

        return true;
    }


}