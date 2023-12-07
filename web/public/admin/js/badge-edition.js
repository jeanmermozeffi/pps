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
        submitSelectedButton.addEventListener('click', function () {
            var selectedCheckboxes = document.querySelectorAll('.checkbox-badge:checked');
            var selectedValues = Array.from(selectedCheckboxes).map(function (checkbox) {
                return checkbox.value; // Utilisez la propriété 'value' de la case à cocher
            });

            // Do something with the selected values
            console.log('Selected values: ', selectedValues);

            //Envoyer les valeurs sélectionnées au serveur en utilisant AJAX
            fetch(Routing.generate('app_badge_edittion_print'), {
              method: 'POST',
              body: JSON.stringify({ selectedValues: selectedValues }),
              headers: {
                'Content-Type': 'application/json'
              }
            }).then(response => response.json())
              .then(data => console.log('Data sent successfully', data))
              .catch(error => console.error('Error:', error));
        });
    }
});
