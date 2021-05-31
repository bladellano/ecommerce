$(function () {

    /* Customiza o input:file do sistema */
    $(":file").filestyle();
    
    /* Preenche dinamicamente o campo desurl com nome do produto */
    $('#desproduct').keyup(function (e) {
        let string = $(this).val();
        $('#desurl').val(string_to_slug(string));
    });

});

/* Function of create slug */
function string_to_slug(str) {
    str = str.replace(/^\s+|\s+$/g, '');
    str = str.toLowerCase();

    var from = "àáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
    var to = "aaaaaeeeeiiiioooouuuunc------";

    for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');

    return str;
}
