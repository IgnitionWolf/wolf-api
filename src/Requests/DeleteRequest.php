<?php

namespace IgnitionWolf\API\Requests;

/**
 * Handles authorization for deleting entities requests.
 * This should be extended and used for specific entities 'delete' actions.
 */
class DeleteRequest extends EntityRequest
{
    /**
     * {@inheritdoc}
     */
    public function authorize()
    {
        return $this->can('delete', $this->entity);
    }
}
