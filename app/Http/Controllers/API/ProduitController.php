<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Helpers\ControllerHelper;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        try{
            $produits = Produit::getAll(ControllerHelper::getConditions($request));
            $response = 
            [
                'success'=> true,
                'message'=> count($produits).' results founds',
                'missions'=> $produits,
            ];
            return $response;
        } 
        catch (\Illuminate\Database\QueryException $exception) {
            $response = 
            [
                'success'=> false,
                'message'=> $exception->errorInfo
            ];
            return $response;
        }  
    }

    public function getMatriculeAndWhere(Request $request){
        try{
            $produits = Produit::getAll(ControllerHelper::getConditions($request));
            $response = 
            [
                'success'=> true,
                'message'=> count($produits).' results founds',
                'missions'=> $produits,
            ];
            return $response;
        } 
        catch (\Illuminate\Database\QueryException $exception) {
            $response = 
            [
                'success'=> false,
                'message'=> $exception->errorInfo
            ];
            return $response;
        }  
    }
}
