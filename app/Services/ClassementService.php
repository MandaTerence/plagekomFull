<?php

namespace App\Services;

use App\Models\Personnel;
use App\Models\Classement;
use App\Services\PersonnelService;

class ClassementService {

    public function getClassement($matricules,$produits){
        $personnels = [];
        if(isset($matricules)){
             
        }
        return $personnels;
    }

    public static function compareCAGlobal($pa,$pb){
        if ($pa->CAGlobal == $pb->CAGlobal) { 
            return 0;
        }
        return ($pa->CAGlobal > $pb->CAGlobal) ? -1 : 1;
    }

    public static function compareCAMission($pa,$pb){
        if ($pa->CAMission == $pb->CAMission) { 
            return 0;
        }
        return ($pa->CAMission > $pb->CAMission) ? -1 : 1;
    }

    public static function compareCALocal($pa,$pb){
        if ($pa->CALocal == $pb->CALocal) { 
            return 0;
        }
        return ($pa->CALocal > $pb->CALocal) ? -1 : 1;
    }

    public static function compareCAProduit($pa,$pb){
        if ($pa->CAProduit == $pb->CAProduit) { 
            return 0;
        }
        return ($pa->CAProduit > $pb->CAProduit) ? -1 : 1;
    }

    public static function getClassementGlobal($matricules){
        $personnels = PersonnelService::getPersonnelsCAGlobal($matricules);
        usort($personnels,'static::compareCAGlobal');
        for($i=0;$i<count($personnels);$i++){
            $personnels[$i]->place = $i+1;
        }
        return $personnels;
    }

    public static function getClassementLocal($matricules){
        $personnels = PersonnelService::getPersonnelsCALocal($matricules);
        usort($personnels,'static::compareCALocal');
        for($i=0;$i<count($personnels);$i++){
            $personnels[$i]->place = $i+1;
        }
        return $personnels;
    }

    public static function getClassementMission($matricules){
        $personnels = PersonnelService::getPersonnelsCAMission($matricules);
        usort($personnels,'static::compareCAMission');
        for($i=0;$i<count($personnels);$i++){
            $personnels[$i]->place = $i+1;
        }
        return $personnels;
    }

    public static function getClassementProduit($matricules,$produit){
        $personnels = PersonnelService::getPersonnelsCAProduit($matricules,$produit);
        usort($personnels,'static::compareCAProduit');
        for($i=0;$i<count($personnels);$i++){
            $personnels[$i]->place = $i+1;
        }
        return $personnels;
    }

}