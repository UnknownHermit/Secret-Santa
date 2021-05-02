$(document).ready(function(){
    $("#addSanta").click(function(){
        var forename = $("#santaForename").val();
        var surname = $("#santaSurname").val();
        var email = $("#santaEmail").val();
        var appendLi = "<li>"+forename+" "+surname+" - "+email+"</li>";
        $("#santaList").append(appendLi);
    });
});

