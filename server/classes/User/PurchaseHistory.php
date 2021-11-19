<?php

namespace User;

use Utils\Utils;
include(__DIR__ . "/../../../server/includes/utils/Conn.php");

class PurchaseHistory {
    private static array $purchaseHistory = [];

    /* Render all the purchase that have been made by the user */
    public static function renderPurchaseHistory():void {
        $resultPurchaseHistory = PurchaseHistoryQuery::fetchPurchaseHistory(UserSession::getSessionUserID());

        if ($resultPurchaseHistory->num_rows === 0) {
            echo '<div class="row my-3" id="no-purchase-history-text">
                        <div class="col-lg-12">
                            <h5 class="text-center">
                                You currently don\'t have any purchase history yet!
                            </h5>
                        </div>
                </div>';
            return;
        }

        foreach ($resultPurchaseHistory as $item) {
            self::$purchaseHistory[] = $item;

            $itemIndex = array_search($item, self::$purchaseHistory, true) + 1;

            global $tableRow;
            global $deliveryStatus;

            $tableRow = "";
            $deliveryStatus = "";
            $subtotal = PurchaseHistoryQuery::calculateGrandTotal(UserSession::getSessionUserID(), $item['Member_Order_ID'])->fetch_assoc()['Grand_Total'];
            $shippingFee = 15;
            $discountPercentage = 0.1;
            $discountTotal = $discountPercentage * $subtotal;
            $grandTotal = $subtotal + $shippingFee - $discountTotal;

            switch ($item['Delivery_Status']) {
                case 'Preparing': {
                    $deliveryStatus = '<span style="color: gold; font-weight: bold">&nbsp;Preparing&nbsp;</span>';
                    break;
                }

                case 'Delivering': {
                    $deliveryStatus = '<span style="color: aqua; font-weight: bold">&nbsp;Delivering&nbsp;</span>';
                    break;
                }

                case 'Completed': {
                    $deliveryStatus = '<span style="color: limegreen; font-weight: bold">&nbsp;Delivered&nbsp;</span>';
                    break;
                }

                case 'Cancelled': {
                    $deliveryStatus = '<span style="color: red; font-weight: bold">&nbsp;Cancelled&nbsp;</span>';
                    break;
                }
            }

            global $purchaseHistoryDetails;

            $resultPurchaseHistoryDetails = PurchaseHistoryQuery::fetchPurchaseHistoryDetails(UserSession::getSessionUserID(), $item['Member_Order_ID']);
            $purchaseHistoryDetails = [];

            foreach ($resultPurchaseHistoryDetails as $value) {
                $purchaseHistoryDetails[] = $value;

                $valueIndex = array_search($value, $purchaseHistoryDetails, true) + 1;

                global $reviewStatus;
                $reviewStatus = "";

                if ($item['Delivery_Status'] !== 'Completed') {
                    $reviewStatus = "disabled";
                }

                if ((PurchaseHistoryQuery::fetchReview(UserSession::getSessionUserID(), $value["Product_ID"])->num_rows === 1)) {
                    $reviewStatus = "disabled";
                }

                $tableRow .= "<tr class='table-body-row' id='product-id-" . $value["Product_ID"] . "'>
                                <th class='text-center'>" . $valueIndex . "</th>
                                <td><img class='purchase-history-product-image' src='/OPCS/data/product_images/" . $value['Product_Image'] . "'></td>
                                <td class='text-wrap'>" . $value['Product_Name'] . "</td>
                                <td class='text-center'>" . $value['Quantity'] . "</td>
                                <td class='text-center' >RM " . number_format($value['Total'], 2) . "</td>
                                <td class='text-center'><button class='btn review-btn' data-bs-toggle='modal' data-bs-target='#rating-overlay' onclick='getProductID(this)'" . $reviewStatus . ">Review Here!</button></td>
                            </tr>";
            }

            echo
                '<div class="accordion-item cart-dependent" id="purchase-history-id-' . $item['Member_Order_ID'] . '">
                <h4 class="accordion-header">
                    <button class="accordion-button collapsed text-nowrap" type="button" data-bs-toggle="collapse" data-bs-target="#purchase-history-' . $itemIndex . '"><span style="font-weight: bold">Order#' . $item["Member_Order_ID"] . '&nbsp;</span> | <span style="font-weight: bold">&nbsp;Status: </span>'. $deliveryStatus .' | <span style="font-weight: bold">&nbsp;Order Date:&nbsp;</span><span style="font-weight: bold; color: #575757">' . $item["Date"] . '</span></button>
                </h4>
                <div class="accordion-collapse collapse " id="purchase-history-' . $itemIndex . '" data-bs-parent="#purchase-history">
                    <div class="accordion-body accordion-background">
                        <div class="container-fluid table-responsive rounded-3 overflow-auto table-overflow-x px-0" style="max-height: 300px">
                            <table class="table table-bordered table-border-thick rounded-3">
                                <thead class="top-0 position-sticky table-header">
                                    <tr>
                                        <th scope="col" class="table-head-number text-nowrap">No.</th>
                                        <th scope="col" class="table-head-item text-nowrap text-center">Product</th>
                                        <th scope="col" class="table-head-item-name text-nowrap text-center">Product Name</th>
                                        <th scope="col" class="table-head-quantity text-center text-nowrap">Quantity</th>
                                        <th scope="col" class="table-head-total text-center text-nowrap">Total</th>
                                        <th scope="col" class="text-wrap text-center">Product Review</th>
                                    </tr>
                                </thead>

                                <tbody class="text-nowrap">
                                    ' . $tableRow . '
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="container-fluid bg-info px-0 mt-3 rounded-3">
                            <table class="table table-border-thick rounded-3">
                                <thead class="bg-info">
                                    <tr>
                                        <th scope="col" colspan="10" class="total-bg">Subtotal</th>
                                        <th scope="col" class="text-center total-price-bg w-25">RM ' . number_format($subtotal, 2) . '</th>
                                    </tr>

                                    <tr>
                                        <th scope="col" colspan="10" class="total-bg">Shipping Fee</th>
                                        <th scope="col" class="text-center total-price-bg w-25">RM ' . number_format($shippingFee, 2) . '</th>
                                    </tr>

                                    <tr>
                                        <th scope="col" colspan="10" class="total-bg">Discount ' . ($discountPercentage * 100) . '%</th>
                                        <th scope="col" class="text-center total-price-bg w-25">RM ' . number_format($discountTotal, 2) . '</th>
                                    </tr>

                                    <tr>
                                        <th scope="col" colspan="10" class="total-bg">Grand Total</th>
                                        <th scope="col" class="text-center total-price-bg w-25">RM ' . number_format($grandTotal, 2) . '</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }
    }

    /* Render the rating modal */
    public static function renderRatingModal():void {
        $resultPurchaseHistory = PurchaseHistoryQuery::fetchPurchaseHistory(UserSession::getSessionUserID());

        if ($resultPurchaseHistory->num_rows === 0) {
            return;
        }

        echo '<div class="modal fade" id="rating-overlay" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content modal-rating-content d-flex">
                                <div class="container position-relative rounded-top bg-dark py-2">
                                    <h5 class="modal-title text-center">Write a Review</h5>
                                    <button type="button" class="overlay-close-btn position-absolute d-flex justify-content-center align-items-center" data-bs-dismiss="modal" onclick="clearReviewForm()">
                                        <img src="/OPCS/resources/images/dark-theme/close-dark_theme.png" class="overlay-close-btn-icon" alt="">
                                    </button>
                                </div>

                                <div class="modal-body d-grid gap-5 p-0 rounded-bottom lighter-dark">
                                    <form action="" class="d-grid gap-5 p-0 rounded-bottom" id="rating-section" method="post">
                                        <div class="rating-content d-flex justify-content-center mt-3">
                                            <div class="stars-row">
                                                <input type="radio" class="rate-btn" value="5" name="rating-value" id="rating-5" required>
                                                <label for="rating-5" class="fas fa-star"></label>

                                                <input type="radio" class="rate-btn" value="4" name="rating-value" id="rating-4" required>
                                                <label for="rating-4" class="fas fa-star"></label>

                                                <input type="radio" class="rate-btn" value="3" name="rating-value" id="rating-3" required>
                                                <label for="rating-3" class="fas fa-star"></label>

                                                <input type="radio" class="rate-btn" value="2" name="rating-value" id="rating-2" required>
                                                <label for="rating-2" class="fas fa-star"></label>

                                                <input type="radio" class="rate-btn" value="1" name="rating-value" id="rating-1" required>
                                                <label for="rating-1" class="fas fa-star"></label>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="d-flex justify-content-center mx-4">
                                                <textarea class="w-100" placeholder="Give comments to our products ❤" id="comment-area" name="feedback" rows="10" required></textarea>
                                            </label>
                                        </div>

                                        <div class="modal-footer bg-dark border-dark d-flex payment-overlay-footer position-relative justify-content-around">
                                            <div class="d-inline-block">
                                                <button type="button" class="btn btn-lg review-cancel dark-gradient-btn" data-bs-dismiss="modal" onclick="clearReviewForm()">Cancel</button>
                                            </div>

                                            <div class="d-inline-block">
                                                <button type="submit" class="btn btn-lg review-submit" name="submit-review" onclick="submitProductID()">Submit Review</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
    }

    public static function addProductReview($userID):void {
        if (!Utils::isMethodVarSet("post", "submit-review")) {
            return;
        }

        $productID = explode( "-", $_SESSION["productID"])[2];
        $rating = $_POST["rating-value"];
        $feedback = $_POST["feedback"];

        if (PurchaseHistoryQuery::fetchReview($userID, $productID)->num_rows === 1) {
            echo '<script>alert("You have already reviewed this product!")</script>';
            return;
        }

        $isFeedbackValid = InputFieldValidation::validateFeedback($feedback);

        if (!$isFeedbackValid) {
            return;
        }

        if (empty($rating)) {
            $rating = 0;
        }
        echo '<script>alert("Review submitted successfully!")</script>';

        PurchaseHistoryQuery::appendReview($rating, $feedback,  $productID, $userID);
        UserSession::removeSessionVar("productID");
    }
}