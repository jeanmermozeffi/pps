$(function () {

    $('.row-hide').closest('.form-group').css('marginLeft', '15px');

    //Champ oui/non
    $('[id^="form_field"]').each(function () {

        const $this = $(this);
        if ($this.find('.radio').length == 2) {
            
            $this.find('.radio').removeClass().addClass('radio-inline');
        }
    })
    //Gestion des champs des fiches de spécialité
});