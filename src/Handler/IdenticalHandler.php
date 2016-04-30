<?php

namespace N1215\Http\Context\Handler;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;

class IdenticalHandler implements HttpHandlerInterface {

    public function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        return $context;
    }
}
