<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Classement;
use App\Models\Accompagnement;
use Illuminate\Http\Request;
use App\Services\AccompagnementService;

class ClassementController extends Controller
{
    public function create(Request $request){
        $coach = $request->input('matriculeCoach');
        $commerciaux = $request->input('matriculeCommerciaux');
        $idMission = $request->input('idMission');
        $success = Classement::saveFromCommerciaux($idMission,$commerciaux); 
        $mess = AccompagnementService::generatePlanning($idMission,$coach);
        $response = [
            'success' => $success,
            'message' => $mess,
        ];
        return response()->json($response);
    }

    public function getPlanning(Request $request){
        if((isset($request->mission))&&(isset($request->coach))){
            $idMission = $request->mission;
            $coach = $request->coach;
        }
    }

}
