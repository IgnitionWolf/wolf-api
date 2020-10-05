<?php

namespace IgnitionWolf\API\Rules;

use Exception;
use IgnitionWolf\API\Services\RequestValidator;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Contracts\Foundation\Application;

/**
 * Validates the structure/syntax of JSON parameters. Note, this doesn't support nested JSON (for now).
 *
 * Validator usage:
 *
 *      syntax: {field:type}
 *      in order to abstract the field name (useful for filtering unknown attributes names): {*: type} (* is the field)
 *
 *      allowed types: number, string, value1 OR value2
 *      if you're expecting an array, you can wrap it in square brackets: [number, string, value1 OR value2]
 *
 */
class SyntaxRule implements Rule
{
    public function __invoke(Application &$app): void
    {
        Validator::extend('syntax', function ($attribute, $value, $parameters, $validator) use ($app) {
            $syntax = implode(',', $parameters);

            $syntax = str_replace(
                ['{', '}', ',', ':', ' OR ', ' ', '-OR-'],
                ['{"', '"}', '","', '":"', '-OR-', '', ' OR '],
                $syntax
            );

            if (!$syntax = json_decode($syntax, true)) {
                $error = json_last_error_msg();
                throw new Exception("You have an issue in your JSON rule syntax for $attribute: $error");
            }

            $requestValueAsJson = json_decode($value, true);
            foreach ($syntax as $field => $type) {
                if ($field === '*') {
                    foreach ($requestValueAsJson as $fieldValue) {
                        if (!$this->checkType($type, $fieldValue)) {
                            return false;
                        }
                    }
                } elseif (!isset($requestValueAsJson[$field])) {
                    return false;
                } else {
                    $fieldValue = $requestValueAsJson[$field];

                    if (!$this->checkType($type, $fieldValue)) {
                        return false;
                    }
                }
            }

            return true;
        });

        Validator::replacer('syntax', function ($message, $attribute, $rule, $parameters) {
            $syntax = implode(',', $parameters);
            return "The expected syntax for $attribute is: $syntax";
        });
    }

    /**
     * Type-check a value.
     *
     * @param string $type
     * @param string|array|number $value
     * @return bool
     */
    private function checkType(string $type, $value)
    {
        switch ($type) {
            case 'number':
                if (!is_numeric($value)) {
                    return false;
                }
                break;
            case 'string':
                if (!is_string($value)) {
                    return false;
                }
                break;
            default:
                // Detect array syntax (field:[string])
                if (str_contains($type, '[') && str_contains($type, ']')) {
                    if (!is_array($value)) {
                        return false;
                    }

                    preg_match('#\[(.*?)\]#', $type, $match);
                    if (isset($match[1])) {
                        foreach ($value as $innerValue) {
                            if (!$this->checkType($match[1], $innerValue)) {
                                return false;
                            }
                        }
                    }
                } elseif (str_contains($type, ' OR ')) {
                    if (!in_array(strtolower($value), explode(' or ', strtolower($type)))) {
                        return false;
                    }
                }
                break;
        }
        return true;
    }
}
