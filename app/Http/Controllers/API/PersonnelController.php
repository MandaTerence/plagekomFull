<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Models\DetailMission;
use Illuminate\Http\Request;
use App\Helpers\ControllerHelper;
use Illuminate\Support\Facades\DB;

class PersonnelController extends Controller
{
    const DEFAULT_MAX_RESULT = 10;

    public function index(Request $request){
        $personnels = Personnel::where(ControllerHelper::getConditions($request))
            ->take(self::DEFAULT_MAX_RESULT)
            ->get();
        return $personnels; 
    }

    public function compareCA($pa,$pb){
        if ($pa->CA == $pb->CA) { 
            return 0;
        }
        return ($pa->CA > $pb->CA) ? -1 : 1;
    }

    public function getClassement(Request $request){
        $personnels = [];
        $test = '';
        if(isset($request->Matricules)){
            $donnee = $request->Matricules;
            foreach($donnee as $m){
                $conditions = [['Matricule','like',$m]];
                $p = Personnel::getFirstWithCA($conditions);
                if(isset($p)){
                    $personnels[] = $p;
                }
            }
            usort( $personnels, array( $this, 'compareCA' ) );

            for($i=0;$i<count($personnels);$i++){
                $personnels[$i]->place = $i+1;
            }
        }
        
        if(isset($request->Matricules)){
            $response = [
                'success' => true,
                'message' => 'resultat trouvé'.$test,
                'personnels' => $personnels,
            ];
            return response()->json($response);
        }
        else{
            $response = [
                'success' => false,
                'message' => 'aucun resultat trouvé '.$test,
                'personnels' => null,
            ];
            return response()->json($response);
        }
        return $personnels;
    }

    public function getFirstWhere(Request $request){
        $conditions = [];
        if(isset($request->criteres)){
            $donnee = json_decode($request->criteres);
            foreach($donnee as $column => $value){
                $conditions[] = [$column,'like',$value];
            }
        }
        $personnel = Personnel::getFirstWithCA($conditions);
        if($personnel){
            $response = [
                'success' => true,
                'message' => 'resultat trouvé',
                'personnel' => $personnel,
            ];
            return response()->json($response);
        }
        else{
            $response = [
                'success' => false,
                'message' => 'aucun resultat trouvé '.$request->criteres,
                'personnel' => null,
            ];
            return response()->json($response);
        }
    }

    public function getPersonnelFromCoach(Request $request){
        if((isset($request->coach))&&(isset($request->idMission))){
            $coach = $request->coach;
            $idMission = $request->idMission;
            $personnels = DetailMission::getPersonnelFromCoach($coach,$idMission);
            if(count($personnels)>0){
                $response = [
                    'success' => true,
                    'message' => count($personnels).'result found',
                    'data' => $personnels,
                ];
                return $response;
            }
        }
        $response = [
            'success' => false,
            'message' => 'no result found',
            'data' => [],
        ];
        return $response;
    }

    public function getMatriculeByFonction(Request $request){
        if(isset($request->fonction)){
            $conditions = [];
            $conditions[] = ['Fonction_actuelle','=',$request->fonction];
            if(isset($request->search)){
                $conditions[] = ['Matricule','like','%'.$request->search.'%'];
            }
            $personnels = Personnel::getMatricules($conditions);
            $response = [
                'success' => true,
                'message' => count($personnels).' results founds',
                'personnels' => $personnels,
            ];
            return $response;
        }
        else{
            $response = [
                'success' => false,
                'message' => 'fonction vide',
            ];
            return $response;
        }
    }

    public function searchByFonction(Request $request){
        if(isset($request->fonction)){
            $conditions = [];
            $conditions[] = ['Fonction_actuelle','=',$request->fonction];
            if(isset($request->search)){
                $conditions[] = ['Matricule','like','%'.$request->fonction.'%'];
            }
            $data = Personnel::where($conditions)
            ->first();
            $success = true;
            if(empty($emptyArray)){
                $message = 'aucun element trouvé' ;
            }
            else{
                $message = 'recherhe reussit' ;
            }
        }
        else{
            $success = false;
            $message = 'fonction vide' ;
            $data = [];
        }
        $response = [
            'success' => $success,
            'message' => $message,
            'personnel' => $data,
        ];
        return response()->json($response);
    }

}

