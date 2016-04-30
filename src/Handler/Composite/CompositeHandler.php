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


    public function pipe(HttpHandlerInterface $handler) : CompositeHandler
    {
        return new self(array_merge($this->handlers, [$handler]));
    }


    public function append(HttpHandlerInterface $handler) : CompositeHandler
    {
        return new self(array_merge($this->handlers, [$handler]));
    }

    public function prepend(HttpHandlerInterface $handler) : CompositeHandler
    {
        return new self(array_merge([$handler], $this->handlers));
    }

    public function repeat(HttpHandlerInterface $handler, int $times) : CompositeHandler
    {

        $newHandlers = $this->handlers;
        while($times > 0) {
            $newHandlers[] = $handler;
            $times--;
        }
        return new self($newHandlers);
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

    public function if(ContextSpecificationInterface $spec, HttpHandlerInterface $thenHandler, HttpHandlerInterface $elseHandler) : CompositeHandler
    {
        return $this->append(new IfHandler($spec, $thenHandler, $elseHandler));
    }

    public function unless(ContextSpecificationInterface $spec, HttpHandlerInterface $elseHandler, HttpHandlerInterface $thenHandler) : CompositeHandler
    {
        return $this->append(new IfHandler($spec, $elseHandler, $thenHandler));
    }

    public function while(ContextSpecificationInterface $spec, HttpHandlerInterface $loopHandler) : CompositeHandler
    {
        return $this->append(new WhileHandler($spec, $loopHandler));
    }

    public function doWhile(ContextSpecificationInterface $spec, HttpHandlerInterface $loopHandler) : CompositeHandler
    {
        return $this->append(new DoWhileHandler($spec, $loopHandler));
    }

    /**
     * @param array $pairs
     * @return CompositeHandler
     */
    public function switch(array $pairs) : CompositeHandler
    {
        return $this->append(SwitchHandler::createFromPairs($pairs));
    }


    public function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        foreach($this->handlers as $handler) {
            if($context->isTerminated()) {
                return $context;
            }
            $context = $handler->__invoke($context);
        }
        return $context;
    }
}
