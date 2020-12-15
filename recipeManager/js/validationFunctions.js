function validations() {

    /*Checks input values against validation parameters, and adds success or failure classes and message*/

    document.querySelector("#name").addEventListener("blur", function () {

        let regEx = /^[\S](.){3,50}$/
        let name = document.querySelector("#name");

        if (regEx.test(name.value) && name.value != undefined) {
            name.classList.add("validationSuccess");
            name.classList.remove("validationFailure");
            document.getElementById("nameValidation").innerHTML = " ";
        } else {
            name.classList.add("validationFailure");
            name.classList.remove("validationSuccess");
            document.getElementById("nameValidation").innerHTML = "Please enter a valid name";
        }
    });

    document.querySelector("#servings").addEventListener("blur", function () {

        let regEx = /^[0-9]{1,}$/
        let serving = document.querySelector("#servings");

        if (regEx.test(serving.value) && serving.value != undefined && serving.value > 0) {
            serving.classList.add("validationSuccess");
            serving.classList.remove("validationFailure");
            document.getElementById("servingValidation").innerHTML = " ";
        } else {
            serving.classList.add("validationFailure");
            serving.classList.remove("validationSuccess");
            document.getElementById("servingValidation").innerHTML = "Please enter a valid serving";
        }
    });

    document.querySelector("#prepHours").addEventListener("blur", function () {

        let regEx = /^[0-9]{1,}$/
        let prep = document.querySelector("#prepHours");

        if (regEx.test(prep.value) && prep.value != undefined && prep.value >= 0 && prep.value <= 60) {
            prep.classList.add("validationSuccess");
            prep.classList.remove("validationFailure");
            document.getElementById("prepValidation").innerHTML = " ";
        } else {
            prep.classList.add("validationFailure");
            prep.classList.remove("validationSuccess");
            document.getElementById("prepValidation").innerHTML = "Please enter a valid time";
        }
    });

    document.querySelector("#prepMins").addEventListener("blur", function () {

        let regEx = /^[0-9]{1,}$/
        let prep = document.querySelector("#prepMins");

        if (regEx.test(prep.value) && prep.value != undefined && prep.value >= 0 && prep.value <= 60) {
            prep.classList.add("validationSuccess");
            prep.classList.remove("validationFailure");
            document.getElementById("prepValidation").innerHTML = " ";
        } else {
            prep.classList.add("validationFailure");
            prep.classList.remove("validationSuccess");
            document.getElementById("prepValidation").innerHTML = "Please enter a valid time";
        }
    });

    /* document.querySelector("#prepTimeType").addEventListener("blur", function () {
 
         let prepTimeType = document.querySelector("#prepTimeType");
 
         if (prepTimeType.value !== "Please Select") {
             prepTimeType.classList.add("validationSuccess");
             prepTimeType.classList.remove("validationFailure");
             document.getElementById("prepValidation").innerHTML = " ";
         } else {
             prepTimeType.classList.add("validationFailure");
             prepTimeType.classList.remove("validationSuccess");
             document.getElementById("prepValidation").innerHTML = "Please enter a valid time";
         }
     });*/

    document.querySelector("#cookHours").addEventListener("blur", function () {

        let regEx = /^[0-9]{1,}$/
        let cook = document.querySelector("#cookHours");

        if (regEx.test(cook.value) && cook.value != undefined && cook.value >= 0 && cook.value <= 60) {
            cook.classList.add("validationSuccess");
            cook.classList.remove("validationFailure");
            document.getElementById("cookValidation").innerHTML = " ";
        } else {
            cook.classList.add("validationFailure");
            cook.classList.remove("validationSuccess");
            document.getElementById("cookValidation").innerHTML = "Please enter a valid time";
        }
    });

    document.querySelector("#cookMins").addEventListener("blur", function () {

        let regEx = /^[0-9]{1,}$/
        let cook = document.querySelector("#cookMins");

        if (regEx.test(cook.value) && cook.value != undefined && cook.value >= 0 && cook.value <= 60) {
            cook.classList.add("validationSuccess");
            cook.classList.remove("validationFailure");
            document.getElementById("cookValidation").innerHTML = " ";
        } else {
            cook.classList.add("validationFailure");
            cook.classList.remove("validationSuccess");
            document.getElementById("cookValidation").innerHTML = "Please enter a valid time";
        }
    });

    /*document.querySelector("#cookTimeType").addEventListener("blur", function () {

        let cookTimeType = document.querySelector("#cookTimeType");

        if (cookTimeType.value !== "Please Select") {
            cookTimeType.classList.add("validationSuccess");
            cookTimeType.classList.remove("validationFailure");
            document.getElementById("cookValidation").innerHTML = " ";
        } else {
            cookTimeType.classList.add("validationFailure");
            cookTimeType.classList.remove("validationSuccess");
            document.getElementById("cookValidation").innerHTML = "Please enter a valid time";
        }
    });*/

    document.querySelector("#difficulty").addEventListener("blur", function () {

        let difficulty = document.querySelector("#difficulty");

        if (difficulty.value !== "Please Select") {
            difficulty.classList.add("validationSuccess");
            difficulty.classList.remove("validationFailure");
            document.getElementById("difficultyValidation").innerHTML = " ";
        } else {
            difficulty.classList.add("validationFailure");
            difficulty.classList.remove("validationSuccess");
            document.getElementById("difficultyValidation").innerHTML = "Please enter a valid dificulty";
        }
    });

    document.querySelector("#ingredientAmt1").addEventListener("blur", function () {
        let regEx = /^[0-9]+(\.[0-9]{1,2})?$/

        let amt = document.querySelector("#ingredientAmt1");

        if (regEx.test(amt.value) && amt.value != undefined) {
            amt.classList.add("validationSuccess");
            amt.classList.remove("validationFailure");
            document.getElementById("ingredientValidation").innerHTML = " ";
        } else {
            amt.classList.add("validationFailure");
            amt.classList.remove("validationSuccess");
            document.getElementById("ingredientValidation").innerHTML = "Please enter a valid ingredient";
        }
    });

    document.querySelector("#ingredientName1").addEventListener("blur", function () {
        let regEx = /^[\S](.){3,255}$/

        let name = document.querySelector("#ingredientName1");

        if (regEx.test(name.value) && name.value != undefined) {
            name.classList.add("validationSuccess");
            name.classList.remove("validationFailure");
            document.getElementById("ingredientValidation").innerHTML = " ";
        } else {
            name.classList.add("validationFailure");
            name.classList.remove("validationSuccess");
            document.getElementById("ingredientValidation").innerHTML = "Please enter a valid ingredient";
        }
    });

    document.querySelector('#instruction1').addEventListener("blur", function () {
        let regEx = /^[\S](.){3,1023}$/

        let inst = document.querySelector("#instruction1");

        if (regEx.test(inst.value) && inst.value != undefined) {
            inst.classList.add("validationSuccess");
            inst.classList.remove("validationFailure");
            document.getElementById("instructionValidation").innerHTML = " ";
        } else {
            inst.classList.add("validationFailure");
            inst.classList.remove("validationSuccess");
            document.getElementById("instructionValidation").innerHTML = "Please enter a valid ingredient";
        };
    });
};

function submitValidation(e) {
    if (test.value != "") {
        e.preventDefault();
        return false;
    };
};