function contactValidations() {
    document.querySelector("#contact_email").addEventListener("blur", function () {

        let regEx = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
        let email = document.querySelector("#contact_email");

        if (regEx.test(email.value) && email.value != undefined) {
            email.classList.add("validationSuccess");
            email.classList.remove("validationFailure");
            document.querySelector(".emailValidation").innerHTML = " ";
        } else {
            email.classList.add("validationFailure");
            email.classList.remove("validationSuccess");
            document.querySelector(".emailValidation").innerHTML = "Please enter a valid email";
        }
    });

    document.querySelector("#contact_phone").addEventListener("blur", function () {

        let regEx = /^\d{10}$/
        let phone = document.querySelector("#contact_phone");

        if (regEx.test(phone.value) && email.value != undefined) {
            phone.classList.add("validationSuccess");
            phone.classList.remove("validationFailure");
            document.querySelector(".phoneValidation").innerHTML = " ";
        } else {
            phone.classList.add("validationFailure");
            phone.classList.remove("validationSuccess");
            document.querySelector(".phoneValidation").innerHTML = "Please enter a valid phone number";
        }
    });
}

function submitValidation(e) {
    if (test.value != "") {
        e.preventDefault();
        return false;
    };
};