<?php

namespace IgnitionWolf\API\Requests;

/**
 * Handles authorization for creating entities requests.
 * This should be extended and used for specific entities 'create' actions.
 */
class CreateEntityRequest extends EntityRequest
{
    /**
     * {@inheritdoc}
     */
    public function authorize()
    {
        return $this->can('create', $this->entity);
    }
}
