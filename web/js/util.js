function readURLC(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#precargaPrendaC').attr('src', e.target.result);
            $('#precargaPrendaC').attr('width', '100');
            $('#precargaPrendaC').attr('height', '150');
        }

        reader.readAsDataURL(input.files[0]);
    }
}
function readURLI(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#precargaPrendaI').attr('src', e.target.result);
            $('#precargaPrendaI').attr('width', '100');
            $('#precargaPrendaI').attr('height', '150');
        }

        reader.readAsDataURL(input.files[0]);
    }
}
$("#imgInpC").change(function(){
    readURLC(this);
});
$("#imgInpI").change(function(){
    readURLI(this);
});