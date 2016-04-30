<?php

namespace N1215\Http\Context\Handler;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\HttpHandlerInterface;

class ResponseSender implements HttpHandlerInterface
{
    /**
     * @var int
     */
    private $length;

    public function __construct(int $length = 8192)
    {
        $this->length = $length;
    }

    public function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        $response = $context->getResponse();
        $version = $response->getProtocolVersion();
        $status = $response->getStatusCode();
        $phrase = $response->getReasonPhrase();
        header("HTTP/{$version} {$status} {$phrase}");

        foreach ($response->getHeaders() as $name => $values) {
            $name = str_replace('-', ' ', $name);
            $name = ucwords($name);
            $name = str_replace(' ', '-', $name);
            foreach ($values as $value) {
                header("{$name}: {$value}");
            }
        }

        $stream = $response->getBody();
        $stream->rewind();
        while (!$stream->eof()) {
            echo $stream->read($this->length);
        }
        return $context;
    }
}