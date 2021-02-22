<?php

namespace App\Services;

use App\Interfaces\CustomAuthService;
use App\Models\Utilisateur;
use Illuminate\Support\Str;

class AuthentificationService implements CustomAuthService{


    public static $columnNameId = 'Matricule';
    public static $columnNamePassword = 'Mode_de_pass_login';
    public static $authorisedIdprefix = 'OTO*';

    public function __construct()
    {
        //
    }

    public function checkUtilisateur(string $matricule,string $password){
        if(($matricule!=null)&&($matricule!="")&&($password!=null)&&($password!=""))
            return Utilisateur::where('Matricule', $matricule)->where('Mode_de_pass_login',$password)->first();
    }
}