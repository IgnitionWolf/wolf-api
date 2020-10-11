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
        // The route looks like this: /api/entity/{entity}, so the first and only param should be {entity}; the id.
        $route = $this->route();
        $id = $route->parameters()[$route->parameterNames()[0]];
        return $this->can('read', $id);
    }
}
