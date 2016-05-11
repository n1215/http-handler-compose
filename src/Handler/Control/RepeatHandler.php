<?php

namespace N1215\Http\Context\Handler\Control;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;
use N1215\Http\Context\Specification\ContextSpecificationInterface;

class RepeatHandler implements HttpHandlerInterface {

    /**
     * @var HttpHandlerInterface
     */
    private $loop;

    /**
     * @var int
     */
    private $times;

    public function __construct(HttpHandlerInterface $loop, int $times)
    {
        $this->loop = $loop;
        $this->times = $times;
    }

    public function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        for ($i = 0; $i < $this->times; $i++) {
            $context = $this->loop->__invoke($context);
        }
        return $context;
    }
}
