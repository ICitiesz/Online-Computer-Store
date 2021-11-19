<?php

namespace User;

class BillingInfo {
    private static array $billingProfileArray = array();
    private static array $billingIDArray = array();

    /* Create DOM element for billing profile. */
    public static function createBillingProfile():void {
        if (!isset($_SESSION["create-billing-profile"])) {
            return;
        }

        echo
        '<div class="accordion-item cart-dependent" id="new-billing-id">
            <h4 class="accordion-header">
                <button class="accordion-button collapsed text-nowrap" type="button" data-bs-toggle="collapse" data-bs-target="#new-billing">New Billing Profile</button>
            </h4>
            <div class="accordion-collapse collapse " id="new-billing" data-bs-parent="#billing-info">
                <div class="accordion-body">
                    <form action="" class="d-grid gap-3" method="post">
                        <div class="form-floating" style="color: black">
                            <input class="form-control d-flex align-items-center px-2" id="new-billing-profile-name" type="text" placeholder="exampleProfileName" name="new-billing-profile-name" required>
                            <label for="new-billing-profile-name" class="px-2 align-self-center"><small>Billing Profile Name</small></label>
                        </div>

                        <div class="form-floating" style="color: black">
                            <input class="form-control d-flex align-items-center px-2" id="new-billing-recipient-name" type="text" placeholder="exampleRecipientName" name="new-billing-recipient-name" required>
                            <label for="new-billing-recipient-name" class="px-2 align-self-center"><small>Recipient Name</small></label>
                        </div>

                        <div class="form-floating" style="color: black">
                            <input class="form-control d-flex align-items-center px-2" id="new-billing-contact" type="tel" placeholder="0123456789" name="new-billing-recipient-contact-number" required>
                            <label for="new-billing-contact" class="px-2 align-self-center"><small class="text-nowrap">Contact Number</small></label>
                        </div>

                        <div class="form-floating d-flex" style="color: black">
                            <textarea class="form-control d-flex align-items-center px-2" id="new-recipient-billing-address" placeholder="Your billing address here!" name="new-recipient-billing-address" required></textarea>
                            <label for="new-recipient-billing-address" class="px-2 align-self-center"><small>Billing Address</small></label>
                        </div>

                        <div class="row d-flex justify-content-around billing-info-option">
                            <div class="col d-flex justify-content-center billing-info-save-container">
                                <button class="btn btn-lg billing-info-save px-4 bg-info" type="submit" name="new-billing-profile-submit">Save</button>
                            </div>

                            <div class="col d-flex justify-content-center">
                                <button class="btn btn-lg billing-info-delete px-4 bg-info" type="button" data-bs-toggle="modal" data-bs-target="#billing-profile-delete-confirmation" data-bs-parent="#new-billing" onclick="getProfileID(this)">Delete</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        ';

        UserSession::removeSessionVar("create-billing-profile");
    }

    /* Prepare the billing profile */
    public static function prepareBillingProfile():void {
        $result = BillingInfoQuery::fetchBillingInfo(UserSession::getSessionUserID());

        foreach ($result as $item) {
            self::$billingProfileArray[] = $item;

            $itemIndex = array_search($item, self::$billingProfileArray, true) + 1;

            self::$billingIDArray["billing-profile-" . $itemIndex] = $item["Billing_ID"];
        }

        self::removeBillingProfile();
        self::renderBillingProfile();
    }

    /* Add billing profile */
    public static function addBillingProfile():void {
        if (isset($_POST["new-billing-profile-submit"])) {
            BillingInfoQuery::registerBillingInfo(UserSession::getSessionUserID());
        }
    }

    /* Remove billing profile function */
    public static function removeBillingProfile():void {
        if (!isset($_SESSION["reference"])) {
            return;
        }

        $isDeleted = BillingInfoQuery::deleteBillingInfo(UserSession::getSessionUserID(), self::$billingIDArray[$_SESSION["reference"]]);

        if (!$isDeleted) {
            echo '<script>alert("Sorry, you\'re not able to delete this billing profile due to this billing profile attached to 1 or multiple record in our database.")</script>';
        }

        UserSession::removeSessionVar("reference");
    }

    /* Render billing profile on billing profile page */
    public static function renderBillingProfile():void {
        $result = BillingInfoQuery::fetchBillingInfo(UserSession::getSessionUserID());

        if ($result->num_rows === 0) {
            echo '<div class="row my-3" id="no-billing-profile-text">
                        <div class="col-lg-12">
                            <h5 class="text-center">
                                You currently don\'t have any billing profile yet!
                            </h5>
                        </div>
                </div>';
            return;
        }

        foreach ($result as $item) {
            self::$billingProfileArray[] = $item;

            $itemIndex = array_search($item, self::$billingProfileArray, true) + 1;

            self::$billingIDArray["billing-profile-" . $itemIndex] = $item["Billing_ID"];

            echo
            '<div class="accordion-item cart-dependent" id="billing-profile-id-' . $itemIndex . '">
                <h4 class="accordion-header">
                    <button class="accordion-button collapsed text-nowrap" type="button" data-bs-toggle="collapse" data-bs-target="#billing-profile-' . $itemIndex . '"><span style="font-weight: bold">' . $item["Billing_Profile_Name"] . '</span></button>
                </h4>
                <div class="accordion-collapse collapse " id="billing-profile-' . $itemIndex . '" data-bs-parent="#billing-info">
                    <div class="accordion-body">
                        <form action="" id="billing-profile-form-' . $itemIndex . '" method="post" class="d-grid gap-3">
                            <input type="hidden" name="reference" value="billing-profile-' . $itemIndex . '" >
                            
                            <div class="form-floating" style="color: black">
                                <input class="form-control d-flex align-items-center px-2" id="billing-profile-name-' . $itemIndex . '" type="text" placeholder="exampleProfileName" name="billing_profile_name" value="' . $item["Billing_Profile_Name"] . '" required>
                                <label for="billing-profile-name-' . $itemIndex . '" class="px-2 align-self-center"><small>Billing Profile Name</small></label>
                            </div>

                            <div class="form-floating" style="color: black">
                                <input class="form-control d-flex align-items-center px-2" id="billing-recipient-name-' . $itemIndex . '" type="text" placeholder="exampleRecipientName" value="' . $item["Recipient_Name"] . '" required>
                                <label for="billing-recipient-name-' . $itemIndex . '" class="px-2 align-self-center"><small>Recipient Name</small></label>
                            </div>

                            <div class="form-floating" style="color: black">
                                <input class="form-control d-flex align-items-center px-2" id="billing-contact-' . $itemIndex . '" type="tel" placeholder="0123456789" value="' . $item["Recipient_Contact_Number"] . '" required>
                                <label for="billing-contact-' . $itemIndex . '" class="px-2 align-self-center"><small class="text-nowrap">Contact Number</small></label>
                            </div>

                            <div class="form-floating d-flex" style="color: black">
                                <textarea class="form-control d-flex align-items-center px-2" id="billing-billing-address-' . $itemIndex . '" placeholder="Your billing address here!" required>' . $item["Billing_Address"] .  '</textarea>
                                <label for="billing-billing-address-' . $itemIndex . '" class="px-2 align-self-center"><small>Billing Address</small></label>
                            </div>

                            <div class="row d-flex justify-content-around billing-info-option">
                                <div class="col d-flex justify-content-center billing-info-save-container">
                                    <button class="btn btn-lg billing-info-save px-4 bg-info" type="submit">Save</button>
                                </div>
    
                                <div class="col d-flex justify-content-center">
                                    <button class="btn btn-lg billing-info-delete px-4 bg-info" name="delete_profile" type="button" data-bs-toggle="modal" data-bs-target="#billing-profile-delete-confirmation" data-bs-parent="#billing-profile-' . $itemIndex . '" onclick="getProfileID(this)">Delete</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            ';
        }
    }

    /* Render the billing profile list for checkout page */
    public static function renderBillingProfileList($isDisplay = true) {
        $result = BillingInfoQuery::fetchBillingInfo(UserSession::getSessionUserID());

        if ($result->num_rows === 0) {
            return false;
        }

        if (!$isDisplay) {
            foreach ($result as $item) {
                self::$billingProfileArray[] = $item;

                $itemIndex = array_search($item, self::$billingProfileArray, true) + 1;

                self::$billingIDArray["billing-profile-" . $itemIndex] = $item["Billing_ID"];
            }

            return self::$billingIDArray;
        }

        foreach ($result as $item) {
            self::$billingProfileArray[] = $item;

            $itemIndex = array_search($item, self::$billingProfileArray, true) + 1;

            self::$billingIDArray["billing-profile-" . $itemIndex] = $item["Billing_ID"];

            echo '<option id="billing-profile-' . $itemIndex . '" value="billing-profile-' . $itemIndex . '">' . $item['Billing_Profile_Name'] . '</option>';
        }

        return true;
    }

    /* Render the billing information for checkout page */
    public static function renderBillingInfo():void {
        if (UserSession::isSessionSet("checkoutBillingProfile")) {
            $selectedBillingProfile = $_SESSION["checkoutBillingProfile"];

            if ($selectedBillingProfile === "") {
                echo '<div class="alert alert-danger" role="alert">
                    <strong>Error!</strong> Please select a billing profile.';
                UserSession::removeSessionVar("checkoutBillingProfile");
                return;
            }

            $result = BillingInfoQuery::fetchBillingInfo(UserSession::getSessionUserID());

            if ($result->num_rows === 0) {
                echo '<div class="alert alert-danger" role="alert">
                    <strong>Error!</strong> Please select a billing profile.';
                UserSession::removeSessionVar("checkoutBillingProfile");
                return;
            }

            global $billingDetails;

            $billingDetails = [];

            foreach ($result as $item) {
                self::$billingProfileArray[] = $item;

                $itemIndex = array_search($item, self::$billingProfileArray, true) + 1;
                $billingDetails["billing-profile-" . $itemIndex] = $item;
            }

            $recipientName = $billingDetails[$selectedBillingProfile]["Recipient_Name"];
            $recipientContact = $billingDetails[$selectedBillingProfile]["Recipient_Contact_Number"];
            $recipientBillingAddress = $billingDetails[$selectedBillingProfile]["Billing_Address"];

            echo '<div class="container-fluid d-grid rounded-3 gap-5 py-5 lighter-dark">
                    <div class="container-md mt-4 d-flex justify-content-center">
                        <div class="form-floating checkout-recipient-name">
                            <input class="form-control d-flex align-items-center px-2" id="checkout-recipient-name" type="text" placeholder="exampleRecipientName" value="'. $recipientName . '" readonly>
                            <label for="checkout-recipient-name" class="px-2 align-self-center"><small>Recipient Name</small></label>
                        </div>
                    </div>

                    <div class="container-md d-flex justify-content-center">
                        <div class="form-floating checkout-recipient-contact">
                            <input class="form-control d-flex align-items-center px-2" id="checkout-recipient-contact" type="tel" placeholder="0123456789" value="'. $recipientContact . '" readonly>
                            <label for="checkout-recipient-contact" class="px-2 align-self-center"><small>Contact Number</small></label>
                        </div>
                    </div>

                    <div class="container-md d-flex mb-5 justify-content-center">
                        <div class="form-floating checkout-recipient-address d-flex">
                            <textarea class="form-control d-flex align-items-center px-2" id="checkout-recipient-billing-address" placeholder="Your destination address here!" readonly>'. $recipientBillingAddress . '</textarea>
                            <label for="checkout-recipient-billing-address" class="px-2 align-self-center"><small>Billing Address</small></label>
                        </div>
                    </div>
                </div>';

            echo '<script id="temp-script">
                document.getElementById("non-selected").selected = false;
                document.getElementById("' . $selectedBillingProfile . '").selected = true;
                document.getElementById("temp-script").remove();
            </script>';
            UserSession::removeSessionVar("checkoutBillingProfile");
        }
    }
}

?>