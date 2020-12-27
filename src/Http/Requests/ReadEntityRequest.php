<?php

namespace IgnitionWolf\API\Http\Requests;

/**
 * Handles authorization for reading entities requests.
 * This should be extended and used for specific entities 'read' actions.
 */
class ReadEntityRequest extends EntityRequest
{
    /**
     * @inheritdoc
     */
    public function authorize(): bool
    {
        return $this->can('read', $this->find($this->idFromRoute()));
    }
}
