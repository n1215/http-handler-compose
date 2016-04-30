<?php

namespace N1215\Http\Context\Handler;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;
use N1215\Http\Context\Specification\ContextSpecificationInterface;

class IfHandler implements HttpHandlerInterface {

    /**
     * @var ContextSpecificationInterface
     */
    protected $spec;

    /**
     * @var HttpHandlerInterface
     */
    protected $then;

    /**
     * @var HttpHandlerInterface
     */
    protected $else;

    public function __construct(
        ContextSpecificationInterface $spec,
        HttpHandlerInterface $then,
        HttpHandlerInterface $else = null
    )
    {
        $this->spec = $spec;
        $this->then = $then;
        $this->else = is_null($else) ? new IdenticalHandler : $else;
    }


    public function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        if($this->spec->isSatisfiedBy($context)) {
            return $this->then->__invoke($context);
        }

        return $this->else->__invoke($context);
    }
}
