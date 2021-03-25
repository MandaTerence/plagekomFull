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
    public static $DEFAULT_MAX_RESULT = 10;
    public static $DAY_INTERVAL = 190;
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

    public static $COLUMN_TYPE_MISSION = [
        "local"=>"LOCAL",
        "mission"=>"MISSION"
    ];

    public function getCAGlobal(){
        $nbrJour=self::$DAY_INTERVAL;
        $interval = getDateInterval($nbrJour);
        $facture = DB::table('facture')
        ->where('facture.Matricule_personnel','like',$this->Matricule)
        ->whereBetween('facture.Date', [$interval->firstDate,$interval->lastDate])
        ->select(DB::raw('COALESCE(SUM(detailvente.Quantite * prix.Prix_detail),0) as CA'))
        ->join('detailvente', 'detailvente.Facture', '=', 'facture.id')
        ->join('prix', 'detailvente.ID_prix', '=', 'prix.Id')
        ->first();
        $CAGlobal = 0;
        if($facture->CA){
            $CAGlobal = $facture->CA;
        }
        return $CAGlobal;
    }

    public function getCAMission(){
        $nbrJour=self::$DAY_INTERVAL;
        $interval = getDateInterval($nbrJour);
        $facture = DB::table('facture')
        ->where('facture.Matricule_personnel','like',$this->Matricule)
        ->where('mission.Type_de_mission','like',self::$COLUMN_TYPE_MISSION['mission'])
        ->whereBetween('facture.Date', [$interval->firstDate,$interval->lastDate])
        ->select(DB::raw('COALESCE(SUM(detailvente.Quantite * prix.Prix_detail),0) as CA'))
        ->join('detailvente', 'detailvente.Facture', '=', 'facture.id')
        ->join('prix', 'detailvente.ID_prix', '=', 'prix.Id')
        ->join('mission','mission.Id_de_la_mission','like','facture.Id_de_la_mission')
        ->first();
        $CAMission = 0;
        if($facture->CA){
            $CAMission = $facture->CA;
        }
        return $CAMission;
    }

    public function getCAselonProduit($Produit){
        if(isset($Produit)){
            $nbrJour=self::$DAY_INTERVAL;
            $interval = getDateInterval($nbrJour);
            $facture = DB::table('facture')
            ->where('facture.Matricule_personnel','like',$this->Matricule)
            ->whereBetween('facture.Date', [$interval->firstDate,$interval->lastDate])
            ->where('Produit.Code_produit','=',$Produit)
            ->select(DB::raw('COALESCE(SUM(detailvente.Quantite * prix.Prix_detail),0) as CA'))
            ->join('detailvente', 'detailvente.Facture', '=', 'facture.id')
            ->join('prix', 'detailvente.ID_prix', '=', 'prix.Id')
            ->join('produit', 'produit.Code_produit', '=', 'prix.Code_produit')
            ->join('mission','mission.Id_de_la_mission','like','facture.Id_de_la_mission')
            ->first();
            $CAMission = 0;
            if($facture->CA){
                $CAMission = $facture->CA;
            }
            return $CAMission;
        }
        else{
            return 0;
        }
    }

    public function getCALocal(){
        $nbrJour=self::$DAY_INTERVAL;
        $interval = getDateInterval($nbrJour);
        $facture = DB::table('facture')
        ->where('facture.Matricule_personnel','like',$this->Matricule)
        ->whereBetween('facture.Date', [$interval->firstDate,$interval->lastDate])
        ->where('mission.Type_de_mission','like',self::$COLUMN_TYPE_MISSION['local'])
        ->select(DB::raw('COALESCE(SUM(detailvente.Quantite * prix.Prix_detail),0) as CA'))
        ->join('detailvente', 'detailvente.Facture', '=', 'facture.id')
        ->join('prix', 'detailvente.ID_prix', '=', 'prix.Id')
        ->join('mission','mission.Id_de_la_mission','like','facture.Id_de_la_mission')
        ->first();
        $CAMission = 0;
        if($facture->CA){
            $CAMission = $facture->CA;
        }
        return $CAMission;
    }

    static function getFirstWithCA($conditions){
        $personnel = self::where($conditions)
        ->select('Matricule', 'Nom', 'Prenom','Fonction_actuelle')
        ->first();
        if($personnel){
            $interval = getDateInterval(self::$DAY_INTERVAL);
            $facture = DB::table('facture')
            ->where('facture.Matricule_personnel','like',$personnel->Matricule)
            ->whereBetween('facture.Date', [$interval->firstDate,$interval->lastDate])
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
    
    public static function getMatricules($conditions = []){
        $nbrOfResult = self::$DEFAULT_MAX_RESULT;
        if($nbrOfResult == 'all'){
            return self::select('Matricule')
            ->where($conditions)
            ->get();
        }else{
            return self::select('Matricule')
            ->where($conditions)
            ->take($nbrOfResult)
            ->get();
        }
    }

}
