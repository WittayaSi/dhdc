$(function(){
    $('#signupButton').click(function(){
        $('#modalSignup').modal('show')
                .find('#signupContent')
                .load($(this).attr('url'));
    });
});
