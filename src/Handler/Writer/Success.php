<?php

namespace N1215\Http\Context\Handler\Writer;

use N1215\Http\Context\Handler\Composite\CompositeHandler;
use N1215\Http\Context\HttpHandlerInterface;

class Success
{
    public static function ok(string $content) : HttpHandlerInterface
    {
        return new CompositeHandler([
            new SetStatusCode(200),
            new AddBody($content),
        ]);
    }

    public static function created(string $content) : HttpHandlerInterface
    {
        return new CompositeHandler([
            new SetStatusCode(201),
            new AddBody($content),
        ]);
    }
}