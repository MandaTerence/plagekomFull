<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

use App\Models\Facture;
use App\Models\Produit;

class Personnel extends Model
{

    use HasFactory,Notifiable;
    public static $DEFAULT_MAX_RESULT = 10;
    public static $DAY_INTERVAL = 190;
    const PRIX_CHER = 190;
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

    public function getNomFromMAtricule(){
        $result = self::select('Nom','Prenom')
        ->where('Matricule','like',$this->Matricule)
        ->first();
        $this->Nom = $result->Nom;
        $this->Prenom = $result->Prenom;
        return $this->Nom.' '.$this->Prenom;
    }

    public function getAllCA($produits = []){
        $this->CAGlobal=$this->getCAGlobal();
        $this->CAMission=$this->getCAMission();
        $this->CALocal=$this->getCALocal();
        $produitFinaux = [];
        $CAProduitMoinsCher = 0;
        $CAProduitPlusCher = 0;
        foreach($produits as $produit){
            $CAProduit = $this->getCAselonProduit($produit);
            $produitFinaux[] = [
                "Code_roduit" =>$produit,
                "CAProduit" => $CAProduit
            ];
            $pr = Produit::getFirstWhere([['produit.Code_produit',$produit]]);
            if($pr->prix<self::PRIX_CHER){
                $CAProduitMoinsCher += $CAProduit;
            }
            else{
                $CAProduitPlusCher += $CAProduit;
            }
        }
        $this->CAProduitMoinsCher = $CAProduitMoinsCher;
        $this->CAProduitPlusCher = $CAProduitPlusCher;
        $this->CAProduit= $produitFinaux;
    }

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

    public function getCATotal($coef){
        $this->CATotal = 
            ($this->CAGlobal*$coef['global'])
            +($this->CAMission*$coef['mission'])
            +($this->CALocal*$coef['local'])
            +($this->CAProduitPlusCher*$coef['produitPlusCher'])
            +($this->CAProduitMoinsCher*$coef['produitMoinsCher'])
        ;
        return $this->CATotal;
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

    public static function getDataCoach(){
        $test = DB::table('test')->personnel;
    }

}
