<template>
    of {{ matricule }} !
</template>

<script>
export default {
    name: "ControleTelephonique",
    data() {
        return {
            matricule: '',
            personnelData: [],
        }
    },
    created() {
        this.loadURLdata();
    },
    methods: {
        loadURLdata(){
            let urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('matricule')){
                this.matricule = urlParams.get('matricule');
            }
        },
        getPersonnel(){
            if(this.matricule!=''){
                axios.get('/api/personnel/getFirstWhere',{params: {matricule : this.matricule}}).then(response => {
                    this.personnelData = response.data.personnel;
                    alert(this.personnelData);
                });
            }
        }
    }
}
</script>