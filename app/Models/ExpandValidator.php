<?php

namespace App\Models;

use App\Models\expand_validator\ExpandValidatorArray;
use App\Models\expand_validator\ExpandValidatorLeaf;
use App\Models\expand_validator\ExpandValidatorObject;

class ExpandValidator {

    /**
     * Processing line by line where each line is a variable that must be expanded if does not exist.
     *
     * @param   array &$curr_pos Current position in the array given by reference.
     * @param   array $keys Array of strings with all variables to process.
     * @param   array $values Array of strings with the validators of the final variable of $keys.
     * @return  void Pass by reference, no return expected.
     */
    public static function ev_encode(Array &$curr_pos, Array $keys, Array $values){
        if(!sizeof($keys)) throw new \Exception('Empty keys', 400);
        if(!sizeof($values)) throw new \Exception('Empty values', 400);
        if(!strcmp(current($keys),'*')) throw new \Exception('First elt cannot be a *', 400);
        if(!strcmp(current($keys),'')) throw new \Exception('Keys cannot be empty', 400);
        if(!strcmp(current($values),'')) throw new \Exception('Keys cannot be empty', 400);

        do{
            $current = current($keys);
            $next = next($keys);
            $curr_pos =& self::ev_next($curr_pos, $next, $current, $values);
        }while($next !== false);
    }

    /**
     * Process a variable and if does not exist: expand as 'object', 'array' or 'leaf' at a position, otherwise move to the next position.
     * 
     * @param   array &$curr_pos Reference of the position where the variable must be expanded.
     * @param   string $next Next variable to process.
     * @param   string $current The variable to process.
     * @param   array $values Array of strings with the validators of the final variable.
     * @return  array The reference of the position where the next variable must be expanded.
     */
    private static function &ev_next(Array &$curr_pos, mixed $next, String $current, Array $values): Array{
        if(!strcmp($current,'*') && !strcmp($next,'*')) throw new \Exception('Two or more * cannot follows each other', 400);

        if($next === false){
            if(!strcmp($current,'*')) throw new \Exception('Last elt cannot be a *', 400);
            $curr_pos[$current] = $expandValidator[$current] = ExpandValidatorLeaf::build(ExpandValidatorLeaf::KEY, $values);
            return $curr_pos;
        }

        if(!strcmp($current,'*')) return $curr_pos;

        $is_in = array_key_exists($current,$curr_pos);
        if(!strcmp($next,'*')){
            if(!$is_in) $curr_pos[$current] = ExpandValidatorArray::build();
            return $curr_pos[$current][ExpandValidatorArray::FIELD][ExpandValidatorObject::FIELD];
        }

        //Overwrites the variable if the type is not an object where an object is expected.
        if(!$is_in || !array_key_exists(ExpandValidatorObject::FIELD, $curr_pos[$current]))
            $curr_pos[$current] = ExpandValidatorObject::build();
        return $curr_pos[$current][ExpandValidatorObject::FIELD];
    }

}
