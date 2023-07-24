// ------------------------ DEBUT ANALYSE CONSULTATION ---------------------------
function addSelect2($prototype, placeholder, tag) {
    var options = {
         placeholder: placeholder,
            allowClear: true,
        
    };


    if (tag) {
        options = $.extend(options, {
            tags: true,
            createTag: function (params) {
            var term = $.trim(params.term);

            if (term === '') {
              return null;
            }

            return {
              id: term,
              text: term,
              newTag: true // add additional parameters
            }
          }
        });
    }

    if (!$prototype.is('select')) {
        $prototype.find("select").select2(options);
    } else {
        $prototype.select2(options);
    }

    
   }

    $(document).ready(function () {

    var $container = $('.list_medicament');
    var $addLink = $('.add_medicament');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addExamen($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_medicament').length;



    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_3",
        format: "DD/MM/YYYY"
    }, function (start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addExamen($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_medicament').each(function () {
            const $this = $(this);
            addDeleteLink($this);
            addSelect2($this, 'Choisir un medicament');
        });
    }

    // La fonction qui ajoute un formulaire Examen
    function addExamen($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-medicament").attr('data-prototype').replace(/__name__label__/g, 'Examen ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'medicament
        addDeleteLink($prototype);

       


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

       addSelect2($prototype, 'Choisir un medicament');
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une medicament
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
$(document).ready(function () {

    var $container = $('.list_analyse');
    var $addLink = $('.add_analyse');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addAnalyse($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_analyses').length;



    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_3",
        format: "DD/MM/YYYY"
    }, function (start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_analyses').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Analyse
    function addAnalyse($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-analyse").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'analyse
        addDeleteLink($prototype);

        $(".analyse").select2({
            placeholder: "Selectionnez une analyse",
            allowClear: true
        });


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

// ------------------------ FIN ANALYSE CONSULTATION ---------------------------
// 
// --------- DEBUT EXAMEN -- //
$(document).ready(function () {

    var $container = $('.list_examen');
    var $addLink = $('.add_examen');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addExamen($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_examen').length;



    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_3",
        format: "DD/MM/YYYY"
    }, function (start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addExamen($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_examen').each(function () {
            const $this = $(this);
            addDeleteLink($this);
            addSelect2($this, 'Choisir un examen');
        });
    }

    // La fonction qui ajoute un formulaire Examen
    function addExamen($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-examen").attr('data-prototype').replace(/__name__label__/g, 'Examen ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'examen
        addDeleteLink($prototype);

       


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

       addSelect2($prototype, 'Choisir un examen');
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une examen
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


//DEBUT HOSPI
$(document).ready(function () {

    var $container = $('.list_hospitalisation');
    var $addLink = $('.add_hospitalisation');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addHospitalisation($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('tr.prototype_hospitalisation').length;



    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_3",
        format: "DD/MM/YYYY"
    }, function (start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addExamen($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_hospitalisation').each(function () {
            const $this = $(this);
            addDeleteLink($this);
            addSelect2($this, 'Choisir le type d\'hospitalisation');
        });
    }

    // La fonction qui ajoute un formulaire Examen
    function addHospitalisation($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-hospitalisation").attr('data-prototype').replace(/__name__label__/g, 'Examen ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'hospitalisation
        addDeleteLink($prototype);



        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        addSelect2($prototype, 'Choisir le type d\'hospitalisation');
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une hospitalisation
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




// ------------------------ FIN MEDICAMENT CONSULTATION ---------------------------


// ------------------------ DEBUT AFFECTION CONSULTATION --------------------------
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
    var index = $container.find('tr.prototype_affection').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAffection($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_affection').each(function () {
            addDeleteLink($(this));
            addSelect2($(this));
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


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        addSelect2($prototype);
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

// ------------------------ FIN AFFECTION CONSULTATION ---------------------------
// 
//------------------------ FIN ANALYSE CONSULTATION ---------------------------

// --------- DEBUT VACCIN -- //
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
    var index = $container.find('tr.prototype_vaccin').length;



    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addExamen($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('tr.prototype_vaccin').each(function () {
            const $this = $(this);
            addDeleteLink($this);
            addSelect2($this, 'Choisir un vaccin');
        });
    }

    // La fonction qui ajoute un formulaire Examen
    function addVaccin($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-vaccin").attr('data-prototype').replace(/__name__label__/g, 'Examen ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'vaccin
        addDeleteLink($prototype);

       


        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

       addSelect2($prototype, 'Choisir un vaccin');
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une vaccin
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


// ------------------------ DEBUT COMBO MEDECIN, CLIENT, SPECIALITE ET AFFECTION ---------------------------

$(document).ready(function () {

    $('div#ps_gestionbundle_consultation_affections').parent().remove();
    //$('div#ps_gestionbundle_consultation_analyses').parent().remove();
    $('div#ps_gestionbundle_consultation_medicaments').parent().remove();

    $("#ps_gestionbundle_consultation_specialite").select2({
        placeholder: "Selectionnez une spécialité",
        allowClear: true
    });

    $('.grid_body').find('table').addClass('table table-bordered table-striped');

    /*$("#ps_gestionbundle_consultation_patient").select2({
     placeholder: "Selectionnez un patient",
     allowClear: true
     });*/

    /*$("#ps_gestionbundle_consultation_affection").select2({
     placeholder: "Selectionnez une affection",
     allowClear: true
     });*/

});


$(document).ready(function () {

    var $container = $('.list_antecedent_personnel');
    var $addLink = $('.add_antecedent_personnel');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addAnalyse($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_antecedent_personnel').length;



    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_antecedent_personnel').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Analyse
    function addAnalyse($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-antecedent-personnel").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'analyse
        addDeleteLink($prototype);

        

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);


        addSelect2($prototype.find('.select2'), 'Choisir', true);



       
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

    var $container = $('.list_antecedent_familial');
    var $addLink = $('.add_antecedent_familial');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function (e) {
        addAnalyse($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_antecedent_familial').length;



    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_antecedent_familial').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Analyse
    function addAnalyse($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-antecedent-familial").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1))
            .replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer l'analyse
        addDeleteLink($prototype);

        

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);


         addSelect2($prototype.find('.select2'), 'Choisir', true);

       
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