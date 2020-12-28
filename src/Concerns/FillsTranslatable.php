<?php

namespace IgnitionWolf\API\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Model;

trait FillsTranslatable
{
    /**
     * Due to a bug in Spatie's package, we need to make sure translations
     * are being handled correctly.
     *
     * @link https://github.com/spatie/laravel-translatable/issues/225
     * @param Model $entity
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function fillTranslatable(Model $entity, array &$data)
    {
        if (!method_exists($entity, 'getTranslatableAttributes') || !method_exists($entity, 'setTranslations')) {
            return;
        }

        foreach ($entity->getTranslatableAttributes() as $attribute) {
            if (!isset($data[$attribute]) || empty($data[$attribute])) {
                continue;
            }

            // Make sure the translatable attribute changed, then unset and assign it again.
            if ($entity->$attribute !== $data[$attribute]) {
                if (is_string($data[$attribute])) {
                    throw new Exception('You should pass an array to translatable fields.', 400);
                }

                unset($entity->$attribute);
                $entity->setTranslations($attribute, $data[$attribute]);
                unset($data[$attribute]);
            }
        }
    }
}
