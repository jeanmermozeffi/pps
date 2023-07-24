<?php

namespace PS\UtilisateurBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordStrengthValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return null
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value === null || $value === '') {
            return;
        }

        if (function_exists('grapheme_strlen') && 'UTF-8' === $constraint->charset) {
            $length = grapheme_strlen($value);
        } else {
            $length = mb_strlen($value, $constraint->charset);
        }

        if ($constraint->minLength > 0 && (mb_strlen($value, $constraint->charset) < $constraint->minLength)) {
            $this->context->addViolation($constraint->tooShortMessage, ['{{length}}' => $constraint->minLength]);
        }

        if ($constraint->requireLetters && !preg_match('/\w/', $value)) {
            $this->context->addViolation($constraint->missingLettersMessage);
        }

        if ($constraint->requireUpperCase && !preg_match('/[A-Z]/', $value)) {
            $this->context->addViolation($constraint->missingUpperCaseMessage);
        }

        if ($constraint->requireLowerCase && !preg_match('/[a-z]/', $value)) {
            $this->context->addViolation($constraint->missingLowerCaseMessage);
        }

        if ($constraint->requireLowerUpperCase && !(preg_match('/[A-Z]/', $value) && preg_match('/[a-z]/', $value))) {
            $this->context->addViolation($constraint->missingLowerUpperCaseMessage);
        }

        if($constraint->requireCaseDiff && !preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/', $value)) {
            $this->context->addViolation($constraint->requireCaseDiffMessage);
        }

        if ($constraint->requireNumbers && !preg_match('/[0-9]/', $value)) {
            $this->context->addViolation($constraint->missingNumbersMessage);
        }

        //Cc: Control; M: Mark; P: Punctuation; S: Symbol; Z:  Separator
        //Not checked: L: Letter; N: Number; C{fosn}: format, private-use, surrogate, unassigned
        if ($constraint->requireSpecials && !preg_match('/[^0-9A-Za-zÀ-ÖØ-öø-ÿ]/u', $value)) {
            $this->context->addViolation($constraint->missingSpecialsMessage);
        }
    }
}
