<?php

namespace User;

use Utils\Utils;

class Checkout {
    private static array $cartItems = [];

    /* Check if member have cart, if not create for them */
    public static function createCart():void {
        if (CheckoutQuery::fetchUserCartID(UserSession::getSessionUserID()) === null) {
            CheckoutQuery::createCart(UserSession::getSessionUserID());
        }
    }

    public static function renderCartItems($userID): void {
        $cartID = CheckoutQuery::fetchUserCartID($userID);
        $result = CheckoutQuery::fetchUserCartItems($cartID);

        if ($result->num_rows === 0) {
            echo '<div class="row my-3 mt-5 py-5" id="no-cart-item-text">
                        <div class="col-lg-12">
                            <h5 class="text-center">
                                Your cart is empty now! Go shopping and add some items to your cart.
                            </h5>
                        </div>
                </div>';
            return;
        }

        $tableRow = "";
        $subtotal = CheckoutQuery::calculateGrandTotalCart($cartID);
        $shippingFee = 15;
        $discountPercentage = 0.1;
        $discountTotal = $discountPercentage * $subtotal;
        $grandTotal = $subtotal + $shippingFee - $discountTotal;

        foreach ($result as $item) {
            self::$cartItems[] = $item;

            $itemIndex = array_search($item, self::$cartItems, true) + 1;

            $tableRow .= "<tr class='table-body-row' id=cart-item-id-" . $item["Cart_Item_ID"] . ">
                                <th>" . $itemIndex . "</th>
                                <td><img class='cart-product-image' src='/OPCS/data/product_images/" . $item['Product_Image'] . "' alt=''></td>
                                <td class='text-wrap'>" . $item['Product_Name'] . "</td>
                                <td class='text-center'>RM " . number_format($item['Price'], 2) . "</td>
                                <td class='text-center'>" . $item['Quantity'] . "</td>
                                <td class='text-center' >RM " . number_format($item['Total'], 2) . "</td>
                                <td class='text-center d-flex align-items-center'><button class='btn remove-cart-item-btn' onclick='removeCartItem(this)'>Remove</button></td>
                            </tr>";
        }

        echo '<div class="container-fluid table-responsive table-whole-bg rounded-3 overflow-auto table-overflow-x px-0" style="min-height: 350px; max-height: 350px">
                        <table class="table table-bordered table-border-thick rounded-3">
                            <thead class="top-0 position-sticky table-header rounded-3">
                            <tr>
                                <th scope="col" class="table-head-number text-nowrap">No.</th>
                                <th scope="col" class="table-head-item text-center text-nowrap text-center">Product</th>
                                <th scope="col" class="table-head-item-name text-center text-nowrap text-center">Product Name</th>
                                <th scope="col" class="table-head-total text-nowrap">Product Price</th>
                                <th scope="col" class="table-head-quantity text-center text-nowrap">Quantity</th>
                                <th scope="col" class="table-head-total text-center text-nowrap">Total</th>
                                <th scope="col" class="text-wrap text-center">Removal</th>
                            </tr>
                            </thead>

                            <tbody class="text-nowrap table-body-row">
                                ' . $tableRow . '
                            </tbody>
                        </table>
                    </div>

                    <div class="container-fluid bg-info px-0 mt-3 rounded-3">
                        <table class="table table-border-thick rounded-3">
                            <thead class="bg-info">
                            <tr>
                                <th scope="col" colspan="10" class="total-bg">Subtotal</th>
                                <th scope="col" class="total-price-bg w-25 text-center">RM ' . number_format($subtotal, 2) . '</th>
                            </tr>

                            <tr>
                                <th scope="col" colspan="10" class="total-bg">Shipping </th>
                                <th scope="col" class="total-price-bg w-25 text-center">RM ' . number_format($shippingFee, 2) . '</th>
                            </tr>

                            <tr>
                                <th scope="col" colspan="10" class="total-bg">Discount ' . ($discountPercentage * 100) . '%</th>
                                <th scope="col" class="total-price-bg w-25 text-center">RM ' . number_format($discountTotal, 2) . '</th>
                            </tr>

                            <tr>
                                <th scope="col" colspan="10" class="total-bg">Grand Total</th>
                                <th scope="col" class="total-price-bg w-25 text-center">RM ' . number_format($grandTotal, 2) . '</th>
                            </tr>
                            </thead>
                        </table>
                    </div>';
    }

    // Remove cart item
    public static function removeCartItem():void {
        if (UserSession::isSessionSet("checkoutCartItemID")) {
            $cartItemID = $_SESSION['checkoutCartItemID'];
            CheckoutQuery::deleteCartItem(explode("-", $cartItemID)[3]);
            UserSession::removeSessionVar("checkoutCartItemID");
        }
    }

    // Process checkout
    public static function processCheckout($userID):void {
        if (!Utils::isMethodVarSet("POST", "place-order")) {
            return;
        }

        $cartID = CheckoutQuery::fetchUserCartID($userID);
        $result = CheckoutQuery::fetchUserCartItems($cartID);

        if ($result->num_rows === 0) {
            echo '<script>alert("Your cart is empty!")</script>';
            return;
        }

        $isCardHolderValid = InputFieldValidation::validateCardHolderName($_POST['card-holder']);
        $isCardNumberValid = InputFieldValidation::validateCardNumber($_POST['card-number']);
        $isCardExpMonthValid = InputFieldValidation::validateCardExpiryMonth($_POST['exp-month']);
        $isCardExpYearValid = InputFieldValidation::validateCardExpiryYear($_POST['exp-year']);
        $isCardCVVValid = InputFieldValidation::validateCardCVV($_POST['card-cvv']);

        if (!$isCardHolderValid || !$isCardNumberValid || !$isCardExpMonthValid || !$isCardExpYearValid || !$isCardCVVValid) {
            return;
        }

        CheckoutQuery::addMemberOrder($userID, BillingInfo::renderBillingProfileList(false)[$_SESSION["selectedBillingProfile"]]);

        foreach ($result as $item) {
            $productID = $item['Product_ID'];
            $quantity = $item['Quantity'];

            CheckoutQuery::appendMemberOrderDetails($productID, PurchaseHistoryQuery::fetchPurchaseHistory($userID, false)->fetch_assoc()["Member_Order_ID"],$quantity);
            CheckoutQuery::updateProductQuantity($productID, CheckoutQuery::fetchProductQuantity($productID), $quantity);
            CheckoutQuery::deleteCartItem($item['Cart_Item_ID']);
        }

        UserSession::removeSessionVar("selectedBillingProfile");
        header("Location: /OPCS/en/account/purchase-history");
        exit();
    }
}