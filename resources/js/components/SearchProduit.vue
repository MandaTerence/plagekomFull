<template>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="inputProduit">Produit</label>
            <input type="text" placeholder="what are you looking for?" v-model="produit" class="form-control" v-on:keyup="autoComplete" v-on:click="autoComplete">
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
</template>
<script>
export default {
    name: "SearchPersonnel",
    data() {
        return {
            produit: '',
            isSearchingAutoComplete: false,
            resultats:'',
        }
    },
    props: {
        produits: {
            type: Array,
            required: true
        }
    },
    created() {
        
    },
    methods: {
        autoComplete(){
            if((this.matricule.length > 2)&&(!this.isSearchingAutoComplete)){
                this.isSearchingAutoComplete = true;
                if(this.idFonction != null ){
                    setTimeout(this.searchAutoComplete, 1000);
                }
            }    
        },
        searchAutoComplete(){
            this.resultats = [];
            if(this.customId == null){
                axios.get('/api/personnels/getMatriculeByFonction',{params: {fonction: this.idFonction,search: this.matricule}}).then(response => {
                    this.isSearchingAutoComplete = false;
                    if(response.data.success){
                        
                    }
                    else{
                        alert(response.data.message);
                    }
                });
            }
        },
    }
}
</script>