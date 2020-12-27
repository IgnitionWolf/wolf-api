<?php

namespace IgnitionWolf\API\Http\Requests;

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
     * @inheritdoc
     */
    public function authorize(): bool
    {
        return $this->can('list', static::$model);
    }
}
