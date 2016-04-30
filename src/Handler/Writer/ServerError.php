<?php

namespace N1215\Http\Context\Handler\Writer;

use N1215\Http\Context\Handler\Composite\CompositeHandler;
use N1215\Http\Context\HttpHandlerInterface;

class ServerError
{
    public static function internalServerError(string $content) : HttpHandlerInterface
    {
        return new CompositeHandler([
            new SetStatusCode(500),
            new AddBody($content),
        ]);
    }

    public static function serviceUnavailable(string $content) : HttpHandlerInterface
    {
        return new CompositeHandler([
            new SetStatusCode(503),
            new AddBody($content),
        ]);
    }
}