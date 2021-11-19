// Get the product ID
let productID;

function getProductID(e) {
    productID = e.closest("tr").id;
}

// Reset the form
function clearReviewForm() {
    document.getElementById("rating-section").reset();
}

// Submit the product ID to the server
function submitProductID() {
    $.ajax({
        url: '/OPCS/en/account/purchase-history.php',
        type: 'POST',
        dataType: 'text',
        data: {productID: productID},
        success: function(data) {
            console.log("yes");
        }
    });
}
