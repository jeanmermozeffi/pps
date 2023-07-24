

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



// ------------------------ DEBUT AFFECTION PATIENT --------------------------



function addDeleteLink($prototype) {

        // Création du lien

   $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien

   $prototype.find(".del-col").append($deleteLink);

        // Ajout du listener sur le clic du lien

   $deleteLink.click(function(e) {

       $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()

            //$prototype.remove();

       e.preventDefault(); // évite qu'un # apparaisse dans l'URL

        return false;

   });

}





$(document).ready(function() {

    var $container = $('.list_associe');

    var $addLink = $('.add_associe');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.

    $addLink.click(function(e) {

        addRow($container);

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL

        return false;

    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement

    var index = $container.find('tr.prototype_associes').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).

    /*if (index == 0) {

     addAffection($container);

     } else {*/

    if (index > 0) {

        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression

        $container.children('tr.prototype_associes').each(function() {

            $(this).find("select").select2({

                placeholder: "Selectionnez un lien",

                

            });

            addDeleteLink($(this));

        });

    }

    // La fonction qui ajoute un formulaire Categorie

    function addRow($container) {

        // Dans le contenu de l'attribut « data-prototype », on remplace :

        // - le texte "__name__label__" qu'il contient par le label du champ

        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($("#list-associe").attr('data-prototype').replace(/__name__label__/g, 'Associé ' + (index + 1)).replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation

        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>

        $container.append($prototype);

        $prototype.find("select").select2({

            placeholder: "-----------------",

           

        });

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro

        index++;

    }

    // La fonction qui

});

$(document).ready(function() {

    var $container = $('.list_affection');

    var $addLink = $('.add_affection');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.

    $addLink.click(function(e) {

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

        $container.children('tr.prototype_affections').each(function() {

            /*$(this).find("select").select2({

                placeholder: "Selectionnez une affection",

                allowClear: true,

                tags: true

            });*/

            init_date_picker($(this).find('.datepicker'));

            addDeleteLink($(this));

        });

    }

    // La fonction qui ajoute un formulaire Categorie

    function addAffection($container) {

        // Dans le contenu de l'attribut « data-prototype », on remplace :

        // - le texte "__name__label__" qu'il contient par le label du champ

        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($("#list-affection").attr('data-prototype').replace(/__name__label__/g, 'Affection ' + (index + 1)).replace(/__name__/g, index));

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

        $prototype.find(".del-col").append($deleteLink);

        // Ajout du listener sur le clic du lien

        $deleteLink.click(function(e) {

            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()

            //$prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL

            return false;

        });

    }

});

// ------------------------ FIN AFFECTION PATIENT ---------------------------

$(document).ready(function() {

    var $container = $('.list_medecin');

    var $addLink = $('.add_medecin');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.

    $addLink.click(function(e) {

        addMedecin($container);

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL

        return false;

    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement

    var index = $container.find('tr.prototype_medecins').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).

    /*if (index == 0) {

     addAffection($container);

     } else {*/

    if (index > 0) {

        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression

        $container.children('tr.prototype_medecins').each(function() {

            addDeleteLink($(this));

            addSelect2($(this).find('select'), 'Choisir', false);

        });

    }

    // La fonction qui ajoute un formulaire Categorie

    function addMedecin($container) {

        // Dans le contenu de l'attribut « data-prototype », on remplace :

        // - le texte "__name__label__" qu'il contient par le label du champ

        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($("#list-medecin").attr('data-prototype').replace(/__name__label__/g, 'Medecin ' + (index + 1)).replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation

        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>

        $container.append($prototype);

        addSelect2($prototype.find('select'), 'Choisir', false);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro

        index++;

    }

    // La fonction qui ajoute un lien de suppression d'une prestation

    function addDeleteLink($prototype) {

        // Création du lien

        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien

        $prototype.find(".del-col").append($deleteLink);

        // Ajout du listener sur le clic du lien

        $deleteLink.click(function(e) {

            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()

            //$prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL

            return false;

        });

    }

});



//TRaitements

$(document).ready(function() {

    var $container = $('.list_traitement');

    var $addLink = $('.add_traitement');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.

    $addLink.click(function(e) {

        addtraitement($container);

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL

        return false;

    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement

    var index = $container.find('tr.prototype_traitements').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).

    /*if (index == 0) {

     addAffection($container);

     } else {*/

    if (index > 0) {

        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression

        $container.children('tr.prototype_traitements').each(function() {

            addDeleteLink($(this));

            init_date_picker($(this).find('.datepicker'));

        });

    }

    // La fonction qui ajoute un formulaire Categorie

    function addtraitement($container) {

        // Dans le contenu de l'attribut « data-prototype », on remplace :

        // - le texte "__name__label__" qu'il contient par le label du champ

        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($("#list-traitement").attr('data-prototype').replace(/__name__label__/g, 'Traitement ' + (index + 1)).replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation

        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>

        $container.append($prototype);



        init_date_picker($prototype.find('.datepicker'));

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro

        index++;

    }

    // La fonction qui ajoute un lien de suppression d'une prestation

    function addDeleteLink($prototype) {

        // Création du lien

        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien

        $prototype.find(".del-col").append($deleteLink);

        // Ajout du listener sur le clic du lien

        $deleteLink.click(function(e) {

            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()

            //$prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL

            return false;

        });

    }

});

// ------------------------ DEBUT TELEPHONE PATIENT --------------------------

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

    var index = $container.find('tr.prototype_telephones').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).

    /*if (index == 0) {

     addAffection($container);

     } else {*/

    if (index > 0) {

        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression

        $container.children('tr.prototype_telephones').each(function() {

            addDeleteLink($(this));

            addSelect2($(this).find('select'), 'Choisir', false);

        });

    }

    // La fonction qui ajoute un formulaire Categorie

    function addTelephone($container) {

        // Dans le contenu de l'attribut « data-prototype », on remplace :

        // - le texte "__name__label__" qu'il contient par le label du champ

        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($("#list-telephone").attr('data-prototype').replace(/__name__label__/g, 'Telephone ' + (index + 1)).replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation

        addDeleteLink($prototype);

        /*$(".telep").select2({

            placeholder: "Selectionnez une affection",

            allowClear: true

        });*/

        // On ajoute le prototype modifié à la fin de la balise <div>

        $container.append($prototype);





        $prototype.find("select").select2({

            placeholder: "Selectionnez le lien de parenté",

            

        });

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro

        index++;

    }

    // La fonction qui ajoute un lien de suppression d'une prestation

    function addDeleteLink($prototype) {

        // Création du lien

        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien

        $prototype.find(".del-col").append($deleteLink);

        // Ajout du listener sur le clic du lien

        $deleteLink.click(function(e) {

            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()

            //$prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL

            return false;

        });

    }

});

// ------------------------ FIN TELEPHONE PATIENT ---------------------------

// ------------------------ DEBUT ASSURANCE PATIENT --------------------------

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

    var index = $container.find('tr.prototype_assurances').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).

    /*if (index == 0) {

     addAffection($container);

     } else {*/

    if (index > 0) {

        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression

        $container.children('tr.prototype_assurances').each(function() {

            $(this).find("select").select2({

                placeholder: "Selectionnez une assurance",

                allowClear: true,

                tags: true

            });

            addDeleteLink($(this));

        });

    }

    // La fonction qui ajoute un formulaire Categorie

    function addAssurance($container) {

        // Dans le contenu de l'attribut « data-prototype », on remplace :

        // - le texte "__name__label__" qu'il contient par le label du champ

        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($("#list-assurance").attr('data-prototype').replace(/__name__label__/g, 'Affection ' + (index + 1)).replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation

        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>

        $container.append($prototype);

        $prototype.find("select").select2({

            placeholder: "Selectionnez une assurance",

            allowClear: true,

            tags: true

        });

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro

        index++;

    }

    // La fonction qui ajoute un lien de suppression d'une prestation

    function addDeleteLink($prototype) {

        // Création du lien

        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien

        $prototype.find(".del-col").append($deleteLink);

        // Ajout du listener sur le clic du lien

        $deleteLink.click(function(e) {

            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()

            //$prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL

            return false;

        });

    }

});

// ------------------------ FIN ASSURANCE PATIENT ---------------------------

// ------------------------ DEBUT ALLERGIE PATIENT --------------------------

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

    var index = $container.find('tr.prototype_allergies').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).

    /*if (index == 0) {

     addAffection($container);

     } else {*/

    if (index > 0) {

        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression

        $container.children('tr.prototype_allergies').each(function() {

            /*$(this).find("select").select2({

                placeholder: "Selectionnez une allergie",

                allowClear: true,

                tags: true

            });*/

            addDeleteLink($(this));

        });

    }

    // La fonction qui ajoute un formulaire Categorie

    function addAllergie($container) {

        // Dans le contenu de l'attribut « data-prototype », on remplace :

        // - le texte "__name__label__" qu'il contient par le label du champ

        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($("#list-allergie").attr('data-prototype').replace(/__name__label__/g, 'Affection ' + (index + 1)).replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation

        addDeleteLink($prototype);

        /*$(".telep").select2({

            placeholder: "Selectionnez une affection",

            allowClear: true

        });*/

        // On ajoute le prototype modifié à la fin de la balise <div>

        $container.append($prototype);

        /*$prototype.find("select").select2({

            placeholder: "Selectionnez une allergie",

            allowClear: true,

            tags: true

        });*/

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro

        index++;

    }

    // La fonction qui ajoute un lien de suppression d'une prestation

    function addDeleteLink($prototype) {

        // Création du lien

        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien

        $prototype.find(".del-col").append($deleteLink);

        // Ajout du listener sur le clic du lien

        $deleteLink.click(function(e) {

            $(this).parent("td:eq(0)").parent("tr:eq(0)").remove()

            //$prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL

            return false;

        });

    }

});

// ------------------------ FIN ALLERGIE PATIENT ---------------------------

// ------------------------ DEBUT VACCINATION PATIENT --------------------------

$(document).ready(function() {

    var $container = $('.list_vaccin');

    var $addLink = $('.add_vaccin');

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.

    $addLink.click(function(e) {

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

        $container.children('tr.prototype_vaccinations').each(function() {

            /*$(this).find("select").select2({

                placeholder: "Selectionnez un vaccin",

                allowClear: true,

                tags: true

            });*/

            addDeleteLink($(this));

            init_date_picker($(this).find('.datepicker'));

        });

    }

    // La fonction qui ajoute un formulaire Categorie

    function addVaccin($container) {

        // Dans le contenu de l'attribut « data-prototype », on remplace :

        // - le texte "__name__label__" qu'il contient par le label du champ

        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($("#list-vaccination").attr('data-prototype').replace(/__name__label__/g, 'Affection ' + (index + 1)).replace(/__name__/g, index));

        // On ajoute au prototype un lien pour pouvoir supprimer la prestation

        addDeleteLink($prototype);

        /*$(".telep").select2({

            placeholder: "Selectionnez une affection",

            allowClear: true

        });*/

        // On ajoute le prototype modifié à la fin de la balise <div>

        $container.append($prototype);

        

        

        /*$prototype.find("select").select2({

            placeholder: "Selectionnez un vaccin",

            allowClear: true,

            tags: true

        });*/





        init_date_picker($prototype.find('.datepicker'));

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro

        index++;

    }

    // La fonction qui ajoute un lien de suppression d'une prestation

    function addDeleteLink($prototype) {

        // Création du lien

        $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-remove"></i></a>');

        // Ajout du lien

        $prototype.find(".del-col").append($deleteLink);

        // Ajout du listener sur le clic du lien

        $deleteLink.click(function(e) {

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

             addSelect2($(this).find('.select2'), 'Choisir', true);

        });

    }



    // La fonction qui ajoute un formulaire Analyse

    function addAnalyse($container) {

        // Dans le contenu de l'attribut « data-prototype », on remplace :

        // - le texte "__name__label__" qu'il contient par le label du champ

        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($("#list-antecedent-familial").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1))

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

        $prototype.find(".del-col").append($deleteLink);



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

$(document).ready(function() {

    $(":file").filestyle();

    $(":file").filestyle({

        buttonText: "Charger fichier"

    });

    $(":file").filestyle({

        buttonName: "btn-primary"

    });

    $(":file").filestyle({

        iconName: "glyphicon-inbox"

    });

    $(":file").filestyle({

        buttonBefore: false

    });

    $(":file").filestyle({

        placeholder: ""

    });



    function readURL(input) {

        if (input.files && input.files[0]) {

            var reader = new FileReader();

            console.log(input.files[0]);

            reader.onload = function(e) {

                $('#blah').attr('src', e.target.result);

                console.log(e.target.result);

            }

            reader.readAsDataURL(input.files[0]);

        }

    }

    $("#ps_gestionbundle_patient_personne_photo_file").change(function() {

        readURL(this);

    });

});

$(document).ready(function() {

    $('#ps_gestionbundle_patient_affections').parent().remove();

    $('#ps_gestionbundle_patient_allergies').parent().remove();

    $('#ps_gestionbundle_patient_assurances').parent().remove();

    $('#ps_gestionbundle_patient_telephones').parent().remove();

    $('#ps_gestionbundle_patient_vaccinations').parent().remove();

    /*$('#ps_gestionbundle_patient_ville').select2({

        placeholder: "Selectionnez votre ville",

        allowClear: true,

        tags: true

    });*/

    
   



    $('.timepicker').daterangepicker({

        singleDatePicker: true,

        calender_style: "picker_3",

        format: "DD/MM/YYYY"

    }, function(start, end, label) {

        

    });

});