<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    const DEFAULT_MAX_RESULT = 20;

    public function index(Request $request){
        $conditions = [];
        if(isset($request->criteres)){
            foreach($request->criteres as $column => $value){
                $conditions[] = [$column,'like',$value];
            }
        }
        $personnels = Personnel::where($conditions)
            ->take(self::DEFAULT_MAX_RESULT)
            ->get();
        return $personnels; 
        /*$personnels = Personnel::all()->toArray();
        return array_reverse($personnels);*/
    }
}
