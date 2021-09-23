<?php

namespace IgnitionWolf\API\Exceptions\Core;

use Exception;

abstract class BaseException extends Exception
{
    /**
     * @var array|string
     */
    protected $meta;

    /**
     * Construct the exception object.
     *
     * Most of the exception data will be taken from getPayload().
     * $meta is just the additional errors array (for example, form errors)
     *
     * @param mixed|null $meta
     * @param Exception|null $previous
     * @throws Exception
     */
    public function __construct($meta = null, Exception $previous = null)
    {
        $this->meta = $meta;

        $this->loadPayload();
        parent::__construct($this->message, (int) $this->code, $previous);
    }

    /**
     * This must be overridden in child classes.
     *
     * @return ExceptionPayload
     * @throws Exception
     */
    public function getPayload(): ExceptionPayload
    {
        return new ExceptionPayload([]);
    }

    /**
     * Set the exception internal message. Even though the exception data
     * should be configured in the ExceptionPayload object, this is useful
     * in bridged exceptions where we need to pass the message over.
     *
     * @param string $value
     * @return self
     */
    public function setMessage(string $value): self
    {
        $this->message = $value;
        return $this;
    }

    /**
     * Access the payload and get a key value.
     *
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public function get(string $key): mixed
    {
        $bag = $this->getPayload();
        $value = $bag->first($key);

        /** Decode if necessary */
        if ($key === ExceptionPayload::ARG_META && is_string($value)) {
            if ($decoded = json_decode($value, true)) {
                $value = $decoded;
            }
        }

        return $value;
    }

    /**
     * Internal function which maps the payload into the exception.
     *
     * @return void
     * @throws Exception
     */
    private function loadPayload()
    {
        $payload = $this->getPayload();
        foreach ($payload as $arg => $value) {
            $this->$arg = $value;
        }
    }
}
