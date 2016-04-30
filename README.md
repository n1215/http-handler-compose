# Compose Application using HttpContextInterface and HttpHandlerInterface
See www/index.php


#### compose: HttpHandler × HttpHandler -> HttpHandler

    /**
     * @var HttpHandlerInterface $helloWorld
     * @var HttpHandlerInterface $helloRepeat
     * @var HttpHandlerInterface $addHtmlHeader
     * @var HttpHandlerInterface $addHtmlFooter
     */
    $helloWorld = new HelloWorld();

    $helloRepeat = __()
        ->pipe(Writer::setStatusCode(200))
        ->repeat($helloWorld, 5)
        ->map(new BenchMarker());

    $addHtmlHeader = Writer::addBody("<!DOCTYPE html>\n<html>\n<head>\n<meta charset=\"utf-8\">\n<title>Title</title>\n</head>\n<body>\n");
    $addHtmlFooter = Writer::addBody("</body>\n</html>\n");

    /**
     * @var CompositeHandler|HttpHandlerInterface $app
     */
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

    // create http context
    $context = new HttpContext(ServerRequestFactory::fromGlobals(), new Response());

    // app handles context
    $newContext = $app($context);

    // send http response
    $newContext->handledBy(new ResponseSender());


# Interfaces

### HttpContext
HttpContext holds PSR-7 HTTP request, HTTP response, and state.

    interface HttpContextInterface
    {
        public function getRequest() : ServerRequestInterface;

        public function getResponse() : ResponseInterface;

        public function isTerminated(): bool;

        public function withRequest(ServerRequestInterface $request): HttpContextInterface;

        public function withResponse(ResponseInterface $response): HttpContextInterface;

        public function withIsTerminated(bool $isTerminated): HttpContextInterface;

        public function handledBy(HttpHandlerInterface $handler): HttpContextInterface;

    }


### HttpHandler
Handles HttpContext.
An abstraction of Http middlewares, HTTP applications, or controller actions in typical MVC web frameworks.

    interface HttpHandlerInterface
    {
        public function __invoke(HttpContextInterface $context) : HttpContextInterface;
    }

### ContextSpecification
the specification pattern.
corresponds if statement.

### ContextMatcher
corresponds switch statement.
can emulate http router.

- ContextMatchResult
- ContextCase

### HandlerInterceptor
transform a HttpHandler to another one.
enables simple AOP.

# License
MIT License.
