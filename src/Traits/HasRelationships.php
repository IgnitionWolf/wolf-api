<?php

namespace IgnitionWolf\API\Traits;

use Illuminate\Support\Str;

trait HasRelationships
{
    /**
     * List of relationship names.
     * For example, if you have addresses(): HasMany you need to add 'addresses'.
     * This is used to map relationship data automatically from requests.
     *
     * @var array
     */
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
     * @param array $relationshipData
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

                $encoded = is_array($data) ? $data : json_decode($data, true);
                if ($encoded) {
                    // Handle model creation from array
                    $this->$relationship()->create($encoded);
                } else {
                    // Handle IDs (i.e: 1 / 1,2)
                    $data = explode(',', $data);

                    if (empty($data)) {
                        continue;
                    }

                    // For example, belongsTo() doesn't support sync() because it's a single value, not multiple.
                    if (method_exists($this->$relationship(), 'sync')) {
                        $this->$relationship()->sync($data);
                    } else {
                        $this->$relationship()->associate($data[0]);
                    }
                }
            }
        }
    }
}
