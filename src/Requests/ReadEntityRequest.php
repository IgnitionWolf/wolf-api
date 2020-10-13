<?php

namespace IgnitionWolf\API\Requests;

/**
 * Handles authorization for reading entities requests.
 * This should be extended and used for specific entities 'read' actions.
 */
class ReadEntityRequest extends EntityRequest
{
    /**
     * @inheritdoc
     */
    public function authorize()
    {
        return $this->can('read', $this->findEntity($this->extractIdFromRoute()));
    }
}
