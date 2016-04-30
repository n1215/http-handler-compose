<?php

namespace N1215\Http\Context\Matcher\Route;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\Matcher\ContextMatcherInterface;
use N1215\Http\Context\Matcher\ContextMatchResult;
use N1215\Http\Context\Matcher\ContextMatchResultInterface;

class MethodAndPathMatcher implements ContextMatcherInterface
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $path;

    public function __construct(string $method, string $path)
    {
        $this->method = $method;
        $this->path = $path;
    }

    public function isSatisfiedBy(HttpContextInterface $context) : bool
    {
        if ($context->getRequest()->getMethod() !== $this->method) {
            return false;
        }

        return $context->getRequest()->getUri()->getPath() === $this->path;
    }

    public function match(HttpContextInterface $context) : ContextMatchResultInterface
    {
        return new ContextMatchResult($context, [], $this->isSatisfiedBy($context));
    }

};
