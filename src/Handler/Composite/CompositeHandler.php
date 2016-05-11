<?php

namespace N1215\Http\Context\Handler\Composite;

use N1215\Http\Context\Handler\DoWhileHandler;
use N1215\Http\Context\Handler\IfHandler;
use N1215\Http\Context\Handler\SwitchHandler;
use N1215\Http\Context\Handler\WhileHandler;
use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;
use N1215\Http\Context\Interceptor\HandlerInterceptorInterface;
use N1215\Http\Context\Specification\ContextSpecificationInterface;

/**
 * Class HandlerPipeline
 * Handler Pipeline can be implemented as a HttpHandler.
 */
final class CompositeHandler implements HttpHandlerInterface {

    /**
     * Handler
     * @var HttpHandlerInterface[]
     */
    protected $handlers = [];

    /**
     * HandlerPipeline constructor.
     * @param HttpHandlerInterface[] $handlers
     */
    public function __construct(array $handlers = [])
    {
        foreach($handlers as $handler) {
            $this->handlers[] = $handler;
        }
    }

    protected function append(HttpHandlerInterface $handler) : CompositeHandler
    {
        return new self(array_merge($this->handlers, [$handler]));
    }


    public function then(HttpHandlerInterface $handler) : CompositeHandler
    {
        return $this->append(new ThenHandler($handler));
    }


    public function always(HttpHandlerInterface $handler) : CompositeHandler
    {
        return $this->append($handler);
    }


    public function completed(HttpHandlerInterface $handler) : CompositeHandler
    {
        return $this->append(new CompletedHandler($handler));
    }


    public function wrappedIn(HandlerInterceptorInterface $interceptor) : CompositeHandler
    {
        return new self([$interceptor->__invoke($this)]);
    }

    public function map(HandlerInterceptorInterface $interceptor) : CompositeHandler
    {
        $newHandlers = array_map(function($handler) use ($interceptor) {
            return $interceptor->__invoke($handler);
        }, $this->handlers);

        return new self($newHandlers);
    }
}
