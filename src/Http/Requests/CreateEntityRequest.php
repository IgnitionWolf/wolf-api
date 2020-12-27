<?php

namespace IgnitionWolf\API\Http\Requests;

/**
 * Handles authorization for creating entities requests.
 * This should be extended and used for specific entities 'create' actions.
 */
class CreateEntityRequest extends EntityRequest
{
    /**
     * {@inheritdoc}
     */
    public function authorize(): bool
    {
        return $this->can('create', static::$model);
    }
}
