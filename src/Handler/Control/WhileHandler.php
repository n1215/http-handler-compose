<?php

namespace N1215\Http\Context\Handler\Control;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;
use N1215\Http\Context\Specification\ContextSpecificationInterface;

class WhileHandler implements HttpHandlerInterface {

    /**
     * @var ContextSpecificationInterface
     */
    protected $spec;

    /**
     * @var HttpHandlerInterface
     */
    protected $loop;

    public function __construct(
        ContextSpecificationInterface $spec,
        HttpHandlerInterface $loop
    )
    {
        $this->spec = $spec;
        $this->loop = $loop;
    }


    public function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        while ($this->spec->isSatisfiedBy($context)) {
            $context = $this->loop->__invoke($context);
        }

        return $context;
    }
}
