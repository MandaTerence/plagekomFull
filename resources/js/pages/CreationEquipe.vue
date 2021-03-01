<template>
    <form>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputFonction">Fonction</label>
                <select class="form-control" id="inputFonction" v-model="idFonction" >
                    <option v-bind:key="fonction.designation" v-bind:value="fonction.id" v-for="fonction in fonctions">{{ fonction.designation }}</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="inputMatricule">Matricule</label>
                <!--<input type="text" class="form-control" id="inputMatricule" placeholder="Matricule">-->
                <div>
                <input type="text" placeholder="what are you looking for?" v-model="matricule" class="form-control">
                    <div class="panel-footer" >
                        <ul class="list-group">
                            <li class="list-group-item" v-for="result in resultats" v-bind:key="result" >
                                <div v-on:click.left="changeMatriculeValue(result.Matricule)">{{ result.Matricule }}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <button class="btn btn-primary" v-on:click="addPersonnel">Ajouter</button>
    <button class="btn btn-primary" v-on:click="autoComplete">search</button>
    <div class="row">
        <table class="table table-hover">
            <thead >
                <tr class="bg-primary" style="color:white">
                    <th scope="col">matricule</th>
                    <th scope="col">nom et prenom</th>
                    <th scope="col">C.A</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="personnel in personnels" v-bind:key="personnel">
                    <td>{{ personnel.Matricule }}</td>
                    <td>{{ personnel.Nom+personnel.Prenom }}</td>
                    <td>0</td>
                </tr>
            </tbody>
            </table>
    </div>
</template>

<script>
export default {
    name: "CreationEquipe",
    data() {
        return {
            matricule: '',
            fonctions: [],
            personnels: [],
            resultats: [],
            idFonction: null
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/fonctions') 
            .then(response => {
                this.fonctions = response.data;
                this.idFonction = this.fonctions[0].id;
                //this.searchPersonnels();
            })
            .catch(function (error) {
                console.error(error);
            });
        })
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
        autoComplete(){
            if(this.matricule.length > 2){
                this.resultats = [];
               // this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    if(this.idFonction != null ){
                        axios.get('/api/personnels/getMatriculeByFonction',{params: {fonction: this.idFonction,search: this.matricule}}).then(response => {
                            if(response.data.success){
                                this.resultats = response.data.personnels;
                                //alert(JSON.stringify(response.data));
                                alert(response.data);
                            }
                            else{
                                alert(response.data.message);
                            }
                        });
                    }
               // });
            }    
        },
        addPersonnel(){
            if((this.matricule!=null)&&(this.idFonction!=null)){
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.get('/api/personnels/getFirstWhere',{params: {criteres: {Fonction_actuelle: this.idFonction,Matricule: this.matricule}}}).then(response => {
                        addPersonnelToTable(response.data.personnel);
                    });
                });
            }
        },
        addPersonnelToTable(personnel){
            let exist = false
            this.personnels.forEach(element => {
                if(element.Matricule == personnel.Matricule){
                    exist = true;
                }
            });
            if(exist){
                alert(personnel.Matricule+" est deja present");
            }
            else{
                this.personnels.push(personnel);
            }
        },
        test(){
            alert(this.resultats);
        },
    }
}
</script>