<?php
declare(strict_types = 1);

namespace User;

class UserSession {
    /* List of immutable session variables. */
    private static array $restrictedSessionVar = ["user_id", "role"];

    /* Initialize User Session. */
    private static function initUserSession($username):void {
        $_SESSION['user_id'] = UserQuery::fetchUserData($username)->fetch_assoc()["User_ID"];
        $_SESSION['role'] = UserQuery::fetchUserData($username)->fetch_assoc()["Role"];
    }

    /* Discard User Session. */
    private static function discardUserSession():void {
        session_unset();
        session_destroy();
    }

    /* Get User SessionID. */
    public function getUserSessionID() {
        if (empty($_SESSION)) {
            return null;
        }
        return session_id();
    }

    /* Get user ID from session. */
    public static function getSessionUserID() {
        return $_SESSION["user_id"] ?? null;
    }

    /* Get user role from session. */
    public static function getSessionUserRole() {
        return $_SESSION["role"] ?? null;
    }

    /* Check if the session with variable is initialize. */
    public static function isSessionSet(string $sessionVar):bool {
        return isset($_SESSION[$sessionVar]);
    }

    /* Add session variable. */
    public static function addSessionVar(string $varName, $varValue):void {
        if (in_array($varName, self::$restrictedSessionVar, true)) {
            return;
        }

        $_SESSION[$varName] = $varValue;
    }

    /* Remove session variable. */
    public static function removeSessionVar(string $varName):void {
        if (in_array($varName, self::$restrictedSessionVar, true)) {
            return;
        }

        if (empty($_SESSION[$varName])) {
            return;
        }

        unset($_SESSION[$varName]);
    }

    /* Perform user login. */
    public static function userLogin(): void {
        if (self::isSessionSet("user_id")) {
            return;
        }

        if (!isset($_POST["login"])) {
            return;
        }

        $username = $_POST["login-username"];
        $userPassword = $_POST["login-password"];

        if (UserQuery::userValidation($username) && UserQuery::passwordMatcher($username, $userPassword)) {
            self::initUserSession($username);
            unset($_POST);
            return;
        }

        echo "<script>alert('Invalid username/email or password!')</script>";
    }

    /* Perform user logout. */
    public static function userLogout():void {
        if (!self::isSessionSet("user_id")) {
            return;
        }

        if (!isset($_POST["logout"])) {
            return;
        }

        self::discardUserSession();
        header("Location: /OPCS/en/index");
        exit();
    }

    /* Perform user sign up. */
    public static function userSignUp():void {
        if (self::isSessionSet("user_id")) {
            return;
        }

        if (!isset($_POST["sign-up"])) {
            return;
        }

        $username = $_POST["sign_up_username"];
        $email = $_POST["sign_up_email"];
        $contact = $_POST["sign_up_contact"];
        $gender = $_POST["sign_up_gender"];
        $password = $_POST["sign_up_password"];
        $confirmPassword = $_POST["sign_up_confirm_password"];

        $isUsernameValid = InputFieldValidation::validateUsername($username);
        $isEmailValid = InputFieldValidation::validateEmail($email);
        $isContactValid = InputFieldValidation::validateContactNumber($contact);
        $isGenderValid = InputFieldValidation::validateGender($gender);
        $isPasswordValid = InputFieldValidation::validatePassword($password, $confirmPassword);
        $isTermsAgree = InputFieldValidation::validateTermsAgree($_POST["terms-check"]);

        if (!$isUsernameValid || !$isEmailValid || !$isContactValid || !$isGenderValid || !$isPasswordValid || !$isTermsAgree) {
            return;
        }

        if (!UserQuery::userValidation($username) && !UserQuery::userValidation($email)) {
            UserQuery::registerUser($username, $email, $contact, $gender, $confirmPassword);

            self::initUserSession($username);
            CheckoutQuery::createCart(self::getSessionUserID());
            echo "<script>alert('Registration successful!')</script>";
            return;
        }

        echo "<script>alert('Username/email already been used!')</script>";
    }

    /* Add the profile drop down and remove the login/sign up overlay once user logged in. */
    public static function addProfileDropDown():void {
        if (!self::isSessionSet("user_id")) {
            return;
        }

        $user = new User(self::getSessionUserID());

        echo '<script id="add-profile-dropdown">
        let profileBTN = document.getElementById("profile-btn");
        let profileLink = document.getElementById("profile-link");
        
        if (profileBTN != null) {
            if (!profileBTN.classList.contains("dropdown")) {
                profileBTN.classList.add("dropdown");
            }
        }
        
        if (profileLink != null) {
            if (profileLink.hasAttribute("data-bs-toggle")) {
                profileLink.removeAttribute("data-bs-toggle");
            }
            
            if (profileLink.hasAttribute("data-bs-target")) {
                profileLink.removeAttribute("data-bs-target");
            }
            
            profileLink.setAttribute("data-bs-toggle", "dropdown");
        }
        
        let loginOverlay = document.getElementById("login-overlay");
        let signUpOverlay = document.getElementById("sign-up-overlay");
        
        if (loginOverlay != null) {
            loginOverlay.remove();
        }
        
        if (signUpOverlay != null) {
            signUpOverlay.remove();
        }
        </script>';

        global $adminPanel;
        global $profilePic;

        $adminPanel = "";

        if (self::getSessionUserRole() === "admin") {
            $adminPanel = '<div class="row d-flex justify-content-center">
                        <form class="d-flex justify-content-center mb-0" action="/OPCS/en/administrator/admin" method="post">
                            <button class="btn btn-dark rounded-3 d-flex align-items-center justify-content-center nav-sign-out" name="logout">
                                <span class="small text-nowrap general-font">Admin Panel</span>
                            </button>
                        </form>
                    </div>';
        }

        if ($user->getProfilePic() === null) {
            $profilePic = "/OPCS/resources/images/dark-theme/profile_user-dark_theme.png";
        } else {
            $profilePic = "/OPCS/data/user_profile_images/" . $user->getProfilePic();
        }

        echo '<ul class="dropdown-menu dropdown-menu-end nav-dropdown-item-text lighter-dark shadow rounded-3" id="profile-dropdown">
                    <li>
                        <div class="dropdown-item-text d-grid gap-1">
                            <div class="row d-flex justify-content-center">
                                <img class="rounded-circle" id="nav-profile-image" src="' . $profilePic . '" alt="">
                            </div>
    
                            <div class="row">
                                <h6 class="text-center general-font">' . $user->getUsername() . '</h6>
                            </div>
    
                            <hr class="mt-0 mb-2 light-gray">
    
                            <div class="row d-flex justify-content-center mt-0">
                                <form class="d-flex justify-content-center" action="/OPCS/en/account/profile" method="post">
                                    <button class="btn btn-dark rounded-3 d-flex align-items-center justify-content-center nav-manage-profile" name="manage-profile" >
                                        <span class="small text-nowrap general-font">Manage your profile</span>
                                    </button>
                                </form>
                            </div>
                        
                            ' . $adminPanel . '
                        
                            <div class="row d-flex justify-content-center">
                                <form class="d-flex justify-content-center mb-0" action="" method="post">
                                    <button class="btn btn-dark rounded-3 d-flex align-items-center justify-content-center nav-sign-out" name="logout">
                                        <span class="small text-nowrap general-font">Sign out</span>
                                    </button>
                                </form>
                            </div>
                            
                            
                        </div>
                    </li>
            </ul>';
    }

    public static function setNavBarProfileName():void {
        if (self::isSessionSet("user_id")) {
            $user = new User(self::getSessionUserID());

            echo '<script>
            let navProfileIcon = document.getElementById("nav-profile-icon");
        
            if (navProfileIcon != null) {
                navProfileIcon.remove();
            }
            </script>';

            echo '<h6 class="mb-0" id="nav-profile-name">' . $user->getUsername() . '</h6>';
            return;
        }

        echo '<script>
        let navProfileName = document.getElementById("nav-profile-name");
        
        if (navProfileName != null) {
            navProfileName.remove();
        }
        </script>';

        echo '<img src="/OPCS/resources/images/dark-theme/nav_user-dark_theme.png" class="nav-icon" id="nav-profile-icon" alt="">';
    }

    /* Remove the profile drop down and add the login/sign up overlay once user logged out. */
    public static function addOverlay():void {
        if (self::isSessionSet("user_id")) {
            return;
        }

        echo '<script>
        if (document.getElementById("profile-btn") != null && document.getElementById("profile-btn").classList.contains("dropdown")) {
            document.getElementById("profile-btn").classList.remove("dropdown");
        }
        
        if (document.getElementById("profile-link") != null) {
            if (document.getElementById("profile-link").hasAttribute("data-bs-toggle")) {
                document.getElementById("profile-link").removeAttribute("data-bs-toggle");
            }
            
            if (document.getElementById("profile-link").hasAttribute("data-bs-target")) {
                document.getElementById("profile-link").removeAttribute("data-bs-target");
            }
            
            document.getElementById("profile-link").setAttribute("data-bs-toggle", "modal");
            document.getElementById("profile-link").setAttribute("data-bs-target", "#login-overlay");
        }
        
        if (document.getElementById("profile-dropdown") != null) {
            document.getElementById("profile-dropdown").remove();
        }
        </script>';

        $scriptFileName = explode("/", $_SERVER['SCRIPT_FILENAME']);

        $returnPage = "";

        if (end($scriptFileName) === "display_product.php") {
            $returnPage = "/OPCS/en/all-product/display_product?" . $_SERVER['QUERY_STRING'];
        }

        echo '
            <div class="modal fade" id="login-overlay" data-bs-backdrop="static" data-bs-keyboard="false">
    
                <div class="modal-dialog modal-dialog-centered" >
    
                    <div class="modal-content d-flex">
    
                        <div class="container position-relative rounded-top bg-dark py-2">
                            <h5 class="modal-title text-center">Login to OP Computer Store</h5>
                            <button type="button" class="overlay-close-btn position-absolute d-flex justify-content-center align-items-center" data-bs-dismiss="modal">
                                <img src="/OPCS/resources/images/dark-theme/close-dark_theme.png" class="overlay-close-btn-icon" alt="">
                            </button>
                        </div>
    
                        <div class="modal-body p-0 rounded-bottom lighter-dark">
                            <div class="container-fluid ">
                                <div class="row d-grid gap-3 mt-0 mb-4">
                                    <form action="' . $returnPage . '" class="d-grid gap-3 mt-0" method="post">
                                        <div class="container d-flex justify-content-center pt-3">
                                            <div class="form-floating login-sign-up-input d-flex">
                                                <input class="form-control d-flex align-items-center px-2" id="login-username-input" type="text" placeholder="exampleUsername" name="login-username" required>
                                                <label for="login-username-input" class="px-2 align-self-center"><small>Username</small></label>
                                            </div>
                                        </div>
    
                                        <div class="container d-flex justify-content-center">
                                            <div class="form-floating login-sign-up-input d-flex">
                                                <input class="form-control d-flex align-items-center px-2" id="login-password-input" type="password" placeholder="examplePassword" name="login-password" required>
                                                <label for="login-password-input" class="px-2 align-self-center"><small>Password</small></label>
                                            </div>
                                        </div>
    
                                        <div class="container d-flex justify-content-center">
                                            <button class="btn border-dark rounded-3 w-25 dark-gradient-btn" type="submit" name="login">
                                                Login
                                            </button>
                                        </div>
                                    </form>
    
                                    <div class="container d-flex justify-content-center">
                                        <p class="mb-0"><small>Not a member yet? <a href="" data-bs-target="#sign-up-overlay" data-bs-toggle="modal" data-bs-dismiss="modal">Sign up here!</a></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="sign-up-overlay" data-bs-backdrop="static" data-bs-keyboard="false">
    
                <div class="modal-dialog modal-dialog-centered modal-lg" >
    
                    <div class="modal-content d-flex">
    
                        <div class="container rounded-top position-relative bg-dark py-2">
                            <h5 class="modal-title text-center">Sign up for OP Computer Store</h5>
                            <button type="button" class="overlay-close-btn position-absolute d-flex justify-content-center align-items-center" data-bs-dismiss="modal">
                                <img src="/OPCS/resources/images/dark-theme/close-dark_theme.png" class="overlay-close-btn-icon" alt="">
                            </button>
                        </div>
    
                        <div class="modal-body p-0 rounded-bottom lighter-dark">
                            <div class="container-fluid mt-2 mb-1 py-2">
                                <div class="row d-grid gap-3 mt-0 mb-3">
                                    <form action="' . $returnPage . '" method="post" class="d-grid gap-3 mt-0">
                                        <div class="container-fluid">
                                            <div class="row">
    
                                                <div class="col-md px-0 mx-0">
                                                    <div class="container-md d-flex justify-content-center">
                                                        <div class="form-floating login-sign-up-input flex-column">
                                                            <input class="form-control d-flex align-items-center px-2" id="register-username-input" type="text" name="sign_up_username" placeholder="exampleUsername" required>
                                                            <label for="register-username-input" class="px-2 align-self-center"><small>Username</small></label>
                                                            <div class="invalid-feedback">Username Contained Space</div>
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-md px-0 mx-0">
                                                    <div class="container-md d-flex justify-content-center">
                                                        <div class="form-floating login-sign-up-input flex-column">
                                                            <input class="form-control d-flex align-items-center px-2" id="register-email-input" type="email" name="sign_up_email" placeholder="example_email@mail.com" required>
                                                            <label for="register-email-input" class="px-2 align-self-center"><small>Email Address</small></label>
                                                            <div class="invalid-feedback">Email Contained Space</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="container-fluid">
                                            <div class="row">
    
                                                <div class="col-md px-0 mx-0">
                                                    <div class="container-md d-flex justify-content-center">
                                                        <div class="form-floating login-sign-up-input d-flex flex-column">
                                                            <input class="form-control d-flex align-items-center px-2" id="register-contact-input" type="tel" name="sign_up_contact" placeholder="012-3456789" required>
                                                            <label for="register-contact-input" class="px-2 align-self-center"><small>Phone Number</small></label>
                                                            <div class="invalid-feedback hide invalid-phone">Invalid Phone Number</div>
                                                            <div class="space-phone hide ">Phone Number Contained Space</div>
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-md px-0 mx-0">
                                                    <div class="container-md d-flex justify-content-center">
                                                        <div class="form-floating login-sign-up-input d-flex">
                                                            <select class="form-select" id="register-gender-input" name="sign_up_gender" required>
                                                                <option selected disabled value="">Select</option>
                                                                <option value="male">Male</option>
                                                                <option value="female">Female</option>
                                                                <option value="other">Other</option>
                                                            </select>
    
                                                            <label class="form-label align-self-center" for="register-gender-input"><small>Gender</small></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="container-fluid">
                                            <div class="row">
    
                                                <div class="col-md px-0 mx-0">
                                                    <div class="container-md d-flex justify-content-center">
                                                        <div class="form-floating login-sign-up-input d-flex flex-column">
                                                            <input class="form-control d-flex align-items-center px-2" id="register-password-input" name="sign_up_password" type="password" placeholder="password" required>
                                                            <label for="register-password-input" class="px-2 align-self-center"><small>Password</small></label>
                                                            <div class="invalid-feedback space-password">Password Contained Space</div>
                                                            <div class="hide match-password">Password does not match</div>
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-md px-0 mx-0">
                                                    <div class="container-md d-flex justify-content-center">
                                                        <div class="form-floating login-sign-up-input d-flex flex-column">
                                                            <input class="form-control d-flex align-items-center px-2" id="register-confirm-password-input" name="sign_up_confirm_password" type="password" placeholder="password" required>
                                                            <label for="register-confirm-password-input" class="px-2 align-self-center"><small>Confirm Password</small></label>
                                                            <div class="invalid-feedback match-password">Password does not match</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="container-md d-flex justify-content-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="agree" name="terms-check" id="terms-conditions-check" required>
    
                                                <label class="form-check-label" for="terms-conditions-check">
                                                    <small>I agree and accept to the <a href="/OPCS/en/ask/terms-and-condition">terms and conditions</a>.</small>
                                                </label>
                                                
                                                <div class="invalid-feedback">
                                                    <p><small>You must agree before signing up!</small></p>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="container-md d-flex justify-content-center">
                                            <button id="sign-up-btn" class="btn border-dark rounded-3 w-25 dark-gradient-btn" name="sign-up" type="submit">
                                                Sign Up
                                            </button>
                                        </div>
                                    </form>
    
                                    <div class="container-sm d-flex justify-content-center">
                                        <p class="mb-0"><small>Already a member? <a href="" data-bs-target="#login-overlay" data-bs-toggle="modal" data-bs-dismiss="modal">Login here!</a></small></p>
                                    </div>
                                </div>
    
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
}