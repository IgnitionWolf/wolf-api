<?php

namespace IgnitionWolf\API\Http\Requests;

use Exception;

/**
 * Handles authorization for deleting entities requests.
 * This should be extended and used for specific entities 'delete' actions.
 */
class DeleteEntityRequest extends EntityRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws Exception
     */
    public function authorize(): bool
    {
        return $this->can('delete', $this->find($this->idFromRoute()));
    }
}
