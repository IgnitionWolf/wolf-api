<?php

namespace IgnitionWolf\API\Http\Requests;

use Exception;

/**
 * Handles authorization for reading entities requests.
 * This should be extended and used for specific entities 'read' actions.
 */
class ReadEntityRequest extends EntityRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws Exception
     */
    public function authorize(): bool
    {
        return $this->can('read', $this->find($this->idFromRoute()));
    }
}
