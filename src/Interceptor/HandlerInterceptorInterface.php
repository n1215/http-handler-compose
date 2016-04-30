<?php

namespace N1215\Http\Context\Interceptor;
use N1215\Http\Context\HttpHandlerInterface;

interface HandlerInterceptorInterface
{
    public function __invoke(HttpHandlerInterface $handler) : HttpHandlerInterface;
}