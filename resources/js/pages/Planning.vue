<template>
    <div class="row">
        <div class="form-row">
            <div class="form-group">
                <label for="inputMission">Mission</label>
                <select class="form-control" id="inputMission" v-model="idMission" v-on:change="reloadPlannig();">
                    <option v-bind:key="mission.Id_de_la_mission" v-bind:value="mission.Id_de_la_mission" v-for="mission in missions">{{ mission.Id_de_la_mission }}</option>
                </select>
            </div>
        </div>
    </div>
    <div>
        <div v-for="tabPlannings in plannings" v-bind:key="tabPlannings">
            <h1 v-if="tabPlannings.length>0">{{ tabPlannings[0].Coach  }}</h1>
            <div style="margin-top:50px" v-for="planning in tabPlannings" v-bind:key="planning">
                <div class="row">
                    <div class="col-3">
                        <h3>{{ planning['jour'] }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h3>Matin</h3>
                        <table class="table" >
                            <tr class="bg-primary" style="color:white">
                                <td> HEURE </td>
                                <td> MATRICULE </td>                    
                            </tr>
                            <tr>
                                <td> {{ formatHeure(planning.matin[0].Heure_debut) }} à {{ formatHeure(planning.matin[0].Heure_fin) }}  </td>
                                <td> {{ planning.matin[0].Commercial }} </td>                    
                            </tr>
                            <tr>
                                <td> {{ formatHeure(planning.matin[1].Heure_debut) }} à {{ formatHeure(planning.matin[1].Heure_fin) }}  </td>
                                <td> {{ planning.matin[1].Commercial }} </td>                    
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h3>Apres Midi</h3>
                        <table class="table">
                            <tr class="bg-primary" style="color:white">
                                <td> HEURE </td>
                                <td> MATRICULE </td>                    
                            </tr>
                            <tr>
                                <td> {{ formatHeure(planning.apresMidi[0].Heure_debut) }} à {{ formatHeure(planning.apresMidi[0].Heure_fin) }}  </td>
                                <td> {{ planning.apresMidi[0].Commercial }} </td>                    
                            </tr>
                            <tr>
                                <td> {{ formatHeure(planning.apresMidi[1].Heure_debut) }} à {{ formatHeure(planning.apresMidi[1].Heure_fin) }}  </td>
                                <td> {{ planning.apresMidi[1].Commercial }} </td>                    
                            </tr>
                        </table>
                    </div>
                </div>  
                <hr style="background-color: #3490dc;">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Planning",
    data() {
        return {
            missions: [],
            idMission: '',
            plannings: [],
            coach: 'all',
            isSearchingAutoComplete: false
        }
    },
    created() {
        let urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('idMission')){
            this.idMission = urlParams.get('idMission');
        }
        if(urlParams.get('coach'))
            this.coach = urlParams.get('coach');
        this.loadMissions();
        this.loadPlannig();
    },
    methods: {
        loadMissions(){
            //this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get('/api/missions',{params: {criteres: {Statut: 'En_cours'}}}) 
                .then(response => {
                    if(response.data.success){
                        //alert(response.message);
                        this.missions = response.data.data;
                        //this.idMission = this.missions[0].Id_de_la_mission;
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
        loadPlannig(){
            if((this.idMission!=null)&&(this.idMission!='')&&(this.coach!=null)&&(this.coach!='')){
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.get('/api/classements/planning',{params: {idMission : this.idMission,coach : this.coach}}).then(response => {
                        this.plannings = response.data.data;
                    });  
                });
            }
        },
        getPeriodeJour(heure){
            if(this.hourToTime(heure)<this.hourToTime('12:00:00')){
                return 'MATIN';
            }
            else{
                return 'APRES MIDI';
            }
        },
        formatHeure(heure){
            let arr = heure.split(':')
            return ''+(parseInt(arr[0])<10? '0'+parseInt(arr[0]): parseInt(arr[0]))+'h '+(parseInt(arr[1])<10? '0'+parseInt(arr[1]): parseInt(arr[1]));
        },
        searchAutoComplete(){
            //this.missions = [];
            this.$axios.get('/api/missions',{params: {criteres: {Statut: 'En_cours',Id_de_la_mission: '%'+this.idMission+'%'}}}).then(response => {
                this.isSearchingAutoComplete = false;
                for(let i=0;i<this.missions.length;i++){
                    this.missions.pop();
                }
                for(let i=0;i<response.data.data.length;i++){
                    this.missions.push(response.data.data[i].Id_de_la_mission);
                }
            });
        },
        autoComplete(){
            if((this.idMission.length > 2)&&(!this.isSearchingAutoComplete)){
                this.isSearchingAutoComplete = true;
                if(this.idMission != null ){
                    setTimeout(this.searchAutoComplete, 1000);
                }
            }  
        },
        reloadPlannig(){
            this.coach = 'all',
            this.loadPlannig();
        },
    }
}
</script>