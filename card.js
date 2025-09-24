let cNumber = document.getElementById('number');
let eDate = document.getElementById('e-date');
let cvv = document.getElementById('cvv');
let paymentButton = document.querySelector('button[name="submit"]');

cNumber.addEventListener('keyup', function(e){
    let num = cNumber.value.replace(/\s/g, '');
    let newvalue = '';

    for(let i = 0; i < num.length; i++){
        if(i % 4 === 0 && i > 0){
            newvalue = newvalue.concat(' ');
        }
        newvalue = newvalue.concat(num[i]);
        cNumber.value = newvalue;
    }

    // Change border color for the input card number
    let ccNum = document.getElementById('c-number');
    if(newvalue.length < 19){
        ccNum.style.border = "1px solid red";
    }else{
        ccNum.style.border = "1px solid greenyellow";
    }

    checkFormValidity();
});

eDate.addEventListener('keyup', function(e) {
    let newInput = eDate.value;

    if(e.which !== 8){
        let numChars = e.target.value.length;

        if(numChars === 2){
            let thisVal = e.target.value;
            thisVal += '/';
            e.target.value = thisVal;
        }
    }

    validateExpirationDate(newInput);
    checkFormValidity();
});

cvv.addEventListener('keyup', function(e){
    let elen = cvv.value;
    let cvvbox = document.getElementById('cvv-box');

    if(elen.length < 3){
        cvvbox.style.border = "1px solid red";
    }
    else{
        cvvbox.style.border = "1px solid greenyellow";
    }

    checkFormValidity();
});

function validateExpirationDate(input){
    let eDate = document.getElementById('e-date');
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth() + 1; // Months are zero-indexed
    let currentYear = currentDate.getFullYear() % 100; // Get last two digits of the year

    if(input.length < 5){
        eDate.style.border = '1px solid red';
        return;
    }

    let parts = input.split('/');
    if(parts.length < 2){
        eDate.style.border = "1px solid red";
        return;
    }

    let inputMonth = parseInt(parts[0], 10);
    let inputYear = parseInt(parts[1], 10);

    if(inputYear < currentYear || (inputYear === currentYear && inputMonth < currentMonth) || inputMonth > 12){
        eDate.style.border = "1px solid red";
    }else{
        eDate.style.border = "1px solid greenyellow";
    }
}

function checkFormValidity(){
    let ccNum = document.getElementById('c-number').style.borderColor;
    let expDate = document.getElementById('e-date').style.borderColor;
    let cvvBox = document.getElementById('cvv-box').style.borderColor;

    if(ccNum === "red" || expDate === "red" || cvvBox === "red"){
        paymentButton.disabled = true;
        paymentButton.style.backgroundColor = 'red';
    } else{
        paymentButton.disabled = false;
        paymentButton.style.backgroundColor = 'yellowgreen';
    }
}

checkFormValidity();
