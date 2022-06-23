const formDiv = document.getElementById('form');
const inputs = formDiv.getElementsByTagName('input');
const button = formDiv.getElementsByTagName('button')[0];

function isFormFilled() {
    for(let i = 0; i < inputs.length; i++){
        if(inputs[i].value == ''){
            return false;
        }
    }
    return true;
}

let intervalId = window.setInterval(function(){
    if(isFormFilled()) {
        button.classList.add('active');
        button.disabled = false;
    }
    else{
        button.classList.remove('active');
        button.disabled = true;
    }
}, 1000);