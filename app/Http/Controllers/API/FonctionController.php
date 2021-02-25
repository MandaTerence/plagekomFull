<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Fonction;
use Illuminate\Http\Request;

class FonctionController extends Controller
{
    const DEFAULT_MAX_RESULT = 20;

    public function index(Request $request){
        $conditions = [];
        if(isset($request->criteres)){
            foreach($request->criteres as $column => $value){
                $conditions[] = [$column,'like',$value];
            }
        }
        $fonctions = Fonction::where($conditions)
            ->take(self::DEFAULT_MAX_RESULT)
            ->get();
        return $fonctions; 
    }
}
