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

    /**
     * List of relationship names.
     * For example, if you have addresses(): HasMany you need to add 'addresses'.
     * This is used to map relationship data automatically from requests.
     *
     * @var array
     */
    protected $relationships = [];

    /**
     * Automapable settings.
     *
     * @var array
     */
    protected $map = [];

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
                
                if (empty($data)) {
                    continue;
                }
                
                if (($encoded = json_decode($data, true)) && !is_int($encoded)) {
                    // Handle model creation from array
                    $this->$relationship()->create($encoded);
                } else {
                    // Handle IDs (i.e: 1 / 1,2)
                    $data = explode(',', $data);
                    $this->$relationship()->sync($data);
                }
            }
        }
    }
}
