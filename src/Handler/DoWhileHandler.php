<?php

namespace N1215\Http\Context\Handler;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;
use N1215\Http\Context\Specification\ContextSpecificationInterface;

class DoWhileHandler implements HttpHandlerInterface {

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
        do {
            $context = $this->loop->__invoke($context);
        } while ($this->spec->isSatisfiedBy($context));

        return $context;
    }
}
