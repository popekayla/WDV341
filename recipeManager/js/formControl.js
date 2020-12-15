function deleteIngRow(inBtnID) {
    let idNum = parseFloat(inBtnID);
    let idToDelete = 'ing' + idNum;
    let rowToDelete = document.getElementById(idToDelete);
    rowToDelete.parentNode.removeChild(rowToDelete);
};

function deleteInsRow(inBtnID) {
    let idNum = parseFloat(inBtnID);
    let idToDelete = 'ins' + idNum;
    let rowToDelete = document.getElementById(idToDelete);
    rowToDelete.parentNode.removeChild(rowToDelete);
};

function formControl() {

    /*Creates a new ingredient input field for both the amount and the description when 'add' button is clicked
      Finds the length of the ul and adds +1 to the end of the name and id to make it unique*/
    document.querySelector("#ingredients").addEventListener("click", function () {
        let ul = document.querySelector("#ingredientList");
        let li = document.createElement("li");
        let ulLength = ul.getElementsByTagName("li").length;
        for (let x = 0; x <= ulLength; x++) {
            let newIngredient = `<label for="ingredients">Amount:</label> <input type="number" name="ingredient_amount_arr[]" id="ingredientAmt${1 + x}" step="any" min="0" class="amt" required>
                                <label for="measurent">Measurement:</label> <input type="text" name="ingredient_measurement_arr[]" id="ingredientMeasurement${1 + x}">
                                 <label for="ingredientName">Ingredient:</label> <input type="text" name="ingredient_name_arr[]" id="ingredientName${1 + x}" class="ingredient" required> <button type="button" id="${1 + x}deleteIng" class="delete" onClick="deleteIngRow(this.id)">x</button>`
            li.innerHTML = newIngredient;
            li.id = `ing${x + 1}`;
            ul.appendChild(li);
        };

        /*Re-runs validation when 'add' button is clicked, to ensure all new inputs get validated*/
        var amounts = document.querySelectorAll(".amt");
        for (amount of amounts) {
            amount.addEventListener("blur", validateAmt);
        };
        function validateAmt() {
            for (i = 0; i < amounts.length; i++) {
                let regEx = /^[0-9]+(\.[0-9]{1,2})?$/

                let amt = amounts[i].id;
                let amtID = document.getElementById(amt);

                if (regEx.test(amtID.value) && amtID.value != undefined) {
                    document.getElementById(amt).classList.add("validationSuccess");
                    document.getElementById(amt).classList.remove("validationFailure");
                    document.getElementById("ingredientValidation").innerHTML = " ";
                } else {
                    document.getElementById(amt).classList.add("validationFailure");
                    document.getElementById(amt).classList.remove("validationSuccess");
                    document.getElementById("ingredientValidation").innerHTML = "Please enter a valid ingredient";
                };
            };
        };

        var ingredients = document.querySelectorAll(".ingredient")
        for (ingredient of ingredients) {
            ingredient.addEventListener("blur", validateIngredient)
        };

        function validateIngredient() {
            for (i = 0; i < ingredients.length; i++) {
                let regEx = /^[\S](.){3,255}$/

                let ing = ingredients[i].id;
                let ingID = document.getElementById(ing);

                if (regEx.test(ingID.value) && ingID.value != undefined) {
                    document.getElementById(ing).classList.add("validationSuccess");
                    document.getElementById(ing).classList.remove("validationFailure");
                    document.getElementById("ingredientValidation").innerHTML = " ";
                } else {
                    document.getElementById(ing).classList.add("validationFailure");
                    document.getElementById(ing).classList.remove("validationSuccess");
                    document.getElementById("ingredientValidation").innerHTML = "Please enter a valid ingredient";
                };
            };
        };


        /*Deletes row when x button is clicked*/
        /* let closeBtns = document.querySelectorAll(".close");
         console.log(closeBtns);
         for (closeBtn of closeBtns) {
             closeBtn.addEventListener("click", deleteInstructionRow);
         }*/


    });


    /*Creates a new instruction input field forwhen 'add' button is clicked
      Finds the length of the ul and adds +1 to the end of the name and id to make it unique*/
    document.querySelector("#instructions").addEventListener("click", function () {
        let ol = document.querySelector("#instructionList");
        let li = document.createElement("li");
        let olLength = ol.getElementsByTagName("li").length;

        for (let i = 1; i <= olLength; i++) {
            var newInstruction = `<input type="text" name="instruction_description_arr[]" id="instruction${1 + i}" class="instruction" required> <button type="button" id="${1 + i}deleteIns" class="delete" onClick="deleteInsRow(this.id)">x</button>`
            li.innerHTML = newInstruction;
            li.id = `ins${i + 1}`;
            ol.appendChild(li);
        };

        /*Re-runs validation when 'add' button is clicked, to ensure all new inputs get validated*/
        var instructions = document.querySelectorAll(".instruction");
        for (instruction of instructions) {
            instruction.addEventListener("blur", validateInst);
        }
        function validateInst() {
            for (i = 0; i < instructions.length; i++) {

                let regEx = /^[\S](.){3,255}$/

                let inst = instructions[i].id;
                let instID = document.getElementById(inst);

                if (regEx.test(instID.value) && instID.value != undefined) {
                    document.getElementById(inst).classList.add("validationSuccess");
                    document.getElementById(inst).classList.remove("validationFailure");
                    document.getElementById("instructionValidation").innerHTML = " ";
                } else {
                    document.getElementById(inst).classList.add("validationFailure");
                    document.getElementById(inst).classList.remove("validationSuccess");
                    document.getElementById("instructionValidation").innerHTML = "Please enter a valid ingredient";
                }
            };
        }


        /*Deletes row when x button is clicked*/
        let closeBtns = document.querySelectorAll(".close");
        console.log(closeBtns);
        for (closeBtn of closeBtns) {
            closeBtn.addEventListener("click", deleteRow);
        }

    });

}

function isValid(e) {

    /*Checks validation
      If inputs are empty or any has a class of 'validationFailure', error message is displayed and submission fails*/
    let inputs = document.querySelectorAll("input");
    var validation = false;
    for (i = 0; i < inputs.length; i++) {

        if (inputs[i].classList.contains("validationFailure")) {
            alert("Please enter valid information");
            event.preventDefault();
            return false;
        } else {
            validation = true;
        }
    };
}