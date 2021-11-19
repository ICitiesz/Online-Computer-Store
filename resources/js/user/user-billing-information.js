// Line 3 ~ line 59 is the code to make the page responsive for the billing information page.

let mediaQ = [
    window.matchMedia("(max-width: 991px)"),
    window.matchMedia("(max-width: 500px)"),
    window.matchMedia("(max-width: 647px)")
];

document.addEventListener("DOMContentLoaded", function () {
    for (let x of mediaQ) {
        x.addEventListener("change", changeStyle);
        changeStyle(x)
    }
});

function changeStyle(e) {
    switch (e.media) {
        case "(max-width: 991px)": {
            if (e.matches) {
                /* Username Display Configuration */
                document.querySelector(".username-display-box").classList.replace("align-items-end", "align-items-center");
                document.querySelector(".username-display-box").classList.add("justify-content-center");
                document.querySelector(".username-display-box").classList.add("mt-2");
                document.querySelector("#username-display").classList.add("mb-0");
                break;
            }

            document.querySelector(".username-display-box").classList.replace("align-items-center", "align-items-end");
            document.querySelector(".username-display-box").classList.remove("justify-content-center");
            document.querySelector(".username-display-box").classList.remove("mt-2");
            document.querySelector("#username-display").classList.remove("mb-0");
            break;
        }

        case "(max-width: 647px)": {
            if (e.matches) {
                document.querySelector(".billing-info-save-container").classList.add("mb-3");
                break;
            }

            document.querySelector(".billing-info-save-container").classList.remove("mb-3");
            break;
        }

        case "(max-width: 500px)": {
            if (e.matches) {
                /* .panel-container & .inner-content-frame Display Configuration */
                document.querySelector(".panel-container").classList.remove("mx-5");
                document.querySelector(".inner-content-frame").classList.remove("px-5");
                break;
            }

            /* .panel-container & .inner-content-frame Display Configuration */
            document.querySelector(".panel-container").classList.add("mx-5");
            document.querySelector(".inner-content-frame").classList.add("px-5");
            break;
        }
    }
}

/* Remove Billing Profile Functions */
let billingProfileID;

function getProfileID(e) {
    billingProfileID = e.closest("div .accordion-item").id;
}

function removeBillingProfile() {
    if (document.getElementById("billing-profile-form-" + billingProfileID.split("-")[3])) {
        document.getElementById("billing-profile-form-" + billingProfileID.split("-")[3]).submit();
    }
    document.getElementById(billingProfileID).remove();
}
