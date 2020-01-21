<?php

namespace Optimus\Architect;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Collection;

class Utility
{
    /**
     * Get a property of an array or object
     * @param  mixed $objectOrArray object or array
     * @param  string $property
     * @return mixed
     */
    public static function getProperty($objectOrArray, $property, $format = false)
    {
        if (is_array($objectOrArray)) {
            $ret = $objectOrArray[$property];
        } else {
            $ret = $objectOrArray->{$property};
        }

        if (!$format) {
            return $ret;
        }

        return static::formatProperty($ret);
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
            // Does relation exist?
            // If so, only set the relation if not primitive. Keeping a attribute
            // as a relation will allow for it to be converted to arrays during
            // serialization
            if ($property) {
                if ($objectOrArray->relationLoaded($property) && !Utility::isPrimitive($value)) {
                    $objectOrArray->setRelation($property, $value);

            // If attribute is not a relation we just set it on
            // the model directly. If it is a primitive relation (a relation
            // converted to IDs) we unset the relation and set it as an attribute
                } else {
                    unset($objectOrArray[$property]);
                    $objectOrArray->setAttribute($property, $value);
                }
            }
        } elseif (is_array($objectOrArray)) {
            $objectOrArray[$property] = $value;
        } else {
            $objectOrArray->{$property} = $value;
        }
    }

    /**
     * Is the variable a primitive type
     * @param  mixed  $input
     * @return boolean
     */
    public static function isPrimitive($input)
    {
        return !is_array($input) && !($input instanceof EloquentModel) && !($input instanceof Collection);
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

    /**
     * Format a given property.
     * @param mixed $property
     * @param bool $numericIntegers If true and property passes is_numeric test, cast as an integer
     * @return mixed
     */
    private static function formatProperty($property, $numericIntegers = true)
    {
        // handle default integer based properties
        if ($numericIntegers && is_numeric($property)) {
            return (int) $property;
        }

        return $property;
    }
}
