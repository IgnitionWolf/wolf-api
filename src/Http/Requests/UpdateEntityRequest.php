<?php

namespace IgnitionWolf\API\Http\Requests;

use Exception;

/**
 * Handles authorization for updating entities requests.
 * This should be extended and used for specific entities 'update' actions.
 */
class UpdateEntityRequest extends EntityRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws Exception
     */
    public function authorize(): bool
    {
        return $this->can('update', $this->find($this->idFromRoute()));
    }
}
