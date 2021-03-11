<?php

namespace App\Services;

use App\Models\DetailMission;
use App\Models\Classement;
use App\Models\Mission;

class AccompagnementService {


    public function __construct()
    {
        //
    }

    public static function test(){
        return "ok test";
    }

    public static function generatePlanning($idMission,$coach){
        $personnels = DetailMission::getPersonnelFromCoach($coach,$idMission);
        $classement = Classement::getFromMatricules($idMission,$personnels);
        $mission = Mission::getFirst([['Id_de_la_mission','like',$idMission]]);
        $Date_depart = $mission->Date_depart;
        $Date_de_fin = $mission->Date_de_fin;
        $periods = self::date_range($Date_depart, $Date_de_fin);
        
        foreach($periods as $period){

        }
       return true;
    }

    public static function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
        while( $current <= $last ) {
    
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

}