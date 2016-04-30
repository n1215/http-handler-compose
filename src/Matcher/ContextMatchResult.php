<?php

namespace N1215\Http\Context\Matcher;
use N1215\Http\Context\HttpContextInterface;

class ContextMatchResult implements ContextMatchResultInterface
{
    /**
     * @var HttpContextInterface
     */
    private $context;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var bool
     */
    private $isSuccess;


    public function __construct(
        HttpContextInterface $context,
        array $parameters,
        bool $isSuccess = true
    )
    {
        $this->context = $context;
        $this->parameters = $parameters;
        $this->isSuccess = $isSuccess;

    }

    public function getContext(): HttpContextInterface
    {
        return $this->context;
    }

    public function getParameters() : array
    {
        return $this->parameters;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function isFailure(): bool
    {
        return !$this->isSuccess;
    }
}