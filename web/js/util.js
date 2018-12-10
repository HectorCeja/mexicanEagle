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
function readURLCC(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#precargaComponenteC').attr('src', e.target.result);
            $('#precargaComponenteC').attr('width', '100');
            $('#precargaComponenteC').attr('height', '150');
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
function readURLII(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#precargaComponenteI').attr('src', e.target.result);
            $('#precargaComponenteI').attr('width', '100');
            $('#precargaComponenteI').attr('height', '150');
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

$("#imgInpCC").change(function(){
    readURLCC(this);
});
$("#imgInpII").change(function(){
    readURLII(this);
});