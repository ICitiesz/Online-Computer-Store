<?php
declare(strict_types = 1);

namespace User;

use Utils\Utils;

class User {
    private string $userID;
    private string $username;
    private string $userEmail;
    private string $userPassword;
    private string $userContact;
    private String $userGender;
    private String $userRole;
    private String $userProfilePic;

    public function __construct($userID) {
        $this->userID = $userID;
        $this->username = UserQuery::fetchUserData($userID)->fetch_assoc()["Username"];
        $this->userEmail = UserQuery::fetchUserData($userID)->fetch_assoc()["Email"];
        $this->userPassword = UserQuery::fetchUserData($userID)->fetch_assoc()["Password"];
        $this->userContact = UserQuery::fetchUserData($userID)->fetch_assoc()["Contact_Number"];
        $this->userGender = UserQuery::fetchUserData($userID)->fetch_assoc()["Gender"];
        $this->userRole = UserQuery::fetchUserData($userID)->fetch_assoc()["Role"];

        if (UserQuery::fetchUserData($userID)->fetch_assoc()["Profile_Image"] !== null) {
            $this->userProfilePic = UserQuery::fetchUserData($userID)->fetch_assoc()["Profile_Image"];
        }
    }

    public function getUserID():string {
        return $this->userID;
    }

    public function getUsername():string {
        return $this->username;
    }

    public function getEmail():string {
        return $this->userEmail;
    }

    public function getPassword():string {
        return $this->userPassword;
    }

    public function getContact():string {
        return $this->userContact;
    }

    public function getGender():string {
        return $this->userGender;
    }

    public function getRole():string {
        return $this->userRole;
    }

    public function getProfilePic(): ?string {
        return $this->userProfilePic ?? null;
    }

    /* Perform update profile operation */
    public static function updateProfile($userID):void {
        if (isset($_POST["profile-save"])) {
            $profileUsername = $_POST["profile_username"];
            $profileEmail = $_POST["profile_email"];
            $profileGender = $_POST["profile_gender"];
            $profileContact = $_POST["profile_contact"];

            $isUsernameValid = InputFieldValidation::validateUsername($profileUsername);
            $isEmailValid = InputFieldValidation::validateEmail($profileEmail);
            $isGenderValid = InputFieldValidation::validateGender($profileGender);
            $isContactValid = InputFieldValidation::validateContactNumber($profileContact);

            if (!$isUsernameValid || !$isEmailValid || !$isGenderValid || !$isContactValid) {
                return;
            }

            if (UserQuery::userValidation($_POST["profile_username"])) {
                if (UserQuery::fetchUserData($_POST["profile_username"])->fetch_assoc()["User_ID"] !== $userID) {
                    echo "<script>alert('The username already exist!')</script>";
                    return;
                }

                UserQuery::updateUserData($userID, $profileUsername,$profileEmail, $profileGender, $profileContact, null, null);
                echo "<script>alert('Your profile has been updated!');</script>";
                return;
            }

            if (UserQuery::userValidation($_POST["profile_email"])) {
                if (UserQuery::fetchUserData($_POST["profile_email"])->fetch_assoc()["User_ID"] !== $userID) {
                    echo "<script>alert('The email already exist!')</script>";
                    return;
                }

                UserQuery::updateUserData($userID, $profileUsername,$profileEmail, $profileGender, $profileContact, null, null);
                echo "<script>alert('Your profile has been updated!');</script>";
                return;
            }

            UserQuery::updateUserData($userID, $profileUsername,$profileEmail, $profileGender, $profileContact, null, null);
            echo "<script>alert('Your profile has been updated!');</script>";
            return;
        }

        if (isset($_POST["new_password_save"])) {
            $profilePassword = $_POST["profile_password"];
            $profileConfirmPassword = $_POST["profile_confirm_password"];

            $isPasswordValid = InputFieldValidation::validatePassword($profilePassword, $profileConfirmPassword);

            if (!$isPasswordValid) {
                return;
            }
            UserQuery::updateUserData($userID, null, null, null, null,$profileConfirmPassword, null);
            echo "<script>alert('Your password has been updated!');</script>";
        }
    }

    public static function updateProfilePic($userID):void {
        if (UserSession::isSessionSet("croppedImage")) {
            $croppedImage = $_SESSION["croppedImage"];
            $imageFile = $userID . ".jpg";

            Utils::createDataFolder();

            $folderPath = $_SERVER["DOCUMENT_ROOT"] . "/OPCS/data/user_profile_images";
            UserQuery::updateProfilePic($userID, $imageFile);
            file_put_contents($folderPath . "/" . $imageFile, Utils::decodeImage($croppedImage));
            UserSession::removeSessionVar("croppedImage");
        }
    }

}

?>