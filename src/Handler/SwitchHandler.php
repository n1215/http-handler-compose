<?php

namespace N1215\Http\Context\Handler;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;
use N1215\Http\Context\Matcher\ContextCase;
use N1215\Http\Context\Matcher\ContextCaseInterface;

class SwitchHandler implements HttpHandlerInterface {

    /**
     * @var ContextCaseInterface[]
     */
    protected $cases;

    /**
     * SwitchHandler constructor.
     * @param ContextCaseInterface[] $cases
     */
    public function __construct(array $cases)
    {
        $this->cases = $cases;
    }

    /**
     * @param array $pairs array of ContextMatcherInterface and HttpHandlerInterface pairs
     * @return SwitchHandler
     */
    public static function createFromPairs(array $pairs)
    {
        $cases = [];
        foreach($pairs as $pair) {
            $cases[] = new ContextCase($pair[0], $pair[1]);
        }

        return new self($cases);
    }


    public function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        foreach($this->cases as $case)
        {
            $result = $case->getMatcher()->match($context);
            
            if($result->isSuccess()) {
                return $result->getContext()->handledBy($case->getHandler());
            }
        }

        return $context;
    }
}
