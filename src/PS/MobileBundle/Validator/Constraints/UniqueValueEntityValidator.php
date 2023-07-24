<?php

namespace PS\MobileBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueValueEntityValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $entityRepository = $this->em->getRepository($constraint->entityClass);

        if (!is_scalar($constraint->field)) {
            throw new InvalidArgumentException('"field" parameter should be any scalar type');
        }

        $searchResults = $entityRepository->findBy([
            $constraint->field => $value
        ]);

        if (count($searchResults) > 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}