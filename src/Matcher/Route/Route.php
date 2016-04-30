<?php

namespace N1215\Http\Context\Matcher\Route;

use N1215\Http\Context\HttpContextInterface;
use N1215\Http\Context\Matcher\ContextMatcherInterface;
use N1215\Http\Context\Matcher\ContextMatchResult;
use N1215\Http\Context\Matcher\ContextMatchResultInterface;

class Route
{
    public static function method(string $method, string $path) : ContextMatcherInterface
    {
        return new MethodAndPathMatcher($method, $path);
    }

    public static function get(string $path) : ContextMatcherInterface
    {
        return new MethodAndPathMatcher('GET', $path);
    }

    public static function post(string $path) : ContextMatcherInterface
    {
        return new MethodAndPathMatcher('POST', $path);
    }

    public static function put(string $path) : ContextMatcherInterface
    {
        return new MethodAndPathMatcher('PUT', $path);
    }

    public static function delete(string $path) : ContextMatcherInterface
    {
        return new MethodAndPathMatcher('DELETE', $path);
    }

    public static function all() : ContextMatcherInterface
    {
        return new class() implements ContextMatcherInterface
        {
            public function match(HttpContextInterface $context) : ContextMatchResultInterface
            {
                return new ContextMatchResult($context, [], true);
            }
        };
    }
}