<?php

namespace PS\UtilisateurBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordStrength extends Constraint
{
    /**
     * @var string
     */
    public $tooShortMessage = 'password.minlength';
    /**
     * @var string
     */
    public $missingLettersMessage = 'Your password must include at least one letter.';
    /**
     * @var string
     */
    public $requireCaseDiffMessage = 'password.require_lower_upper';
    /**
     * @var string
     */
    public $missingNumbersMessage = 'password.require_number';
    /**
     * @var string
     */
    public $missingSpecialsMessage = 'password.require_special';
    /**
     * @var string
     */
    public $missingUpperCaseMessage = 'password.require_upper';
    /**
     * @var string
     */
    public $missingLowerCaseMessage = 'password.require_lower';

     /**
     * @var string
     */
     public $missingLowerUpperCaseMessage = 'password.require_lower_upper';

    /**
     * @var int
     */
    public $minLength = 8;
    /**
     * @var mixed
     */
    public $requireLetters = false;
    /**
     * @var mixed
     */
    public $requireCaseDiff = true;
    /**
     * @var mixed
     */
    public $requireNumbers = true;
    /**
     * @var mixed
     */
    public $requireSpecials = true;
    /**
     * @var mixed
     */
    public $requireUpperCase = false;
    /**
     * @var mixed
     */
    public $requireLowerCase = false;

    /**
     * @var mixed
    */
    public $requireLowerUpperCase = false;

    /**
     * @var string
     */
    public $charset = 'UTF-8';
}
