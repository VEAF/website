<?php

namespace App\Validator\Menu\Item;

use App\Entity\Menu\Item;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TypeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /** @var Item $value */
        /* @var $constraint Type */

        if (!isset(Item::TYPES[$value->getType()])) {
            $this->context->buildViolation($constraint->invalidType)
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }

        if (Item::TYPE_LINK === $value->getType() && '' == $value->getLink()) {
            $this->context->buildViolation($constraint->needLink)
                ->setParameter('{{ value }}', $value->getTypeAsString())
                ->addViolation();
        }

        if (Item::TYPE_URL === $value->getType() && null === $value->getUrl()) {
            $this->context->buildViolation($constraint->needUrl)
                ->setParameter('{{ value }}', $value->getTypeAsString())
                ->addViolation();
        }

        if (Item::TYPE_PAGE === $value->getType() && null === $value->getPage()) {
            $this->context->buildViolation($constraint->needPage)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
