<?php

namespace App\Models\expand_validator;

class ExpandValidatorObject {
    const KEY = "object";
    const FIELD = "properties";

    /**
     * Build an 'object' array of parameters.
     * 
     * @return  array The parameters as array('type'=>'object', 'validators'=>array('object'), 'properties'=>array()).
     */    
    public static function build(): Array{
        return array_merge(ExpandValidatorLeaf::build(self::KEY,[self::KEY]),array(self::FIELD => array()));
    }
}
