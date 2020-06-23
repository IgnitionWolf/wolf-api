<?php

namespace IgnitionWolf\API\Requests;

/**
 * Handles authorization for listing entities.
 * This should be extended and used for specific entities 'list' actions.
 */
class ListEntityRequest extends EntityRequest
{
    /**
     * {@inheritdoc}
     */
    public function authorize()
    {
        return $this->can('list', $this->entity);
    }
}
