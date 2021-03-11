<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Fonction;
use Illuminate\Http\Request;

class FonctionController extends Controller
{
    const DEFAULT_MAX_RESULT = 20;
    const AUTHORISED_ID = [2];
    const CUSTOM_FONCTION = [
        array("id" => "c1","designation" => "Commerciaux","customId"=>[1,6])
    ];

    public function index(Request $request){
        $conditions = [];
        $res = [];
        if(isset($request->criteres)){
            foreach($request->criteres as $column => $value){
                $conditions[] = [$column,'like',$value];
            }
        }
        $fonctions = Fonction::where($conditions)
            ->take(self::DEFAULT_MAX_RESULT)
            ->get();

        

        foreach($fonctions as $f){
            foreach(self::AUTHORISED_ID as $id){
                if($f->id==$id){
                    $res[] = $f;
                }
            }
        }

        foreach (self::CUSTOM_FONCTION as $customFonction) {
            $res[] = (object)$customFonction;
        }

        return $res; 
    }

}
