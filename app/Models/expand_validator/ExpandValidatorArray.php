<?php

namespace App\Models\expand_validator;

class ExpandValidatorArray {
    const KEY = "array";
    const FIELD = "items";

    /**
     * Build an 'array' array of parameters.
     * 
     * @return  array The parameters as array('type'=>'object', 'validators'=>array('object'), 'items'=>array('type'=>'object', 'validators'=>array('object'), 'properties'=>array())).
     */    
    public static function build(): Array{
        return array_merge(ExpandValidatorLeaf::build(self::KEY,[self::KEY]),array(self::FIELD => ExpandValidatorObject::build()));
    }
}
