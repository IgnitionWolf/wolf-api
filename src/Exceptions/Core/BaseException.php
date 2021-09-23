<?php

namespace IgnitionWolf\API\Exceptions\Core;

use Throwable;
use Exception;

abstract class BaseException extends Exception
{
    /**
     * Construct the exception object.
     *
     * @param int $code
     * @param string $message
     * @param string $prettyCode
     * @param mixed $meta
     * @param Throwable|null $previous
     */
    public function __construct(
        int $code = 500,
        string $message = '',
        protected string $prettyCode = '',
        protected mixed $meta = [],
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getPrettyCode(): string
    {
        return $this->prettyCode;
    }

    /**
     * @param string $prettyCode
     */
    public function setPrettyCode(string $prettyCode): void
    {
        $this->prettyCode = $prettyCode;
    }

    /**
     * @return array|mixed
     */
    public function getMeta(): mixed
    {
        return $this->meta;
    }

    /**
     * @param array|mixed $meta
     */
    public function setMeta(mixed $meta): void
    {
        $this->meta = $meta;
    }
}
