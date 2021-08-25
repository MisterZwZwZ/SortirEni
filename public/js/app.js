$(document).on('change', '#sortie_villes', function (){
    let $field = $(this)
    let $form = $field.closest('form')
    let data = {}
    data [$field.attr('name')] = $field.val()
    $.post($form.attr('action'), data).then(function (data){
        let $inputLieu = $(data).find('#sortie_lieu')
        $('#sortie_lieu').replaceWith($inputLieu)
    })
})