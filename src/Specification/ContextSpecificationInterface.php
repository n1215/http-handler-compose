<?php

namespace N1215\Http\Context\Specification;
use N1215\Http\Context\HttpContextInterface;

interface ContextSpecificationInterface
{
    public function isSatisfiedBy(HttpContextInterface $context) : bool;
}