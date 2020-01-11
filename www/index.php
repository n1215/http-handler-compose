<?php

require __DIR__ . '/../vendor/autoload.php';

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\Sample\HttpContext;
use N1215\Http\Context\HttpHandlerInterface;
use N1215\Http\Context\Handler\Writer\Writer;
use N1215\Http\Context\Handler\ResponseSender;
use N1215\Http\Context\Matcher\ContextMatchResult;
use N1215\Http\Context\Handler\Composite\CompositeHandler;
use N1215\Http\Context\Handler\Writer\ServerError;
use N1215\Http\Context\Handler\Writer\ClientError;
use N1215\Http\Context\Matcher\ContextMatcherInterface;
use N1215\Http\Context\Matcher\ContextMatchResultInterface;
use N1215\Http\Context\Matcher\Route\Route;
use N1215\Http\Context\Interceptor\HandlerInterceptor;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response;


class BenchMarker extends HandlerInterceptor
{
    public function handle(HttpContextInterface $context, HttpHandlerInterface $next) : HttpContextInterface
    {
        $start = microtime(true);

        $newContext = $next->__invoke($context);

        $time = round((microtime(true) - $start) * 1000, 3);

        $handlerName = get_class($next);
        $text = "<p style='color:#999; font-size:70%;'>{$handlerName} processed in <em>{$time}</em> ms</p>\n";

        return $newContext
            ->handledBy(Writer::addBody($text));
    }
}


class HelloWorld implements HttpHandlerInterface
{
    public function __invoke(HttpContextInterface $context) : HttpContextInterface
    {
        return $context
            ->handledBy(Writer::addBody("<p>Hello, World!!</p>\n"));
    }
}


class MaintenanceMode implements ContextMatcherInterface
{
    public function match(HttpContextInterface $context) : ContextMatchResultInterface
    {
        return new ContextMatchResult($context, [], false);
    }

}


/**
 * @param HttpHandlerInterface[] $handlers
 * @return CompositeHandler
 */
function __(array $handlers = []) : CompositeHandler
{
    return new CompositeHandler($handlers);
}


compose_application: {

    $helloWorld = new HelloWorld();
    
    $helloRepeat = __()
        ->pipe(Writer::setStatusCode(200))
        ->repeat($helloWorld, 5)
        ->map(new BenchMarker());

    $addHtmlHeader = Writer::addBody("<!DOCTYPE html>\n<html>\n<head>\n<meta charset=\"utf-8\">\n<title>Title</title>\n</head>\n<body>\n");
    $addHtmlFooter = Writer::addBody("</body>\n</html>\n");
    
    $app = __()
        ->pipe($addHtmlHeader)
        ->switch([
            [new MaintenanceMode(), ServerError::serviceUnavailable('maintenance mode')],
            [Route::get('/'), $helloWorld],
            [Route::get('/repeat'), $helloRepeat],
            [Route::all(), ClientError::notFound('page not found')],
        ])
        ->wrappedIn(new BenchMarker())
        ->pipe($addHtmlFooter);
}

// create context
$context = new HttpContext(ServerRequestFactory::fromGlobals(), new Response());

// app handles context
$newContext = $app->__invoke($context);

// send http response
$newContext->handledBy(new ResponseSender());

exit();