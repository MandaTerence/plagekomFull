<?php

namespace App\Services;

use App\Interfaces\CustomAuthService;
use App\Models\Personnel;
use Illuminate\Support\Str;

class PersonnelService {

    public function __construct()
    {

    }

    public static function getPersonnelsCAProduit($matricules,$produit){
        $personnels = [];
        foreach($matricules as $matricule){
            $personnel = new Personnel;
            $personnel->Matricule = $matricule;
            $personnel->CAProduit = $personnel->getCAselonProduit($produit);
            $personnels[] = $personnel;
        }
        return $personnels;
    }

    public static function getPersonnelsCAGlobal($matricules){
        $personnels = [];
        foreach($matricules as $matricule){
            $personnel = new Personnel;
            $personnel->Matricule = $matricule;
            $personnel->CAGlobal = $personnel->getCAGlobal();
            $personnels[] = $personnel;
        }
        return $personnels;
    }

    public static function getPersonnelsCALocal($matricules){
        $personnels = [];
        foreach($matricules as $matricule){
            $personnel = new Personnel;
            $personnel->Matricule = $matricule;
            $personnel->CALocal = $personnel->getCALocal();
            $personnels[] = $personnel;
        }
        return $personnels;
    }

    public static function getPersonnelsCAMission($matricules){
        $personnels = [];
        foreach($matricules as $matricule){
            $personnel = new Personnel;
            $personnel->Matricule = $matricule;
            $personnel->CAMission = $personnel->getCAMission();
            $personnels[] = $personnel;
        }
        return $personnels;
    }

}