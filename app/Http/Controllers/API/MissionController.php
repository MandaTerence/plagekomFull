<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mission;

class MissionController extends Controller
{
    public function index(Request $request){
        $conditions = [];
        if(isset($request->criteres)){
            $crit = json_decode($request->criteres);
            foreach($crit as $column => $value){
                $conditions[] = [$column,'like',$value];
            }
        }
        $missions = Mission::getAllId($conditions);
        $response = 
        [
            'success'=> true,
            'message'=> count($missions).' results found',
            'data'=> $missions,
        ];
        return $response;
    }
}
