<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;

class TestController extends Controller
{
    public function index()
    {
        $utilisateur = Utilisateur::all()->toArray();
        return array_reverse($utilisateur);
    }
}
