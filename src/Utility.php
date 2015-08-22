<?php

namespace Optimus\Architect;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Collection;

class Utility {

    /**
     * Get a property of an array or object
     * @param  mixed $objectOrArray object or array
     * @param  string $property
     * @return mixed
     */
    public static function getProperty($objectOrArray, $property)
    {
        if (is_array($objectOrArray) || $objectOrArray instanceof \ArrayAccess) {
            return $objectOrArray[$property];
        } else {
            return $objectOrArray->{$property};
        }
    }

    /**
     * Set a property of an Eloquent model, normal object or array
     * @param mixed $objectOrArray model, object or array
     * @param string $property
     * @param void
     */
    public static function setProperty(&$objectOrArray, $property, $value)
    {
        // Eloquent models are also instances of ArrayAccess, and therefore
        // we check for that first
        if ($objectOrArray instanceof EloquentModel) {
            if ($objectOrArray->relationLoaded($property)) {
                $objectOrArray->setRelation($property, $value);
            } else {
                $objectOrArray->setAttribute($property, $value);
            }
        } elseif (is_array($objectOrArray) || $objectOrArray instanceof \ArrayAccess) {
            $objectOrArray[$property] = $value;
        } else {
            $objectOrArray->{$property} = $value;
        }
    }

    /**
     * Is the input a collection of resources?
     * @param  mixed  $input
     * @return boolean        
     */
    public static function isCollection($input)
    {
        return is_array($input) || $input instanceof Collection;
    }

}
