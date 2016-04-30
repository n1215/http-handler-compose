<?php

namespace N1215\Http\Context\Matcher;

use N1215\Http\Context\HttpHandlerInterface;
use N1215\Http\Context\Specification\ContextSpecificationInterface;

interface ContextCaseInterface extends ContextSpecificationInterface
{
    public function getMatcher() : ContextMatcherInterface;

    public function getHandler() : HttpHandlerInterface;
}