<?php

namespace markhuot\keystone\helpers\base;

use Craft;
use craft\helpers\App;

/**
 * @template T
 * @param class-string<T> $class
 * @return T
 */
function app(string $class)
{
    return Craft::$container->get($class);
}

function parseEnv(string $alias): string
{
    $result = App::parseEnv($alias);

    throw_if(! $result || ! is_string($result) || $result === $alias, 'The alias '.$alias.' could not be resolved');

    return $result;
}

/**
 * @phpstan-assert !true $condition
 */
function throw_if(mixed $condition, \Exception|string $message): void
{
    if ($condition) {
        if (is_object($message) && $message instanceof \Exception) {
            throw $message;
        } else {
            throw new \RuntimeException($message);
        }
    }
}

/**
 * @phpstan-assert true $condition
 */
function throw_unless(mixed $condition, \Exception|string $message): void
{
    if (! $condition) {
        if (is_object($message) && $message instanceof \Exception) {
            throw $message;
        } else {
            throw new \RuntimeException($message);
        }
    }
}
