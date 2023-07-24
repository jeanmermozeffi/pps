;
(function($) {
    const passwordValidatorTpl = `
        <ul class="validator-rules small list-unstyled">
            <li data-rule="minLength" class="text-muted"><span class="fa fa-times-circle"></span> 8 caractères</li>
            <li data-rule="requireSpecials" class="text-muted"><span class="fa fa-times-circle"></span> 1 caractère spécial</li>
            <li data-rule="requireUpperLowerCase" class="text-muted"><span class="fa fa-times-circle"></span> 1 majuscule et minuscule</li>
            <li data-rule="requireNumbers" class="text-muted"><span class="fa fa-times-circle"></span> 1 chiffre</li>
        </ul>`;
    const usernameValidatorTpl = `
        <ul class="validator-rules small list-unstyled">
            <li data-rule="alphaDash" class="text-muted"><span class="fa fa-times-circle"></span> Caractères alphanumériques et _</li>
        </ul>`;

    let hasErrors = 0;

    function swithClass($el, currentClass, newClass, switchable) {
        if ($el.hasClass(currentClass) || switchable) {
            $el.removeClass(currentClass);
            $el.addClass(newClass);
        } else if ($el.hasClass(newClass)) {
            $el.removeClass(newClass);
            $el.addClass(currentClass);
        }
    }
    $('.validate-password').on('input', function() {
        const $this = $(this);
        const password = $.trim($this.val());
        const $rules = $('.validator-rules');
        let rules = {
            minLength: false,
            requireSpecials: false,
            requireNumbers: false,
            requireUpperLowerCase: false,
        };
        rules.minLength = (password.length >= 8);
        rules.requireSpecials = (/[^0-9A-Za-zÀ-ÖØ-öø-ÿ]/.test(password));
        rules.requireUpperLowerCase = (/[A-Z]/.test(password) && /[a-z]/.test(password));
        rules.requireNumbers = (/[0-9]/.test(password));
        for (const rule of Object.keys(rules)) {
            const $li = $rules.find(`[data-rule=${rule}]`);
            if (rules[rule]) {
                $li.removeClass('text-muted');
                $li.addClass('text-success');
                $li.find('.fa').removeClass('fa-times-circle').addClass('fa-check-circle');
            } else {
                $li.find('.fa').removeClass('fa-check-circle').addClass('fa-times-circle');
                hasErrors += 1;
                $li.removeClass('text-success');
                $li.addClass('text-muted')
            }

            //swithClass($rules.find(`[data-rule=${rule}]`), 'text-mut', 'text-red', rules[rule]);
        }
    });
    $('.validate-username').on('input', function() {
        const $this = $(this);
        const username = $.trim($this.val());
        const $rules = $('.validator-rules');
        let rules = {
            alphaDash: false
        };
        if (/[^0-9A-Za-z_]/.test(username)) {
            rules.alphaDash = false;
        } else {
            rules.alphaDash = true;
        }
        for (const rule of Object.keys(rules)) {
            const $li = $rules.find(`[data-rule=${rule}]`);
            if (rules[rule]) {
                $li.find('.fa').removeClass('fa-times-circle').addClass('fa-check-circle');
                $li.removeClass('text-muted');
                $li.addClass('text-success');
            } else {
                hasErrors += 1;
                $li.find('.fa').removeClass('fa-check-circle').addClass('fa-times-circle');
                $li.removeClass('text-success');
                $li.addClass('text-muted')
            }
        }
    }).trigger('input');

    if (hasErrors) {

    }
})(jQuery);