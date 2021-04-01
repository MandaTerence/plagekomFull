<template>
    <button v-on:click="test">test</button>
    <SearchProduit v-model:produits="produits"/>
    <ProduitTab v-model:produits="produits"/>
    <SearchPersonnel v-model:commerciaux="commerciaux" v-model:coachs="coachs"/>
    <EquipeTab v-model:equipes="coachs" titre="Coachs"/>
    <EquipeTab v-model:equipes="commerciaux" titre="Commerciaux"/>
    <div class="row" >
        <div class="col-12 text-right">
            <button class="btn btn-primary" v-on:click="getClassement">lancer le classement</button>
        </div>
    </div>

    <div name="modal" v-if="showModal" @close="showModal = false">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-body">
                        <table class="table table-hover">
                            <thead >
                                <tr class="bg-primary" style="color:white">
                                    <th scope="col-md-2">matricule</th>
                                    <th scope="col-md-2">nom et prenom</th>
                                    <th scope="col-md-2">C.A</th>
                                    <th scope="col-md-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="coach in coachs" v-bind:key="coach">
                                    <td scope="col-md-2">{{ coach.Matricule }}</td>
                                    <td scope="col-md-2">{{ coach.Nom+" "+coach.Prenom }}</td>
                                    <td scope="col-md-2">{{ coach.CA }}</td>
                                    <td scope="col-md-1">
                                        <button class="btn btn-danger" v-on:click="remove(coachs,coach.Matricule)">
                                        <div class="d-none d-lg-block">
                                            supprimer
                                        </diV>
                                        <div class="d-block d-lg-none">
                                        X
                                        </div>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-hover">
                            <thead >
                                <tr class="bg-primary" style="color:white">
                                    <th scope="col-md-2">matricule</th>
                                    <th scope="col-md-2 d-none">nom et prenom</th>
                                    <th scope="col-md-2">C.A</th>
                                    <th scope="col-md-2">place</th>
                                    <th scope="col-md-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="classement in classements" v-bind:key="classement">
                                    <td scope="col-md-2" v-if="classement.place == classement.placeOriginal">{{ classement.Matricule }}</td>
                                    <td scope="col-md-2" style="color:red" v-else-if="classement.place > classement.placeOriginal">{{ classement.Matricule }} ({{  classement.placeOriginal - classement.place }} place)</td>
                                    <td scope="col-md-2" style="color:green" v-else>{{ classement.Matricule }} (+{{  classement.placeOriginal - classement.place }} place)</td>
                                    <td scope="col-md-2">{{ classement.Nom+classement.Prenom }}</td>
                                    <td scope="col-md-2">{{ classement.CA }}</td>
                                    <th scope="col-md-2"><input type="number" v-model="classement.placeTemp" v-on:change="changeClassement(classement.place,classement.placeTemp)"/></th>
                                    <td scope="col-md-1">
                                        <button class="btn btn-danger" v-on:click="remove(classement,classement.Matricule)">
                                        <div class="d-none d-lg-block">
                                            supprimer
                                        </diV>
                                        <div class="d-block d-lg-none">
                                            X
                                        </div>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            <button class="btn btn-primary" v-on:click="validateEquipe">Valider</button>
                            <button class="btn btn-primary" v-on:click="showModal = false">retour</button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EquipeTab from "../components/EquipeTab";
import SearchPersonnel from "../components/SearchPersonnel";
import SearchProduit from "../components/SearchProduit";
import ProduitTab from "../components/ProduitTab";

export default {
    name: "CreationEquipe",
    data() {
        return {
            isSearchingAutoComplete: false,
            maxCoach:1,
            maxCommerciaux:8,
            showModal: false,

            matricule: '',

            fonctions: [],
            missions: [],

            commerciaux: [],
            coachs: [],
            produits: [],

            idFonction: null,
            idMission: null,
            idProduit: null,

            customId: null,

            resultats: [],
            classements: [],
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/login";
        }
        next();
    },
    created() {

    },
    methods: {
        
        fonctionOnChange(){
            this.resultats = [];
            this.matricule = '';
        },
        changeMatriculeValue(newMatricule){
            this.resultats = [];
            this.matricule = newMatricule;
        },
        changeCustomId(){
            this.customId = null;
            for(let i = 0;i< this.fonctions.length;i++){
                if(this.fonctions[i].id == this.idFonction){
                    if(this.fonctions[i].customId){
                        this.customId = this.fonctions[i].customId;
                    }
                }
            }
            this.autoComplete();
        },
        autoComplete(){
            if((this.matricule.length > 2)&&(!this.isSearchingAutoComplete)){
                this.isSearchingAutoComplete = true;
                if(this.idFonction != null ){
                    setTimeout(this.searchAutoComplete, 1000);
                }
            }    
        },
        validateEquipe(){
            if(this.coachs.length<this.maxCoach){
                alert("il manque "+(this.maxCoach-this.coachs.length)+" coach");
            }
            else if(this.commerciaux.length<this.maxCommerciaux){
                alert("il manque "+(this.maxCommerciaux-this.commerciaux.length)+" commerciaux");
            }
            else{
                axios.post('/api/classements/',{matriculeCoach: this.coachs[0].Matricule,matriculeCommerciaux: this.getMatriculeAndPlaceFromArray(this.classements),idMission:this.idMission}).then(response => {
                    if(response.data.success){
                        this.showModal = false;
                        this.testRouter(this.idMission,this.coachs[0].Matricule);
                    }
                    else if(!response.data.success){
                        alert('insertion echoué');
                    }
                });
            }
        },
        getMatriculeAndPlaceFromArray(personnels){
            let matricules = [];
            for(let i=0;i<personnels.length;i++){
                let element = {'Matricule' : personnels[i]['Matricule'], 'Place' : personnels[i]['place'] };
                matricules.push(element);
            }
            return matricules;
        },
        getMatriculeFromArray(personnels){
            let matricules = [];
            for(let i=0;i<personnels.length;i++){
                matricules.push(personnels[i]['Matricule']);
            }
            return matricules;
        },
        getCodeProduitFromArray(produits){
            let codeProduits = [];
            for(let i=0;i<produits.length;i++){
                codeProduits.push(produits[i]['Code_produit']);
            }
            return codeProduits;
        },
        getClassement(){
            let matricules = this.getMatriculeFromArray(this.commerciaux);
            let produits =  this.getCodeProduitFromArray(this.produits);
            this.classements.splice(0,this.classements.length);
            axios.get('/api/personnels/getClassement',{params: {Matricules: matricules,Produits: produits}}).then(response => { 
                if(response.data.personnels!=null){
                    for(let i=0;i<response.data.personnels.length;i++){
                        let perso = response.data.personnels[i];
                        perso.placeTemp = response.data.personnels[i].place;
                        perso.placeOriginal = response.data.personnels[i].place;
                        this.classements.push(perso);
                    }
                    this.showModal = true;
                }
                
            });
        },
        recalculPlace(){
            let newClassement = [];
            for(let i=0;i<this.classements.length;i++){
                newClassement.push(this.classements[i]);
                newClassement[i].placeTemp = i+1;
                newClassement[i].place = i+1;
            }
            for(let i=0;i<this.classements.length;i++){
                this.classements.splice(i,1,newClassement[i]);
            }
        },
        changeClassement(place,placeTemp){
            if((placeTemp<1)||(placeTemp>this.classements.length)){
                alert("changement de place impossible");
                this.classements[place-1].placeTemp = place;
            }                        
            else{
                let elementTemp = this.classements[place-1];
                this.classements.splice(place-1,1);
                this.classements.splice(placeTemp-1, 0, elementTemp);
                this.recalculPlace();
            }
            
        },
        testRouter(idMission,coach){
            this.$router.push({ name: 'planning', query: { idMission: idMission ,coach: coach} });
        },
        test(){
            let te =  this.getCodeProduitFromArray(this.produits);
            alert(te);
        },
    },
    
    components: {
        EquipeTab,
        SearchPersonnel,
        SearchProduit,
        ProduitTab
    }
}
</script>