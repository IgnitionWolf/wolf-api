<?php

namespace IgnitionWolf\API\Requests;

/**
 * Handles authorization for listing entities.
 * This should be extended and used for specific entities 'list' actions.
 */
class ListEntityRequest extends EntityRequest
{
    public static $rules = [
        'filter' => 'nullable|syntax:{*:[string]}',
        'sort' => 'nullable|syntax:{field:string, order:ASC OR DESC}'
    ];

    /**
     * @inheritdoc
     */
    public function authorize()
    {
        return $this->can('list', static::$entity);
    }
}
