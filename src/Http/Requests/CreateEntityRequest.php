<?php

namespace IgnitionWolf\API\Http\Requests;

use Exception;

/**
 * Handles authorization for creating entities requests.
 * This should be extended and used for specific entities 'create' actions.
 */
class CreateEntityRequest extends EntityRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws Exception
     */
    public function authorize(): bool
    {
        return $this->can('create', static::$model);
    }
}
