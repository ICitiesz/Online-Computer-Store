// Remove cart item DOM element.
function removeCartItem(e) {
    let cartItemID = e.closest("tr").id;

    console.log(cartItemID);
    $.ajax({
        url: '/OPCS/en/cart/checkout.php',
        type: 'POST',
        data: {
            checkoutCartItemID: cartItemID
        },
        success: function(data) {
            if (data.success) {

            }
        }
    });

    if (document.getElementById(cartItemID)) {
        document.getElementById(cartItemID).remove();
    }

    alert("You have removed 1 cart item!");
    window.location.href = "/OPCS/en/cart/checkout";
}
// Get billing info
function getBillingInfo(e) {
    if (e.value === "") {
        return;
    }

    $.ajax({
        url: '/OPCS/en/cart/checkout.php',
        type: 'POST',
        dataType: 'text',
        data: {checkoutBillingProfile: e.value},
        success: function(data) {
        }
    });

    window.location.href = "/OPCS/en/cart/checkout";
}

// Place order
function placeOrder() {
    let billingProfile = document.getElementById("checkout-billing-profile").value;

    if (billingProfile === "") {
        alert("Please select a billing profile!");
        return;
    }

    $.ajax({
        url: '/OPCS/en/cart/checkout.php',
        type: 'POST',
        dataType: 'text',
        data: {selectedBillingProfile: billingProfile},
        success: function(data) {
        }
    });

    alert("Your order has been placed!");
}

// Block Payment
function blockPayment(e) {
    if (document.getElementById("checkout-billing-profile").value === "") {
        e.removeAttribute("data-bs-target");
        e.removeAttribute("data-toggle");
        alert("Please select a billing profile!");
        return;
    }

    if (document.getElementById("no-cart-item-text") !== null) {
        e.removeAttribute("data-bs-target");
        e.removeAttribute("data-toggle");
        alert("Your cart is empty!");
    }
}

