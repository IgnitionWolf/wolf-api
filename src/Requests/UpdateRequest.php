<?php

namespace IgnitionWolf\API\Requests;

/**
 * Handles authorization for updating entities requests.
 * This should be extended and used for specific entities 'update' actions.
 */
class UpdateRequest extends EntityRequest
{
    /**
     * {@inheritdoc}
     */
    public function authorize()
    {
        return $this->can('update', $this->entity);
    }
}
