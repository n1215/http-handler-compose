<?php

namespace N1215\Http\Context\Handler\Control;

use N1215\Http\Context\HttpHandlerInterface;
use N1215\Http\Context\Specification\ContextSpecificationInterface;

class Control
{
    public static function if(ContextSpecificationInterface $spec, HttpHandlerInterface $thenHandler, HttpHandlerInterface $elseHandler) : HttpHandlerInterface
    {
        return new IfHandler($spec, $thenHandler, $elseHandler);
    }

    public static function unless(ContextSpecificationInterface $spec, HttpHandlerInterface $elseHandler, HttpHandlerInterface $thenHandler) : HttpHandlerInterface
    {
        return new IfHandler($spec, $elseHandler, $thenHandler);
    }

    public static function repeat(HttpHandlerInterface $loopHandler, int $times) : HttpHandlerInterface
    {
        return new RepeatHandler($loopHandler, $times);
    }

    public static function while(ContextSpecificationInterface $spec, HttpHandlerInterface $loopHandler) : HttpHandlerInterface
    {
        return new WhileHandler($spec, $loopHandler);
    }

    public static function doWhile(ContextSpecificationInterface $spec, HttpHandlerInterface $loopHandler) : HttpHandlerInterface
    {
        return new DoWhileHandler($spec, $loopHandler);
    }

    /**
     * @param array $pairs
     * @return HttpHandlerInterface
     */
    public static function switch(array $pairs) : HttpHandlerInterface
    {
        return SwitchHandler::createFromPairs($pairs);
    }

}