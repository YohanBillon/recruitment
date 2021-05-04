<?php

namespace App\Models\expand_validator;

class ExpandValidatorLeaf {
    const KEY = 'leaf';

    /**
     * Build a generic array of parameters if it is not a 'leaf', otherwise build a 'leaf' depending on $validators.
     * 
     * @param   string $type Type of the array of parameters, can be either 'object', 'array' or 'leaf'.
     * @param   array $values Validators of the array of parameters.
     * @return  array The parameters as array('type'=>$type, 'validators'=>$values) or build a specific 'leaf' depending on $validators.
     */
    public static function build(String $type, Array $values): Array{
        if(strcmp($type, self::KEY)) return array('type'=>$type, 'validators'=>$values);
        return self::create_values(array_shift($values), $values);
    }

    /**
     * Create a 'leaf' and process 'object' and 'array' types, otherwise return a generic array.
     * 
     * @param   string $type Type of the 'leaf'.
     * @param   array $values The validator.
     * @return  array A specific 'leaf' depending either 'object' or 'array', or just a 'leaf'.
     */
    private static function create_values(String $type, Array $values): Array{
        switch($type){
            case ExpandValidatorObject::KEY:
                $ev = ExpandValidatorObject::build();
                self::create_by_keys($ev[ExpandValidatorObject::FIELD], $values);
                return $ev;
            case ExpandValidatorArray::KEY:
                $ev = ExpandValidatorArray::build();
                self::create_by_keys($ev[ExpandValidatorArray::FIELD][ExpandValidatorObject::FIELD], $values);
                return $ev;
            default:
                array_push($values, $type);
                return array('type'=>self::KEY, 'validators'=>$values);
        }
    }

    /**
     * Process a validator.
     * 
     * @param   array &$body Reference of the position to create the validator.
     * @param   array $keys The validator.
     * @return  void Pass by reference, no return expected.
     */
    private static function create_by_keys(Array &$body, Array $keys){
        if(empty($keys)) return;
        if(sizeof($keys) > 1) throw new \Exception('So much parameters', 400);

        $key_values = explode(':', array_shift($keys));
        if(sizeof($key_values) != 2) throw new \Exception('Invalide parameter', 400);

        $parameters = explode(',', array_pop($key_values));
        foreach($parameters as $param){
            $body[$param] = array('type'=>self::KEY, 'validators'=>[]);
        }
    }
}
