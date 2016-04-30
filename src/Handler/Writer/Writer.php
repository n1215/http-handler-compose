<?php

namespace N1215\Http\Context\Handler\Writer;

use N1215\Http\Context\HttpHandlerInterface;

class Writer
{
    public static function addBody(string $content) : HttpHandlerInterface
    {
        return new AddBody($content);
    }

    public static function setStatusCode(int $code = 200) : HttpHandlerInterface
    {
        return new SetStatusCode($code);
    }
}