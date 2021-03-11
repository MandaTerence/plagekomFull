<template>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="inputMission">Mission</label>
            <select class="form-control" id="inputMission" v-model="idMission" >
                <option v-bind:key="mission.Id_de_la_mission" v-bind:value="mission.Id_de_la_mission" v-for="mission in missions">{{ mission.Id_de_la_mission }}</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="inputFonction">Fonction</label>
            <select class="form-control" id="inputFonction" v-model="idFonction" v-on:change="changeCustomId">
                <option v-bind:key="fonction.designation" v-bind:value="fonction.id" v-for="fonction in fonctions">{{ fonction.designation }}</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="inputMatricule">Matricule</label>
            <input type="text" placeholder="what are you looking for?" v-model="matricule" class="form-control" v-on:keyup="autoComplete" v-on:click="autoComplete">
            <div class="panel-footer" style="float:top">
                <ul class="list-group">
                    <li class="list-group-item" v-for="result in resultats" v-bind:key="result" v-on:click.left="changeMatriculeValue(result.Matricule)" >
                        <div >{{ result.Matricule }}</div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="form-group col-md-4">
            <button class="d-none d-lg-block btn btn-primary " style="margin:30px" v-on:click="addPersonnel">Ajouter</button>
        </div>
    </div>
    <button class="d-block d-lg-none btn btn-primary " style="margin:30px" v-on:click="addPersonnel">Ajouter</button>
    <div class="row">
        <div class="col-6">
            <h4>Coachs</h4>
        </div>
        <div class="col-6">
            <h4 class="text-right">Nombre: {{coachs.length }}</h4>
        </div>
    </div>
    <div class="row">
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
    </div>
    <div class="row">
        <div class="col-6">
            <h4>Commerciaux</h4>
        </div>
        <div class="col-6">
            <h4 class="text-right">Nombre: {{commerciaux.length }}</h4>
        </div>
    </div>
    <div class="row">
        <table class="table table-hover">
            <thead >
                <tr class="bg-primary" style="color:white">
                    <th scope="col-md-2">matricule</th>
                    <th scope="col-md-2 d-none">nom et prenom</th>
                    <th scope="col-md-2">C.A</th>
                    <th scope="col-md-1"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="commercial in commerciaux" v-bind:key="commercial">
                    <td scope="col-md-2">{{ commercial.Matricule }}</td>
                    <td scope="col-md-2">{{ commercial.Nom+commercial.Prenom }}</td>
                    <td scope="col-md-2">{{ commercial.CA }}</td>
                    <td scope="col-md-1">
                        <button class="btn btn-danger" v-on:click="remove(commerciaux,commercial.Matricule)">
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
    <div class="row">
        <div class="col-12 text-right">
            <button class="btn btn-primary" v-on:click="getClassement">lancer le classement</button>
        </div>
    </div>

    <div name="modal" v-if="showModal" @close="showModal = false">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <!--
                    <div class="modal-header">
                        <slot name="header">
                        default header
                        </slot>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                        default body
                        </slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            default footer
                            <button class="modal-default-button" @click="$emit('close')">
                                OK
                            </button>
                        </slot>
                    </div>
                    -->
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
                            <button class="btn btn-primary" v-on:click="validateEquipe ">Valider</button>
                            <button class="btn btn-primary" v-on:click="showModal = false">retour</button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreationEquipe",
    data() {
        return {
            matricule: '',
            fonctions: [],
            missions: [],
            commerciaux: [],
            coachs: [],
            resultats: [],
            classements: [],
            idFonction: null,
            idMission: null,
            isSearchingAutoComplete: false,
            maxCoach:1,
            maxCommerciaux:8,
            customId: null,
            showModal: false,
        }
    },
    created() {
        this.loadFonctions();
        this.loadMissions();
    },
    methods: {
        loadFonctions(){
            //this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get('/api/fonctions') 
                .then(response => {
                    this.fonctions = response.data;
                    //alert(JSON.stringify(this.fonctions));
                    this.idFonction = this.fonctions[0].id;
                    this.customId = this.fonctions[0].customId;
                    //this.searchPersonnels();
                })
                .catch(function (error) {
                    console.error(error);
                });
            //})
        },
        loadMissions(){
            //this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get('/api/missions',{params: {criteres: {Statut: 'En_cours'}}}) 
                .then(response => {
                    if(response.data.success){
                        //alert(response.message);
                        this.missions = response.data.data;
                        this.idMission = this.missions[0].Id_de_la_mission;
                    }
                    else{
                        console.log(response.data.message);
                    }
                })
                .catch(function (error) {
                    console.error(error);
                });
            //})
        },
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
                //alert(JSON.stringify(this.getMatriculeAndPlaceFromArray(this.classements)));
                axios.post('/api/classements/',{matriculeCoach: this.coachs[0].Matricule,matriculeCommerciaux: this.getMatriculeAndPlaceFromArray(this.classements),idMission:this.idMission}).then(response => {
                    if(response.success){
                        this.showModal = false;
                    }
                    else if(!response.success){
                        alert('insertion echoué');
                    }
                });
            }
        },
        searchAutoComplete(){
            this.resultats = [];
            if(this.customId == null){
                axios.get('/api/personnels/getMatriculeByFonction',{params: {fonction: this.idFonction,search: this.matricule}}).then(response => {
                    this.isSearchingAutoComplete = false;
                    if(response.data.success){
                        this.resultats = response.data.personnels;
                        //alert(JSON.stringify(this.resultats));
                    }
                    else{
                        alert(response.data.message);
                    }
                });
            }
            else{
                this.customId.forEach(element => {
                    axios.get('/api/personnels/getMatriculeByFonction',{params: {fonction: element,search: this.matricule}}).then(response => { 
                        if(response.data.success){
                            this.resultats = this.resultats.concat(response.data.personnels);
                        }
                        else{
                            alert(response.data.message);
                        }
                    });
                });
                this.isSearchingAutoComplete = false;
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
                //matricules.push(personnel.Matricule);
            }
            return matricules;
        },
        getClassement(){
            let mat = this.getMatriculeFromArray(this.commerciaux);
            this.classements.splice(0,this.classements.length);
            axios.get('/api/personnels/getClassement',{params: {Matricules: mat}}).then(response => { 
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
        addEquipeFromCoach(coach){
            this.addPersonnelToTable(this.coachs,coach);
            axios.get('/api/personnels/searchPersonnelFromCoach',{params: {coach: coach.Matricule,idMission: this.idMission}}).then(response => {
                //this.addPersonnelToTable(this.coachs,response.data.personnel);
                for(let i=0;i<response.data.data.length;i++){
                    if(this.commerciaux.length>=this.maxCommerciaux){
                        
                    }
                    else if(response.data.data[i].Matricule!=coach.Matricule){
                        this.addPersonnelToTable(this.commerciaux,response.data.data[i]);
                    }
                }
            });
        },
        addPersonnel(){
            
            if(this.customId == null){
                if((this.matricule!=null)&&(this.idFonction!=null)){
                    //this.$axios.get('/sanctum/csrf-cookie').then(response => {
                        axios.get('/api/personnels/getFirstWhere',{params: {criteres: {Fonction_actuelle: this.idFonction,Matricule: this.matricule}}}).then(response => {
                            if(response.data.success){
                                if(response.data.personnel.Fonction_actuelle == 2){
                                    if(this.coachs.length > (this.maxCoach-1)){
                                        alert("il existe deja un coach");
                                    }else{
                                        this.addEquipeFromCoach(response.data.personnel);
                                    }
                                }else{
                                    if(this.commerciaux.length > (this.maxCommerciaux-1)){
                                        alert("la limite de commerciaux :"+this.maxCommerciaux+" est deja atteinte");
                                    }else{
                                        this.addPersonnelToTable(this.commerciaux,response.data.personnel);
                                    }
                                }
                            }
                            else{
                                alert('aucun resultat trouvé');
                            }
                        });
                    //});
                }
            }else{
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.customId.forEach(element => {
                        axios.get('/api/personnels/getFirstWhere',{params: {criteres: {Fonction_actuelle: element,Matricule: this.matricule}}}).then(response => {
                            if(response.data.success){
                                if(response.data.personnel.Fonction_actuelle == 2){
                                    if(this.coachs.length > (this.maxCoach-1)){
                                        alert("il existe deja un coach");
                                    }else{
                                        this.addPersonnelToTable(this.coachs,response.data.personnel);
                                    }
                                }else{
                                    if(this.commerciaux.length > (this.maxCommerciaux-1)){
                                        alert("la limite de commerciaux :"+this.maxCommerciaux+" est deja atteinte");
                                    }else{
                                        this.addPersonnelToTable(this.commerciaux,response.data.personnel);
                                    }
                                }
                            }
                        });  
                    });
                });
            }
        },
        addPersonnelToTable(table,personnel){
            let exist = false
            table.forEach(element => {
                if(element.Matricule == personnel.Matricule){
                    exist = true;
                }
            });
            if(exist){
                alert(personnel.Matricule+" est deja present");
            }
            else{
                table.push(personnel);
            }
        },
        remove(table,matricule){
            for( var i = 0; i < table.length; i++){ 
                if ( table[i].Matricule ==  matricule) { 
                    table.splice(i, 1);
                    i--; 
                }
            }
        },
        recalculPlace(){
            let newClassement = [];
            for(let i=0;i<this.classements.length;i++){
                newClassement.push(this.classements[i]);
                newClassement[i].placeTemp = i+1;
                newClassement[i].place = i+1;
            }
            //this.classements.splice(0,this.classement.length);
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
            /*
            let  test = "";
            for(let i=0;i<this.classements.length;i++){
                test+= this.classements[i].Matricule+' T: '+this.classements[i].placeTemp+' P: '+this.classements[i].place+' '+this.classements[i].placeOriginal+'\n';
            }
            alert(test);
            */
        },
        test(){
            alert("test ok");
        },

    }
}
</script>