// ------------------------ DEBUT ANALYSE CONSULTATION ---------------------------
function addSelect2($prototype, placeholder) {
    $prototype.find("select").select2({
        placeholder: placeholder,
        allowClear: true
    });
}


$(document).ready(function() {
    var $container = $('.list_traitement');
    var $addLink = $('.add_traitement');
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        
        addLine($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_traitement').length;
   
    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_traitement').each(function() {
            addDeleteLink($(this));
        });
    }
    // La fonction qui ajoute un formulaire Analyse
    function addLine($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-traitement").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1)).replace(/__name__/g, index));
        // On ajoute au prototype un lien pour pouvoir supprimer l'traitement
        addDeleteLink($prototype);


         $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }
    // La fonction qui ajoute un lien de suppression d'une traitement
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


$(document).ready(function() {
    var $container = $('.list_constante');
    var $addLink = $('.add_constante');
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        console.log('xxxxx');
        addLine($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_constante').length;
   
    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_constante').each(function() {
            addDeleteLink($(this));
        });
    }
    // La fonction qui ajoute un formulaire Analyse
    function addLine($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-constante").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1)).replace(/__name__/g, index));
        // On ajoute au prototype un lien pour pouvoir supprimer l'constante
        addDeleteLink($prototype);


         $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }
    // La fonction qui ajoute un lien de suppression d'une constante
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




$(document).ready(function() {
    var $container = $('.list_complication');
    var $addLink = $('.add_complication');
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        console.log('xxxxx');
        addLine($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_complication').length;
   
    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_complication').each(function() {
            addDeleteLink($(this));
        });
    }
    // La fonction qui ajoute un formulaire Analyse
    function addLine($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-complication").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1)).replace(/__name__/g, index));
        // On ajoute au prototype un lien pour pouvoir supprimer l'complication
        addDeleteLink($prototype);


         $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }
    // La fonction qui ajoute un lien de suppression d'une complication
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



$(document).ready(function() {
    var $container = $('.list_gc');
    var $addLink = $('.add_gc');
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        console.log('xxxxx');
        addLine($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_gc').length;
   
    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_gc').each(function() {
            addDeleteLink($(this));
        });
    }
    // La fonction qui ajoute un formulaire Analyse
    function addLine($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-gc").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1)).replace(/__name__/g, index));
        // On ajoute au prototype un lien pour pouvoir supprimer l'gc
        addDeleteLink($prototype);


         $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }
    // La fonction qui ajoute un lien de suppression d'une gc
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


$(document).ready(function() {
    var $container = $('.list_glycemie');
    var $addLink = $('.add_glycemie');
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        console.log('xxxxx');
        addLine($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_glycemie').length;
   
    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_glycemie').each(function() {
            addDeleteLink($(this));
        });
    }
    // La fonction qui ajoute un formulaire Analyse
    function addLine($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-glycemie").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1)).replace(/__name__/g, index));
        // On ajoute au prototype un lien pour pouvoir supprimer l'glycemie
        addDeleteLink($prototype);


         $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }
    // La fonction qui ajoute un lien de suppression d'une glycemie
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




$(document).ready(function() {
    var $container = $('.list_antecedent');
    var $addLink = $('.add_antecedent');
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        console.log('xxxxx');
        addLine($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_antecedent').length;
   
    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_antecedent').each(function() {
            addDeleteLink($(this));
        });
    }
    // La fonction qui ajoute un formulaire Analyse
    function addLine($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-antecedent").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1)).replace(/__name__/g, index));
        // On ajoute au prototype un lien pour pouvoir supprimer l'antecedent
        addDeleteLink($prototype);


         $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }
    // La fonction qui ajoute un lien de suppression d'une antecedent
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



$(document).ready(function() {
    var $container = $('.list_gc');
    var $addLink = $('.add_gc');
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        console.log('xxxxx');
        addLine($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('.prototype_gc').length;
   
    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    /*if (index == 0) {
     addAnalyse($container);
     } else {*/
    if (index > 0) {
        // Pour chaque echantillon déjà existante, on ajoute un lien de suppression
        $container.children('.prototype_gc').each(function() {
            addDeleteLink($(this));
        });
    }
    // La fonction qui ajoute un formulaire Analyse
    function addLine($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($("div#list-gc").attr('data-prototype').replace(/__name__label__/g, 'Analyse ' + (index + 1)).replace(/__name__/g, index));
        // On ajoute au prototype un lien pour pouvoir supprimer l'gc
        addDeleteLink($prototype);


         $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }
    // La fonction qui ajoute un lien de suppression d'une gc
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