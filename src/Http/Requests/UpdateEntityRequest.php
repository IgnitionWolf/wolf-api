<?php

namespace IgnitionWolf\API\Http\Requests;

/**
 * Handles authorization for updating entities requests.
 * This should be extended and used for specific entities 'update' actions.
 */
class UpdateEntityRequest extends EntityRequest
{
    /**
     * @inheritdoc
     */
    public function authorize(): bool
    {
        return $this->can('update', $this->find($this->idFromRoute()));
    }
}
