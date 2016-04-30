<?php

namespace N1215\Http\Context\Matcher;
use N1215\Http\Context\HttpContextInterface;

interface ContextMatchResultInterface
{
    public function getContext(): HttpContextInterface;

    public function getParameters() : array;

    public function isSuccess(): bool;

    public function isFailure(): bool;
}