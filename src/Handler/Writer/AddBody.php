<?php

namespace N1215\Http\Context\Handler\Writer;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;

/**
 * Class AddBody
 */
class AddBody implements HttpHandlerInterface
{
    /**
     * @var string
     */
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        $context->getResponse()->getBody()->write($this->content);
        return $context;
    }
}