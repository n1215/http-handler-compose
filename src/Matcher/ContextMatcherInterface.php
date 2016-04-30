<?php

namespace N1215\Http\Context\Matcher;
use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;
use N1215\Http\Handler\Compose\Specification\ContextSpecificationInterface;

interface ContextMatcherInterface
{
    public function match(HttpContextInterface $context) : ContextMatchResultInterface;
}