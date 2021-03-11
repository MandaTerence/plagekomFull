<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EquipeCoach;

class Accompagnement extends Model
{
    use HasFactory;
    protected $table = 'mesaccompanement';
    protected $primaryKey = 'Id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'Id_de_la_mission',
        'Commercial',
        'Coach',
        'Date',
    	'Heure_debut',
        'Heure_fin',
        'Ordre',
    ];

    public function getFromMissionAndCoach($mission,$coach){
        if((isset($mission))&&(isset($coach))){
            
        }
    }
    
}
