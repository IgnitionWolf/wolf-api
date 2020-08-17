<?php

namespace IgnitionWolf\API\Requests;

/**
 * Handles authorization for updating entities requests.
 * This should be extended and used for specific entities 'update' actions.
 */
class UpdateEntityRequest extends EntityRequest
{
    /**
     * {@inheritdoc}
     */
    public function authorize()
    {
        return $this->can('update', $this->findEntity($this->route('id')));
    }
}
