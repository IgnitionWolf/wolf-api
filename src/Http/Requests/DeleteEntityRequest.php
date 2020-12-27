<?php

namespace IgnitionWolf\API\Http\Requests;

/**
 * Handles authorization for deleting entities requests.
 * This should be extended and used for specific entities 'delete' actions.
 */
class DeleteEntityRequest extends EntityRequest
{
    /**
     * @inheritdoc
     */
    public function authorize(): bool
    {
        return $this->can('delete', $this->find($this->idFromRoute()));
    }
}
