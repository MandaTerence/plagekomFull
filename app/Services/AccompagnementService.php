<?php

namespace App\Services;

use App\Models\DetailMission;
use App\Models\Classement;
use App\Models\Mission;
use App\Models\Accompagnement;


class AccompagnementService {

    CONST DATE_FORMAT = 'yy-m-d';

    CONST JOUR_ACCOMPAGNEMENT = [
        //lundi
        ['Date'=>1,'place'=>8,'Heure_debut'=>'08:00:00','Heure_fin'=>'10:00:00','Ordre'=>1],
        ['Date'=>1,'place'=>7,'Heure_debut'=>'10:30:00','Heure_fin'=>'12:00:00','Ordre'=>2],

        ['Date'=>1,'place'=>8,'Heure_debut'=>'14:00:00','Heure_fin'=>'15:30:00','Ordre'=>1],
        ['Date'=>1,'place'=>7,'Heure_debut'=>'16:00:00','Heure_fin'=>'17:30:00','Ordre'=>2],
        //mardi
        ['Date'=>2,'place'=>6,'Heure_debut'=>'08:00:00','Heure_fin'=>'10:00:00','Ordre'=>1],
        ['Date'=>2,'place'=>5,'Heure_debut'=>'10:30:00','Heure_fin'=>'12:00:00','Ordre'=>2],

        ['Date'=>2,'place'=>6,'Heure_debut'=>'14:00:00','Heure_fin'=>'15:30:00','Ordre'=>1],
        ['Date'=>2,'place'=>5,'Heure_debut'=>'16:00:00','Heure_fin'=>'17:30:00','Ordre'=>2],
        //mercredi
        ['Date'=>3,'place'=>4,'Heure_debut'=>'08:00:00','Heure_fin'=>'10:00:00','Ordre'=>1],
        ['Date'=>3,'place'=>3,'Heure_debut'=>'10:30:00','Heure_fin'=>'12:00:00','Ordre'=>2],

        ['Date'=>3,'place'=>4,'Heure_debut'=>'14:00:00','Heure_fin'=>'15:30:00','Ordre'=>1],
        ['Date'=>3,'place'=>3,'Heure_debut'=>'16:00:00','Heure_fin'=>'17:30:00','Ordre'=>2],
        //jeudi
        ['Date'=>4,'place'=>2,'Heure_debut'=>'08:00:00','Heure_fin'=>'10:00:00','Ordre'=>1],
        ['Date'=>4,'place'=>1,'Heure_debut'=>'10:30:00','Heure_fin'=>'12:00:00','Ordre'=>2],

        ['Date'=>4,'place'=>2,'Heure_debut'=>'14:00:00','Heure_fin'=>'15:30:00','Ordre'=>1],
        ['Date'=>4,'place'=>1,'Heure_debut'=>'16:00:00','Heure_fin'=>'17:30:00','Ordre'=>2],
        //vendredi
        ['Date'=>5,'place'=>8,'Heure_debut'=>'08:00:00','Heure_fin'=>'10:00:00','Ordre'=>1],
        ['Date'=>5,'place'=>7,'Heure_debut'=>'10:30:00','Heure_fin'=>'12:00:00','Ordre'=>2],

        ['Date'=>5,'place'=>8,'Heure_debut'=>'14:00:00','Heure_fin'=>'15:30:00','Ordre'=>1],
        ['Date'=>5,'place'=>7,'Heure_debut'=>'16:00:00','Heure_fin'=>'17:30:00','Ordre'=>2],
        //samedi
        ['Date'=>6,'place'=>6,'Heure_debut'=>'08:00:00','Heure_fin'=>'10:00:00','Ordre'=>1],
        ['Date'=>6,'place'=>5,'Heure_debut'=>'10:30:00','Heure_fin'=>'12:00:00','Ordre'=>2],

        ['Date'=>6,'place'=>4,'Heure_debut'=>'14:00:00','Heure_fin'=>'15:30:00','Ordre'=>1],
        ['Date'=>6,'place'=>3,'Heure_debut'=>'16:00:00','Heure_fin'=>'17:30:00','Ordre'=>2]
    ];

    public function __construct()
    {
        //
    }

    public static function test(){
        return "ok test";
    }

    public static function generatePlanning($idMission,$coach){
        $personnels = DetailMission::getPersonnelFromCoach($coach,$idMission);
        $matricules = [];
        foreach($personnels as $p){
            $matricules[] = $p->Matricule;
        }
        $classement = Classement::getFromMatricules($idMission,$matricules);
        $mission = Mission::getFirst([['Id_de_la_mission','like',$idMission]]);
        $Date_depart = $mission->Date_depart;
        $Date_de_fin = $mission->Date_de_fin;
        $periods = self::date_range($Date_depart, $Date_de_fin);
        foreach($periods as $period){
            $p = str_replace('/', '-', $period);
            $jour = date('w', strtotime($p));
            $dateInserer = date(self::DATE_FORMAT, strtotime($p));
            foreach(self::JOUR_ACCOMPAGNEMENT as $plan){
                if($jour == $plan['Date']){
                    $com = $classement[$plan['place']-1];
                    $acc = new Accompagnement();
                    $acc->Id_de_la_mission = $idMission;
                    $acc->Commercial = $com->Commercial;
                    $acc->Coach = $coach;
                    $acc->Date = $dateInserer;
                    $acc->Heure_debut = $plan['Heure_debut'];
                    $acc->Heure_fin = $plan['Heure_fin'];
                    $acc->Ordre = $plan['Ordre'];
                    $acc->save();
                }
            }
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