<?php

namespace IgnitionWolf\API\Requests;

/**
 * Handles authorization for deleting entities requests.
 * This should be extended and used for specific entities 'delete' actions.
 */
class DeleteEntityRequest extends EntityRequest
{
    /**
     * {@inheritdoc}
     */
    public function authorize()
    {
        return $this->can('delete', $this->findEntity($this->route('id')));
    }
}
