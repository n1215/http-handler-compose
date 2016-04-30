<?php

namespace N1215\Http\Context\Matcher;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;

class ContextCase implements ContextCaseInterface
{
    /**
     * @var ContextMatcherInterface
     */
    private $matcher;

    /**
     * @var HttpHandlerInterface
     */
    private $handler;

    public function __construct(
        ContextMatcherInterface $matcher,
        HttpHandlerInterface $handler
    )
    {
        $this->matcher = $matcher;
        $this->handler = $handler;
    }

    public function isSatisfiedBy(HttpContextInterface $context) : bool
    {
        return $this->matcher->match($context)->isSuccess();
    }

    public function getMatcher() : ContextMatcherInterface
    {
        return $this->matcher;
    }

    public function getHandler() : HttpHandlerInterface
    {
        return $this->handler;
    }
}