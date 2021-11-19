// Below are the variables and functions that are used to make the page responsive on the user profile page.

let mediaQ = [
    window.matchMedia("(max-width: 991px)"),
    window.matchMedia("(max-width: 500px)"),
    window.matchMedia("(max-width: 767px)")
];

document.addEventListener("DOMContentLoaded", function () {
    for (let x of mediaQ) {
        x.addEventListener("change", changeStyle);
        changeStyle(x);
    }
});

function changeStyle(e) {
    switch (e.media) {
        case "(max-width: 991px)": {
            if (e.matches) {
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

        case "(max-width: 767px)": {
            break
        }

        case "(max-width: 500px)": {
            if (e.matches) {
                document.querySelector(".panel-container").classList.remove("mx-5");
                document.querySelector(".inner-content-frame").classList.remove("px-5");
                break;
            }

            document.querySelector(".panel-container").classList.add("mx-5");
            document.querySelector(".inner-content-frame").classList.add("px-5");
            break;
        }
    }
}