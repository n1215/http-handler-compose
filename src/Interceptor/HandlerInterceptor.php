<?php

namespace N1215\Http\Context\Interceptor;
use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;

abstract class HandlerInterceptor implements HandlerInterceptorInterface {

    public function handle(HttpContextInterface $context, HttpHandlerInterface $next) : HttpContextInterface
    {
        // do stuff

        $newContext = $next($context);

        // do stuff

        return $newContext;
    }

    public function __invoke(HttpHandlerInterface $handler) : HttpHandlerInterface
    {

        return new class($handler, [$this, 'handle']) implements HttpHandlerInterface {

            /**
             * @var HttpHandlerInterface
             */
            private $handler;

            /**
             * @var callable
             */
            private $callable;

            public function __construct(HttpHandlerInterface $handler, callable $callable)
            {
                $this->handler = $handler;
                $this->callable = $callable;
            }

            public function __invoke(HttpContextInterface $context) : HttpContextInterface
            {
                return call_user_func_array($this->callable, [$context, $this->handler]);
            }

        };
    }

}