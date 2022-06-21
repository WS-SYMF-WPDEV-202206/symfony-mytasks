<?php

namespace App\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class ActionsTransformer implements DataTransformerInterface
{
    public function transform($action)
    {
        return $action->getStatus();
    }
    public function reverseTransform($value)
    {
        return $value;
    }
}