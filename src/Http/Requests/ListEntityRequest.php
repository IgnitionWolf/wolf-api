<?php

namespace IgnitionWolf\API\Http\Requests;

use Exception;

/**
 * Handles authorization for listing entities.
 * This should be extended and used for specific entities 'list' actions.
 */
class ListEntityRequest extends EntityRequest
{
    public static array $rules = [
        'filter' => 'nullable|syntax:{*:[string OR number]}',
        'sort' => 'nullable|syntax:{field:string, order:ASC OR DESC}'
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws Exception
     */
    public function authorize(): bool
    {
        return $this->can('list', static::$model);
    }
}
