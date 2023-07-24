function addSelect2($prototype, placeholder) {
        $prototype.find("select").select2({
            placeholder: placeholder,
            allowClear: true
        });
    }
 $(document).ready(function () {

    var $container = $('.list_signe_fonctionnel');
    var $addLink = $('.add_signe_fonctionnel');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addAnalyse($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_signe_fonctionnel').length;



    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_signe_fonctionnel').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Analyse
    function addAnalyse($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-signe-fonctionnel").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'analyse
        addDeleteLink($prototype);

        

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

       
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une analyse
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

 $(document).ready(function () {

    var $container = $('.list_signe_physique');
    var $addLink = $('.add_signe_physique');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addAnalyse($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_signe_physique').length;



    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_signe_physique').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Analyse
    function addAnalyse($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-signe-physique").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'analyse
        addDeleteLink($prototype);

        

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

       
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une analyse
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
// ------------------------ DEBUT ASSURANCE CONSULTATION ---------------------------

$(document).ready(function() {

    var $container = $('.list_assurance');
    var $addLink = $('.add_assurance');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        addAssurance($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_assurance').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addAssurance($container);
    } else {
        //if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_assurance').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addAssurance($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-assurance").attr('data-prototype').replace(/__name__label__/g, 'Assurance ' + (index+1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'assurance
        addDeleteLink($prototype);


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une assurance
    function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien
        $prototype.find("td:last-child").append($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function(e) {
            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()
            //$prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});

// ------------------------ FIN ASSURANCE CONSULTATION ---------------------------

// ------------------------ DEBUT VACCINATION CONSULTATION ---------------------------

$(document).ready(function() {

    var $container = $('.list_vaccination');
    var $addLink = $('.add_vaccination');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        addVaccination($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_vaccination').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addVaccination($container);
    } else {
        //if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_vaccination').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addVaccination($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-vaccination").attr('data-prototype').replace(/__name__label__/g, 'Vaccination ' + (index+1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'vaccination
        addDeleteLink($prototype);


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une vaccination
    function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien
        $prototype.find("td:last-child").append($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function(e) {
            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()
            //$prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});

// ------------------------ FIN VACCINATION CONSULTATION ---------------------------

// ------------------------ DEBUT ALLERGIE CONSULTATION ---------------------------

$(document).ready(function() {

    var $container = $('.list_allergie');
    var $addLink = $('.add_allergie');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        addAllergie($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_allergie').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addAllergie($container);
    } else {
        //if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_allergie').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addAllergie($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-allergie").attr('data-prototype').replace(/__name__label__/g, 'Allergie ' + (index+1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'allergie
        addDeleteLink($prototype);


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une allergie
    function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien
        $prototype.find("td:last-child").append($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function(e) {
            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()
            //$prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});

// ------------------------ FIN ALLERGIE CONSULTATION ---------------------------


// ------------------------ DEBUT TELEPHONE CONSULTATION --------------------------
$(document).ready(function() {

    var $container = $('.list_telephone');
    var $addLink = $('.add_telephone');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        addTelephone($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_telephone').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addTelephone($container);
    } else {
        //if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_telephone').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addTelephone($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-telephone").attr('data-prototype').replace(/__name__label__/g, 'Médicament ' + (index+1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation
        addDeleteLink($prototype);


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
        $deleteLink.click(function(e) {
            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()
            //$prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});

// ------------------------ FIN TELEPHONE CONSULTATION ---------------------------


// ------------------------ DEBUT AFFECTION CONSULTATION --------------------------
$(document).ready(function() {

    var $container = $('.list_affection');
    var $addLink = $('.add_affection');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {alert()
        addAffection($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_affection').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addAffection($container);
    } else {
        //if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_affection').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Categorie
    function addAffection($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-affection").attr('data-prototype').replace(/__name__label__/g, 'Affection ' + (index+1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation
        addDeleteLink($prototype);


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
        $deleteLink.click(function(e) {
            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()
            //$prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});

// ------------------------ FIN AFFECTION CONSULTATION ---------------------------

// ------------------------ DEBUT COMBO MEDECIN, CLIENT, SPECIALITE ET AFFECTION ---------------------------

$(document).ready(function() {
    //$('input').disabled()
    $('div#ps_gestionbundle_patient_affection').parent().remove();
    $('div#ps_gestionbundle_patient_allergie').parent().remove();
    $('div#ps_gestionbundle_patient_telephone').parent().remove();
    $('div#ps_gestionbundle_patient_assurance').parent().remove();
    $('div#ps_gestionbundle_patient_vaccination').parent().remove();

    /*$('#edit_form').click(function(){
        $('input').enabled()
    })*/

    $("#ps_gestionbundle_patient_sexe").select2({
        placeholder: "Selectionnez un sexe",
        allowClear: true
    });

    $("#ps_gestionbundle_patient_groupeSanguin").select2({
        placeholder: "Selectionnez un groupe",
        allowClear: true
    });

    $("#ps_gestionbundle_patient_ville").select2({
        placeholder: "Selectionnez une ville",
        allowClear: true
    });

});