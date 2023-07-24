// ------------------------ DEBUT AFFECTION PATIENT --------------------------
$(document).ready(function () {

    var $container = $('.list_affection');
    var $addLink = $('.add_affection');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addAffection($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_affections').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAffection($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_affections').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addAffection($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-affection").attr('data-prototype').replace(/__name__label__/g, 'Affection ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation
        addDeleteLink($prototype);

        $(".affection").select2({
            placeholder: "Selectionnez une affection",
            allowClear: true
        });


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une prestation
    function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien
        $prototype.find("td:last-child").append($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function (e) {
            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()
            //$prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

});

// ------------------------ FIN AFFECTION PATIENT ---------------------------

// ------------------------ DEBUT TELEPHONE PATIENT --------------------------
$(document).ready(function () {

    var $container = $('.list_telephone');
    var $addLink = $('.add_telephone');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addTelephone($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_telephones').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAffection($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_telephones').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addTelephone($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-telephone").attr('data-prototype').replace(/__name__label__/g, 'Telephone ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation
        addDeleteLink($prototype);

        /*$(".telep").select2({
            placeholder: "Selectionnez une affection",
            allowClear: true
        });*/


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une prestation
    function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien
        $prototype.find("td:last-child").append($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function (e) {
            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()
            //$prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});

// ------------------------ FIN TELEPHONE PATIENT ---------------------------

// ------------------------ DEBUT ASSURANCE PATIENT --------------------------
$(document).ready(function () {

    var $container = $('.list_assurance');
    var $addLink = $('.add_assurance');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addAssurance($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_assurances').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAffection($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_assurances').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addAssurance($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-assurance").attr('data-prototype').replace(/__name__label__/g, 'Affection ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation
        addDeleteLink($prototype);

        /*$(".telep").select2({
            placeholder: "Selectionnez une affection",
            allowClear: true
        });*/


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une prestation
    function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien
        $prototype.find("td:last-child").append($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function (e) {
            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()
            //$prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});

// ------------------------ FIN ASSURANCE PATIENT ---------------------------

// ------------------------ DEBUT ALLERGIE PATIENT --------------------------
$(document).ready(function () {

    var $container = $('.list_allergie');
    var $addLink = $('.add_allergie');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addAllergie($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_allergies').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAffection($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_allergies').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addAllergie($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-allergie").attr('data-prototype').replace(/__name__label__/g, 'Affection ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation
        addDeleteLink($prototype);

        /*$(".telep").select2({
            placeholder: "Selectionnez une affection",
            allowClear: true
        });*/


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une prestation
    function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien
        $prototype.find("td:last-child").append($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function (e) {
            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()
            //$prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});

// ------------------------ FIN ALLERGIE PATIENT ---------------------------

// ------------------------ DEBUT VACCINATION PATIENT --------------------------
$(document).ready(function () {

    var $container = $('.list_vaccin');
    var $addLink = $('.add_vaccin');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addVaccin($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_vaccinations').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAffection($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_vaccinations').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addVaccin($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($("div#list-vaccination").attr('data-prototype').replace(/__name__label__/g, 'Affection ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation
        addDeleteLink($prototype);

        /*$(".telep").select2({
            placeholder: "Selectionnez une affection",
            allowClear: true
        });*/

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        $('.vaccinpicker').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_3",
            format: "DD-MM-YYYY"
        }, function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });

        $('.rappelpicker').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_3",
            format: "DD-MM-YYYY"
        }, function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une prestation
    function addDeleteLink($prototype) {

        // Création du lien
        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien
        $prototype.find("td:last-child").append($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function (e) {
            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()
            //$prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});

// ------------------------ FIN VACCINATION PATIENT ---------------------------


$(document).ready(function(){
    $(":file").filestyle();
    $(":file").filestyle({buttonText: "Charger fichier"});
    $(":file").filestyle({buttonName: "btn-primary"});
    $(":file").filestyle({iconName: "glyphicon-inbox"});
    $(":file").filestyle({buttonBefore: false});
    $(":file").filestyle({placeholder: ""});

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            console.log(input.files[0]);

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
                console.log(e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#ps_gestionbundle_patient_personne_photo_file").change(function(){
        readURL(this);
    });
});

$(document).ready(function () {
    //alert(123)
    $('div#ps_gestionbundle_patient_affection').parent().remove();
    $('div#ps_gestionbundle_patient_allergie').parent().remove();
    $('div#ps_gestionbundle_patient_assurance').parent().remove();
    $('div#ps_gestionbundle_patient_telephone').parent().remove();
    $('div#ps_gestionbundle_patient_vaccination').parent().remove();

    $('#ps_gestionbundle_patient_personne_datenaissance').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_3",
        format: "DD-MM-YYYY"
    }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

});