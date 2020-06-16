<?php

namespace IgnitionWolf\API\Exceptions\Core;

class ExceptionBridge {

    /**
     * @var array<Throwable>
     */
    protected $map;

    public function __construct($exceptionBridgeMap) {
        $this->map = $exceptionBridgeMap;
    }

    /**
     * Get the equivalent custom exception of a 3rd party provider exception.
     * Mostly used to override Laravel exceptions and format them correctly.
     * 
     * This value is computed in the package's service provider.
     * 
     * @return array<Throwable>
     */
    public function getMap(): array {
        return $this->map;
    }

    /**
     * Acts as the bridge between two exceptions.
     * Intercepts a desired exception then throws another.
     * 
     * @throws Throwable
     * @return void
     */
    public function intercept(\Throwable $exception) {
        foreach($this->getMap() as $class => $target) {
            if($exception instanceof $class) {
                $newException = new $target;

                /**
                 * Pass the message value if existent
                 */
                if($message = $exception->getMessage()) {
                    $newException->setMessage($message);
                }

                throw $newException;
            }
        }
    }
}