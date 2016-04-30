<?php

namespace N1215\Http\Context\Handler\Writer;

use N1215\Http\Context\Handler\Composite\CompositeHandler;
use N1215\Http\Context\HttpHandlerInterface;

class ClientError
{
    public static function notFound(string $content) : HttpHandlerInterface
    {
        return new CompositeHandler([
            new SetStatusCode(404),
            new AddBody($content),
        ]);
    }
}