<template>
        <form>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputFonction">Fonction</label>
                    <select class="form-control" id="inputFonction" v-model="testVal" @change="searchPersonnels">
                        <option v-bind:key="fonction.designation" v-bind:value="fonction.id" v-for="fonction in fonctions">{{ fonction.designation }}</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputMatricule">Matricule</label>
                    <input type="text" class="form-control" id="inputMatricule" placeholder="Matricule">
                </div>
            </div>
        </form>
        <button class="btn btn-primary" v-on:click="test">Sign in</button>
</template>

<script>
export default {
    name: "CreationEquipe",
    data() {
        return {
            fonctions: [],
            personnels: [],
            resultats: [],
            criteres: {
                "fonction" : String,
                "matricule": String
            },
            testVal: null
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/fonctions')
                .then(response => {
                    this.fonctions = response.data;
                    this.testVal = this.fonctions[0].id;
                    this.searchPersonnels();
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        searchPersonnels(){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                //this.$axios.get('/api/personnels',{ params: {criteres: this.criteres}})
                this.$axios.get('/api/personnels')
                    .then(response => {
                        this.personnels = response.data;
                        alert(JSON.stringify(this.personnels[0].Matricule));
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        test(){
            alert(this.personnels);
        },
    }
}
</script>