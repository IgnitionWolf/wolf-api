<?php

namespace IgnitionWolf\API\Http\Requests;

use Exception;
use IgnitionWolf\API\Exceptions\NotAuthorizedException;
use IgnitionWolf\API\Exceptions\ValidationException;
use IgnitionWolf\API\Concerns\Bounces;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use IgnitionWolf\API\Exceptions\NotAuthenticatedException;

abstract class EntityRequest extends FormRequest
{
    use Bounces;

    /**
     * @psalm-var class-string
     * @var string
     */
    protected static string $model;

    public static array $rules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return static::$rules ?? [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws Exception
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Helper function to obtain the entity object of a specific ID.
     * @param int|null $id
     * @return mixed
     * @throws Exception
     */
    public function find(?int $id)
    {
        if (!static::$model) {
            throw new Exception('Tried to call find() but the $model has not been assigned yet.');
        }

        $model = app()->make(static::$model);
        if (!$model) {
            throw new Exception('Failed to instantiate ' . static::$model);
        }

        return $model::find($id);
    }

    /**
     * Helper function to extract the ID parameter from the route.
     *
     * @return int|null
     */
    public function idFromRoute(): ?int
    {
        // The route looks like this: /api/entity/{entity}, so the first and only param should be {entity}; the id.
        $route = $this->route();
        if (isset($route->parameterNames()[0])) {
            return (int) $route->parameters()[$route->parameterNames()[0]];
        }
        return null;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     * @throws NotAuthenticatedException|NotAuthorizedException
     */
    protected function failedAuthorization()
    {
        throw new NotAuthorizedException();
    }
}
