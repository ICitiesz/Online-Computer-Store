// Function for opening the search overlay
let openBtn = document.getElementById('search-btn');

openBtn.addEventListener("click", () => {
    document.getElementById('searchBar').classList.add('show-search-bar');
    document.getElementById('searchField').focus();
});

// Closing Overlay Search
let closeBtn = document.getElementById('close-btn');
closeBtn.addEventListener('click', () =>{
    document.getElementById('searchBar').classList.remove('show-search-bar')
});

// Closing Overlay Search
window.onclick = (event) => {
    if (event.target === document.getElementById('searchBar')){
        document.getElementById('searchBar').classList.remove('show-search-bar');
    }
};

// // Opening Cart Menu
// let cartBtn = document.getElementById('cart-btn');
//
// cartBtn.addEventListener('click', () => {
//     document.querySelector('.cart-menu').classList.toggle('show-cart');
// });

let cartBtn = document.getElementById('cart-btn');

cartBtn.addEventListener('click', () => {
    if(document.getElementsByClassName('cart-dependent') !== 'undefined'){
        let accordion = document.getElementsByClassName('cart-dependent');
        for (let i = 0; i < accordion.length; i++) {
            accordion[i].classList.toggle('disable-hover');

        }
    }
    document.querySelector('.cart-menu').classList.toggle('show-cart');
});

// Change size of the cart menu box on scroll
window.onscroll = () => {
    if (window.pageYOffset > 60) {
        document.querySelector('.cart-menu').style.top = 0;
        document.querySelector('.cart-menu').style.maxHeight = "100%";
    }
    else{
        document.querySelector('.cart-menu').style.top = "80px";
        document.querySelector('.cart-menu').style.maxHeight = "calc(100vh - 80px)";
    }
};


// Registration form validation
try {
    var usernameInput = document.getElementById('register-username-input');
    var emailInput = document.getElementById('register-email-input');
    var phoneNumberInput = document.getElementById('register-contact-input');
    var passwordInput = document.getElementById('register-password-input');
    var confirmPasswordInput = document.getElementById('register-confirm-password-input');
    var signupBtn = document.getElementById('sign-up-btn');

    var usernameCorrect = true;
    var emailCorrect = true;
    var phoneCorrect = true;
    var passwordCorrect = true;
    var confirmCorrect = true;

    usernameInput.addEventListener('keyup', ()=>{
        if (usernameInput.value.indexOf(" ") > -1){
            usernameInput.classList.add('is-invalid');
            signupBtn.setAttribute('disabled', 'true');
            usernameCorrect = false;
        }
        else{
            usernameInput.classList.remove('is-invalid')
            usernameCorrect = true;
        }
        validateBtn();
    });

    emailInput.addEventListener('keyup', () => {
        if (emailInput.value.indexOf(" ") > -1){
            emailInput.classList.add('is-invalid');
            emailCorrect = false;
        }
        else{
            emailInput.classList.remove('is-invalid');
            emailCorrect = true;
        }
        validateBtn();
    });

    phoneNumberInput.addEventListener('keyup', () => {
        // show error message if contain space
        if (phoneNumberInput.value.indexOf(" ") > -1){
            phoneNumberInput.classList.add('is-invalid');
            document.querySelector('.invalid-phone').classList.remove('invalid-feedback');
            document.querySelector('.space-phone').classList.add('invalid-feedback');
            document.querySelector('.space-phone').classList.remove('hide');
            phoneCorrect = false;
        }
        // show error message if contain '-' and length more than 12
        else if (phoneNumberInput.value.indexOf('-') > -1){
            if(phoneNumberInput.value.length > 12){
                phoneNumberInput.classList.add('is-invalid');
                document.querySelector('.invalid-phone').classList.add('invalid-feedback');
                document.querySelector('.invalid-phone').classList.remove('hide');
                phoneCorrect = false;
            }
            else{
                phoneNumberInput.classList.remove('is-invalid');
                document.querySelector('.invalid-phone').classList.remove('invalid-feedback');
                document.querySelector('.invalid-phone').classList.add('hide');
                phoneCorrect = true;
            }
        }
        // show error message if does not contain '-' but length more than 11
        else if(phoneNumberInput.value.length > 11){
            phoneNumberInput.classList.add('is-invalid');
            document.querySelector('.invalid-phone').classList.add('invalid-feedback');
            document.querySelector('.invalid-phone').classList.remove('hide');
            phoneCorrect = false;
        }
        else{
            phoneNumberInput.classList.remove('is-invalid');
            document.querySelector('.invalid-phone').classList.remove('invalid-feedback');
            document.querySelector('.invalid-phone').classList.add('hide');
            document.querySelector('.space-phone').classList.remove('invalid-feedback');
            document.querySelector('.space-phone').classList.add('hide');
            phoneCorrect = true;
        }

        validateBtn();
    });

    passwordInput.addEventListener('keyup', () => {
        if (passwordInput.value.indexOf(" ") > -1){
            passwordInput.classList.add('is-invalid');
            document.querySelector('.space-password').classList.remove('hide');
            passwordCorrect = false;
        }
        else{
            passwordInput.classList.remove('is-invalid');
            document.querySelector('.space-password').classList.add('hide');
            passwordCorrect = true;
        }
        validateBtn();
    });

    confirmPasswordInput.addEventListener('keyup', () => {
        if(confirmPasswordInput.value !== passwordInput.value){
            confirmPasswordInput.classList.add('is-invalid');
            document.getElementsByClassName('match-password').forEach(element => {
                element.classList.add('invalid-feedback');
                element.classList.remove('hide');
            });
            confirmCorrect = false;
        }
        else{
            confirmPasswordInput.classList.remove('is-invalid');
            confirmCorrect = true;
        }
        validateBtn();

    });

    function validateBtn(){
        if (confirmCorrect===true && passwordCorrect===true && emailCorrect===true && phoneCorrect===true && usernameCorrect===true){
            document.getElementById('sign-up-btn').removeAttribute('disabled');
        }
        else{
            document.getElementById('sign-up-btn').setAttribute('disabled', 'true');
        }
    }

}
catch (err){
    console.log("Error 404");
}


// Cart add minus button
try {
    var plusBtn = document.getElementsByClassName('plus-btn');
    var minusBtn = document.getElementsByClassName('minus-btn');
    var quantityBar = document.getElementsByClassName('quantity-input');
    var availableStock = document.getElementsByClassName('product-stock');
    var warningMessage = document.getElementsByClassName('warning-quantity');
    var updateButton = document.getElementsByClassName('update-btn');
    var hrefValue = [];

    for (let i=0; i<plusBtn.length; i++){
        // Validation while adding the number of the quantity value
        plusBtn[i].addEventListener('click', ()=>{
            if(parseInt(availableStock[i].value) > parseInt(quantityBar[i].value)){
                quantityBar[i].value++;
                hrefValue[i] = quantityBar[i].value;
                if(availableStock[i].value === quantityBar[i].value){
                    warningMessage[i].classList.add('show');
                }
                updateButton[i].classList.remove('hide');
            }
            else if (parseInt(availableStock[i].value) === parseInt(quantityBar[i].value)){
                warningMessage[i].classList.add('show');
            }
            cartTotalUpdate();
        });

        minusBtn[i].addEventListener('click', ()=>{
            
            // Stop reducing when it reach 1
            if(parseInt(quantityBar[i].value) !== 1){
                quantityBar[i].value--;
                hrefValue[i] = quantityBar[i].value;
                warningMessage[i].classList.remove('show');
                updateButton[i].classList.remove('hide');
                cartTotalUpdate();
            }
        });

        quantityBar[i].addEventListener('change', ()=>{
            
        })


        // Updating the product Quantity when the update button is pressed
        updateButton[i].addEventListener('click', ()=>{
            updateButton[i].href += hrefValue[i];
        });
    }
    
    document.onload = cartTotalUpdate();
    document.onload = checkCartAvailability();

}
catch (err){
    console.log("Error 404");
}


function cartTotalUpdate(){
    let cartItems = document.getElementsByClassName('cart-item');
    let total = 0;
    for (let index=0; index < cartItems.length; index++){
        let priceElement = document.getElementsByClassName('cart-item-price')[index];
        let quantityElement = document.getElementsByClassName('quantity-input')[index];
        let price = parseFloat(priceElement.innerHTML.replace("RM", "").trim());
        let itemQuantity = quantityElement.value;
        total = total + (price * itemQuantity);
    }
    document.getElementsByClassName('price')[0].innerHTML = "RM " + total.toFixed(2);

}

function checkCartAvailability(){
    let productStock = document.getElementsByClassName('product-stock');
    let cartQuantity = document.getElementsByClassName('quantity-input');
    let warningStockMessage = document.getElementsByClassName('warning-stock');

    for (let i = 0; i < productStock.length; i++) {
        if(parseInt(productStock[i].value) < parseInt(cartQuantity[i].value)){
            warningStockMessage[i].classList.add('show');
        }
    }

}










