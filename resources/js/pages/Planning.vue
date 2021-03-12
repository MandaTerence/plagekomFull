<template>
    <div class="form-row">
        
    </div>
</template>

<script>
export default {
    name: "Planning",
    data() {
        return {
            Accompagnement: [],
        }
    },
    created() {
        this.loadAccompagnement();
    },
    methods: {
      loadAccompagnement(){
        axios.get('/api/personnels/getMatriculeByFonction',{params: {fonction: this.idFonction,search: this.matricule}}).then(response => {
            this.isSearchingAutoComplete = false;
            if(response.data.success){
                this.resultats = response.data.personnels;
            }
            else{
                alert(response.data.message);
            }
        });
      }
    }
}
</script>