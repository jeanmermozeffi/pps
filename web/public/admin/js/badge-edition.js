document.addEventListener('DOMContentLoaded', function () {
    // Select all checkboxes when the "checkbox-all" is clicked
    var checkboxAll = document.getElementById('checkbox-all');
    if (checkboxAll) {
        checkboxAll.addEventListener('change', function () {
            var checkboxes = document.querySelectorAll('.checkbox-badge');
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = checkboxAll.checked;
            });
        });
    }

    // Deselect "checkbox-all" if any individual checkbox is unchecked
    var checkboxBadges = document.querySelectorAll('.checkbox-badge');
    checkboxBadges.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            if (!checkbox.checked) {
                if (checkboxAll) {
                    checkboxAll.checked = false;
                }
            }
        });
    });

    // Submit selected checkboxes when the "submit-selected" button is clicked
    var submitSelectedButton = document.getElementById('submit-selected');
    if (submitSelectedButton) {
        ajaxSubmitted(submitSelectedButton);
        // submitSelectedButton.addEventListener('click', function () {
        //     var selectedCheckboxes = document.querySelectorAll('.checkbox-badge:checked');
        //     var selectedValues = Array.from(selectedCheckboxes).map(function (checkbox) {
        //         return checkbox.value; // Utilisez la propriété 'value' de la case à cocher
        //     });

        //     // Do something with the selected values
        //     console.log('Selected values: ', selectedValues);

        //     //Envoyer les valeurs sélectionnées au serveur en utilisant AJAX
        //     fetch(Routing.generate('app_badge_edittion_print'), {
        //         method: 'POST',
        //         body: JSON.stringify({ selectedValues: selectedValues }),
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-Requested-With': 'XMLHttpRequest',
        //         },
        //     })
        //         .then(response => response.json())
        //         .then(data => {
        //             console.log(data);
        //             console.log(data.statut);
        //             if (data.statut) {
        //                 // Succès : redirigez l'utilisateur
        //                 window.location.href = data.redirect;
        //             } else {
        //                 // Échec : affichez une alerte
        //                 alert('Erreur : ' + data.message);
        //                 // Désélectionnez les cases à cocher
        //                 $(".checkbox-badge").prop("checked", false);
        //             }
        //         })
        //         .catch(error => {
        //             // Gestion des erreurs
        //             console.error('Erreur lors de la requête AJAX :', error);
        //         });
        // });
    }

    function ajaxSubmitted(submitSelectedButton) {
        var submitSelectedButton = $('#submit-selected');

        if (submitSelectedButton.length) {
            submitSelectedButton.on('click', function () {
                var selectedCheckboxes = $('.checkbox-badge:checked');
                var selectedValues = selectedCheckboxes.map(function () {
                    return $(this).val();
                }).get();

                // Do something with the selected values
                console.log('Selected values: ', selectedValues);

                // Envoyer les valeurs sélectionnées au serveur en utilisant AJAX avec jQuery
                $.ajax({
                    url: Routing.generate('app_badge_edittion_print'),
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: JSON.stringify({ selectedValues: selectedValues }),
                    success: function (data) {
                        console.log(data);
                        console.log(data.message);
                        if (data.statut) {
                            // Succès : redirigez l'utilisateur
                            window.location.href = data.redirect;
                        } else {
                            // Échec : affichez une alerte
                            alert('Erreur : ' + data.message);
                            // Désélectionnez les cases à cocher
                            $(".checkbox-badge").prop("checked", false);
                        }
                    },
                    error: function (error) {
                        // Gestion des erreurs
                        console.error('Erreur lors de la requête AJAX :', error);
                    }
                });
            });
        }
    }
});

