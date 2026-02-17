<?php

declare(strict_types=1);

namespace PlanB\DS\Exception;

/**
 * Exception thrown when an element is not found in a collection.
 */
final class ElementNotFoundException extends \RuntimeException
{
    /**
     * Constructor is private to enforce factory method usage.
     */
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    /**
     * Creates an exception for a missing key.
     *
     * @param int|string $key The key that was not found
     */
    public static function missingKey(int|string $key): self
    {
        $message = "The key '{$key}' doesn't exists";

        return new self($message);
    }
}
