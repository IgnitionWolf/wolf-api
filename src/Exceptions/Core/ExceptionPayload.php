<?php

namespace Spatie\Skeleton\Exceptions\Core;

class ExceptionPayload extends \Illuminate\Support\MessageBag
{

    /**
     * Exception arguments to define the exception's renderal data.
     * @var string
     */
    const ARG_STATUS_CODE = 'statusCode';
    const ARG_IDENTIFIER = 'code';
    const ARG_MESSAGE = 'message';
    const ARG_META = 'meta';

    /**
     * Valid arguments
     * @var array
     */
    private $validArgs = [
        self::ARG_MESSAGE,
        self::ARG_STATUS_CODE,
        self::ARG_IDENTIFIER,
        self::ARG_META
    ];

    /**
     * Required arguments
     * @var array
     */
    private $requiredArgs = [
        self::ARG_STATUS_CODE,
        self::ARG_IDENTIFIER
    ];

    /**
     * Default values
     */
    public static $defaults = [
        self::ARG_STATUS_CODE => 500,
        self::ARG_MESSAGE => 'We encountered an internal error, please contact an administrator.',
        self::ARG_IDENTIFIER => 'INTERNAL_ERROR'
    ];

    /**
     * Construct the payload object to be used in Exceptions that inherit BaseExtension.
     * 
     * @param array $bag
     * @throws Exception
     * @return void
     */
    public function __construct(array $bag)
    {

        $this->validate($bag);

        $bag = array_filter($bag, function ($value, $arg) {
            return in_array($arg, $this->validArgs);
        }, ARRAY_FILTER_USE_BOTH);

        /* Serialize meta if array */
        if (isset($bag[self::ARG_META]) && is_array($bag[self::ARG_META])) {
            $bag[self::ARG_META] = json_encode($bag[self::ARG_META]);
        }

        parent::__construct($bag);
    }

    /**
     * Validate the payload by looking for required arguments.
     * 
     * @param array $bag
     * @throws Exception
     * @return void
     */
    public function validate(array $bag)
    {
        foreach ($this->requiredArgs as $arg) {
            if (!in_array($arg, array_keys($bag))) {
                throw new \Exception("Argument $arg is required for this exception.");
            }
        }
    }
}
