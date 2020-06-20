<?php

namespace IgnitionWolf\API\Entity;

use IgnitionWolf\API\Entity\Automap\Automapable;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Model extends EloquentModel
{
    use Automapable;
    use SoftDeletes;

    protected $relationships = [];

    /**
     * Get the relationships array list.
     *
     * @return array
     */
    public function getRelationships(): array
    {
        return $this->relationships;
    }

    /**
     * Similar to fill(), but for relationships.
     * This accepts an array with IDs.
     *
     * @return void
     */
    public function fillRelationships(array $relationshipData): void
    {
        foreach ($this->relationships as $relationship) {
            if (method_exists($this, $relationship)) {
                $data = null;
                if (isset($relationshipData[Str::plural($relationship)])) {
                    $data = $relationshipData[Str::plural($relationship)];
                } elseif (isset($relationshipData[$relationship])) {
                    $data = $relationshipData[$relationship];
                }
                
                if (!empty($data)) {
                    $data = explode(',', $data);
                    
                    $this->$relationship()->sync($data);
                }
            }
        }
    }
}
