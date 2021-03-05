<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

use App\Models\Facture;

class Personnel extends Model
{
    use HasFactory,Notifiable;

    private static $DAY_INTERVAL = 190;
    protected $table = 'personnel';
    protected $primaryKey = 'Matricule';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        //'Matricule',
        'Date_d_embauche',			 			 	
        'Nom',	   		 			   	
        'Prenom',	   		 			   	
        'Date_de_naissance', 			 			   	
        'Lieu_de_naissance', 	   		 			   	
        'Sexe', 	   		 			   	
        'Situation_Matrimoniale', 	   		 			   	
        'Nombre_d_enfant', 			   	
        'Cin_personnel', 	   		 			   	
        'Date_cin_personnel',		 			     
        'Lieu_delivrance_du_cin_personnel', 	   		 			   
        'Date_duplicata_cin_personnel',		
        'Lieu_de_dupliacata_cin_personnel',   				   	
        'Adresse_du_personnel', 			   	
        'Contact_du_personnel',	   		 			   	
        'Nom_et_prenom_du_tuteur',  		 			   	
        'Lien_de_parente',	 			   	
        'Cin_du_tuteur',		   	
        'Date_de_delivrance_cin_tuteur',		 			   	
        'Adresse_du_tuteur',		 			   	
        'Contact_du_tuteur',		 			   	
        'Fonction_a_l_embauche',	 			   	
        'Fonction_actuelle',			   	
        'Mode_de_pass_login',		 			   	
        'statut',
    ];

    static function getFirstWithCA($conditions){
        $personnel = self::where($conditions)
        ->select('Matricule', 'Nom', 'Prenom')
        ->first();
        if($personnel){
            $date = date("Y-m-d");
            $first_date = date('Y-m-d', strtotime($date.' - '.self::$DAY_INTERVAL.' days'));
            $facture = DB::table('facture')
            ->where('facture.Matricule_personnel','like',$personnel->Matricule)
            ->whereBetween('facture.Date', [$first_date, $date])
            ->select(DB::raw('COALESCE(SUM(detailvente.Quantite * prix.Prix_detail),0) as CA'))
            ->join('detailvente', 'detailvente.Facture', '=', 'facture.id')
            ->join('prix', 'detailvente.ID_prix', '=', 'prix.Id')
            ->first();
            $personnel->CA = 0;
            if($facture->CA){
                $personnel->CA = $facture->CA;
            }
            return $personnel;
        }
    }

    public function getFirstWhere(Request $request){
        $conditions = [];
        if(isset($request->criteres)){
            $donnee = json_decode($request->criteres);
            foreach($donnee as $column => $value){
                $conditions[] = [$column,'like',$value];
            }
        }
        $personnel = Personnel::where($conditions)
            ->first();
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
}
