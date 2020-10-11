<?php

namespace IgnitionWolf\API\Requests;

/**
 * Handles authorization for updating entities requests.
 * This should be extended and used for specific entities 'update' actions.
 */
class UpdateEntityRequest extends EntityRequest
{
    /**
     * @inheritdoc
     */
    public function authorize()
    {
        // The route looks like this: /api/entity/{entity}, so the first and only param should be {entity}; the id.
        $route = $this->route();
        $id = $route->parameters()[$route->parameterNames()[0]];
        return $this->can('update', $this->findEntity($id));
    }
}
