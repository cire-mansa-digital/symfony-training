<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class BadRecetteValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var BadRecette $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        $value = mb_strtolower($value);
        foreach ($constraint->recettes as $recette) {
            if (str_contains($value, $recette)) {

                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ recette }}', $recette)
                    ->addViolation()
                ;
            }
        }
    }
}
