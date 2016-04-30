<?php

namespace N1215\Http\Context\Handler\Writer;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;

/**
 * Class SetStatusCode
 */
class SetStatusCode implements HttpHandlerInterface
{
    /**
     * @var int
     */
    private $code;

    public function __construct(int $code = 200)
    {
        $this->code = $code;
    }

    public function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        $newResponse = $context->getResponse()->withStatus($this->code);
        return $context->withResponse($newResponse);
    }

}