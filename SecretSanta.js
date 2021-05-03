$(document).ready(function(){
    $("#addSanta").click(function(){
        var forename = $("#santaForename").val();
        var surname = $("#santaSurname").val();
        var email = $("#santaEmail").val();
        var error = false;
        if(stringIsEmpty(forename)){
            alert("Please enter a valid forename");
            error = true;
        }
        if(stringIsEmpty(surname)){
            alert("Please enter a valid surname");
            error = true;
        }
        if(stringIsEmpty(email)|| !isValidEmail(email)){
            alert("Please enter a valid email");
            error = true;
        }
        if(!error){
            var appendLi = "<li>"+forename+" "+surname+" - "+email+"</li>";
            $("#santaList").append(appendLi);
        }
    });
});

function stringIsEmpty(str){
    if(!str.trim()){
        return true;
    }
    console.log(str+" is not empty");
    return false
}

function isValidEmail(email){
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

