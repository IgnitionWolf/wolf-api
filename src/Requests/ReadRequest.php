<?php

namespace Spatie\Skeleton\Requests;

/**
 * Handles authorization for reading entities requests.
 * This should be extended and used for specific entities 'read' actions.
 */
class ReadRequest extends EntityRequest
{
    /**
     * {@inheritdoc}
     */
    public function authorize()
    {
        return $this->can('read', $this->entity);
    }
}
