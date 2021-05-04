<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\ExpandValidator;
use Exception;

class Controller extends BaseController{

    /**
     * Expand the JSON input request into a structured and labelled tree in JSON format.
     *
     * @param   Request $request The json input request.
     * @return  string  The structured and labelled tree in JSON format.
     */
    public function createExpandValidator(Request $request){
        $expandValidator = array();
        foreach($request->all() as $keys => $values){
            //If parameters are wrong throw with an Exception and create an HTTP Exception with it.
            try{
                ExpandValidator::ev_encode($expandValidator,explode('.',$keys),explode('|',$values));
            }
            catch(Exception $e){
                abort($e->getCode(),$e->getMessage());
            }
        }
        return response()->json($expandValidator);
    }
}
