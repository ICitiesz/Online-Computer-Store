<?php


namespace User;

use mysqli_result;
use Utils\Utils;

include(__DIR__ . "/../../../server/includes/utils/Conn.php");

class UserQuery {
    /* Register user to the database */
    public static function registerUser($username, $email, $contact, $gender, $password) {
        global $conn;

        $sql_query = "INSERT INTO user(User_ID, Username, Email, Contact_Number, Password, Gender, Role)
                        VALUES(?, ?, ?, ?, ?, ?, ?);";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $userID = Utils::generateUUID("user");
        $escUsername = $conn->real_escape_string($username);
        $escEmail = $conn->real_escape_string($email);
        $escContact = $conn->real_escape_string($contact);
        $escPassword = $conn->real_escape_string($password);
        $escGender = $conn->real_escape_string($gender);
        $userRole = "member";

        $sql_statement->bind_param("sssssss", $userID, $escUsername, $escEmail, $escContact, $escPassword, $escGender, $userRole);
        $sql_statement->execute();

        return $sql_statement->affected_rows;
    }

    /* Check if the user is exist in database. */
    public static function userValidation($value):bool {
        global $conn;

        $sql_query = "SELECT * FROM user WHERE Username=? OR Email=?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escValue = $conn->real_escape_string($value);

        $sql_statement->bind_param("ss", $escValue, $escValue);
        $sql_statement->execute();

        return $sql_statement->get_result()->num_rows === 1;
    }

    /* Check the password is match user input. */
    public static function passwordMatcher($username, $userPassword):bool {
        global $conn;

        $sql_query = "SELECT * FROM user WHERE Username=? OR Email=? AND Password=?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escUsername = $conn->real_escape_string($username);
        $escUserPassword = $conn->real_escape_string($userPassword);

        $sql_statement->bind_param("sss", $escUsername, $escUsername, $escUserPassword);
        $sql_statement->execute();

        foreach ($sql_statement->get_result() as $value) {
            if (($value["Username"] === $escUsername || $value["Email"] === $escUsername) && $value["Password"] === $escUserPassword) {
                return true;
            }
        }
        return false;
    }

    /* Fetch User Data by Username, Email or UserID */
    public static function fetchUserData($value):mysqli_result {
        global $conn;

        $sql_query = "SELECT * FROM user WHERE Username=? OR Email=? OR User_ID=?;";

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $escValue = $conn->real_escape_string($value);

        $sql_statement->bind_param("sss", $escValue, $escValue, $escValue);
        $sql_statement->execute();

        return $sql_statement->get_result();
    }

    public static function updateUserData($userID, $username, $userEmail, $userGender, $userContact, $userPassword, $userProfilePic):bool {
        global $conn;
        global $sql_statement;

        $escUserID = $conn->real_escape_string($userID);

        if (!isset($_POST["new_password_save"])) {
            $sql_query = "UPDATE User SET Username=?, Gender=?, Contact_Number=?, Email=? WHERE User_ID=?;";

            $escUsername = $conn->real_escape_string($username);
            $escEmail = $conn->real_escape_string($userEmail);
            $escGender = $conn->real_escape_string($userGender);
            $escContact = $conn->real_escape_string($userContact);

            $checkNull = [$escUsername, $escEmail, $escGender, $escContact];

            foreach ($checkNull as $value) {
                if (empty($value)) {
                    return false;
                }
            }

            $sql_statement = $conn->stmt_init();

            if (!$sql_statement->prepare($sql_query)) {
                die("Unhandled error: " . $conn->error);
            }

            $sql_statement->bind_param("sssss", $escUsername, $escGender, $escContact, $escEmail, $escUserID);
        }

        if (isset($_POST["new_password_save"])) {
            $sql_query = "UPDATE User SET Password=? WHERE User_ID=?;";

            $escPassword = $conn->real_escape_string($userPassword);

            $sql_statement = $conn->stmt_init();

            if (!$sql_statement->prepare($sql_query)) {
                die("Unhandled error: " . $conn->error);
            }

            $sql_statement->bind_param("ss", $escPassword, $escUserID);
        }

        if ($userProfilePic !== null) {
            $sql_query = "UPDATE User SET Profile_Image=? WHERE User_ID=?;";

            $escProfilePic = $conn->real_escape_string($userProfilePic);

            $sql_statement = $conn->stmt_init();

            if (!$sql_statement->prepare($sql_query)) {
                die("Unhandled error: " . $conn->error);
            }

            $sql_statement->bind_param("ss", $escProfilePic, $escUserID);
        }

        $sql_statement->execute();
        return $sql_statement->affected_rows === 1;
    }

    public static function updateProfilePic($userID, $userProfilePic):bool {
        global $conn;
        global $sql_statement;

        $escUserID = $conn->real_escape_string($userID);

        $sql_query = "UPDATE User SET Profile_Image=? WHERE User_ID=?;";

        $escProfilePic = $conn->real_escape_string($userProfilePic);

        $sql_statement = $conn->stmt_init();

        if (!$sql_statement->prepare($sql_query)) {
            die("Unhandled error: " . $conn->error);
        }

        $sql_statement->bind_param("ss", $escProfilePic, $escUserID);

        $sql_statement->execute();
        return $sql_statement->affected_rows === 1;
    }
}
