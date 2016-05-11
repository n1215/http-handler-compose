<?php

namespace N1215\Http\Context\Handler\Composite;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;

/**
 * Class HandlerPipeline
 * Handler Pipeline can be implemented as a HttpHandler.
 */
final class ThenHandler implements HttpHandlerInterface {

    /**
     * @var HttpHandlerInterface
     */
    protected $handler;

    /**
     * HandlerPipeline constructor.
     * @param HttpHandlerInterface $handler
     */
    public function __construct(HttpHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        if($context->isTerminated()) {
            return $context;
        }

        return $this->handler->__invoke($context);
    }
}
