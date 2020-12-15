

function recipeFunctions() {

    let input = document.getElementById("servings");
    let spans = document.querySelectorAll(".amount");
    let amt = [];
    for (span of spans) {
        amt.push(span.innerHTML);
    }

    input.addEventListener("change", changeServing);

    function changeServing() {
        let currentServing = parseFloat(document.querySelector(".serving").innerHTML);
        let newServing = parseFloat(input.value);
        let multiplier = newServing / currentServing;


        for (i = 0; i < amt.length; i++) {
            let newAmt = amt[i] * multiplier
            spans[i].innerHTML = newAmt.toFixed(1);
        }
    }

};


