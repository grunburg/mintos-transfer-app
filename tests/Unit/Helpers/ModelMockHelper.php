<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Unit\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Mockery;
use Mockery\Mock;

/**
 * @template T
 */
class ModelMockHelper
{
    /**
     * @param class-string<T> $class
     * @param array $properties
     * @return Mock | T
     * @throws Exception
     */
    public static function mock(string $class, array $properties = []): Mock | Model
    {
        if (!is_subclass_of($class, Model::class)) {
            throw new Exception('Provided class does not extend Model.');
        }

        /** @var Model $mock */
        $mock = Mockery::mock($class)->makePartial();
        $mock->setRawAttributes($properties);

        return $mock;
    }
}