<template>
    <header v-bind:style="{width: 'calc(100% + '+sideBarWidth+'px)'}">
        <div class="banniere">
            <h1>Plagekom</h1>
        </div>
        <div class="navigation">
            <nav class="d-none d-lg-block navbar navbar-expand-lg navbar-dark bg-primary">
            <!-- for logged-in user-->
            <div class="navbar-nav" v-if="isLoggedIn">
                <a class="nav-item nav-link" style="cursor: pointer;" v-on:click="flipSideBar"><span class="navbar-toggler-icon"></span></a>
                <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
                <router-link to="/books" class="nav-item nav-link">Books</router-link>
                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>
                <div class="navbar-nav ml-auto">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" style="color:white">{{ name }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- for non-logged user-->
            <div class="navbar-nav" v-else>
                <router-link to="/" class="nav-item nav-link">Home</router-link>
                <router-link to="/login" class="nav-item nav-link">login</router-link>
                <router-link to="/register" class="nav-item nav-link">Register</router-link>
                <router-link to="/about" class="nav-item nav-link">About</router-link>
            </div>
            </nav>
            <nav class="d-block d-lg-none navbar navbar-expand-lg navbar-dark bg-primary">
                <!-- for logged-in user-->
                <div class="navbar-nav" v-if="isLoggedIn">
                    <a class="nav-item nav-link" style="cursor: pointer;" v-on:click="flipSideBar"><span class="navbar-toggler-icon"></span></a>
                </div>
                <!-- for non-logged user-->
                <div class="navbar-nav" v-else>
                    <router-link to="/login" class="nav-item nav-link">login</router-link>
                </div>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <div id="mySidebar" class="sidebar" v-bind:style="{ width: sideBarWidth + 'px'}">
                <a href="javascript:void(0)" class="closebtn" v-on:click="flipSideBar">&times;</a>
                <router-link to="/" class="nav-item nav-link"><div v-on:click="flipSideBar">Acceuil</div></router-link>
                <button class="dropdown-btn" v-on:click="dropdown(0)">Controle telephonique</button>
                <div class="dropdown-container">
                    <router-link to="/dashboard" class="nav-item nav-link sub-category" v-bind:style="{ display: dropdownListDisplay[0] }"><div v-on:click="flipSideBar"> -Controle</div></router-link>
                    <router-link to="/books" class="nav-item nav-link sub-category" v-bind:style="{ display: dropdownListDisplay[0] }"><div v-on:click="flipSideBar"> -Etat Controle du mois</div></router-link>
                    <router-link to="/" class="nav-item nav-link sub-category" v-bind:style="{ display: dropdownListDisplay[0] }"><div v-on:click="flipSideBar"> -Calendrier de controle</div></router-link>
                    <router-link to="/" class="nav-item nav-link sub-category" v-bind:style="{ display: dropdownListDisplay[0] }"><div v-on:click="flipSideBar"> -Fiche commerciale</div></router-link>
                </div>
                <button class="dropdown-btn" v-on:click="dropdown(1)">Planning et Accompagnement</button>
                <div class="dropdown-container">
                    <router-link to="/creationEquipe" class="nav-item nav-link sub-category" v-bind:style="{ display: dropdownListDisplay[1] }"><div v-on:click="flipSideBar" > -Génerer planning d accompagnement</div></router-link>
                    <router-link to="/dashboard" class="nav-item nav-link sub-category" v-bind:style="{ display: dropdownListDisplay[1] }"><div v-on:click="flipSideBar" > -Classification commerciaux</div></router-link>
                </div>
                <button class="dropdown-btn" v-on:click="dropdown(2)">Salaire</button>
                <div class="dropdown-container">
                    <router-link to="/dashboard" class="nav-item nav-link sub-category" v-bind:style="{ display: dropdownListDisplay[2] }"><div v-on:click="flipSideBar" > -Salaire du mois</div></router-link>
                </div>
                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Déconnexion</a>
            </div>
            <div id="main" class="col-md-12" v-bind:style="{'margin-left': sideBarWidth + 'px'}">
                <router-view/>
            </div>
        </div>
        <footer>
        </footer>
    </div>
</template>

<script>

export default {
    name: "App",
    data() {
        return {
            name: null,
            isLoggedIn: false,
            displayMenu: true,
            sideBarWidth: 0,
            dropdownListDisplay: ["none","none","none"]
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
        }
        if (window.Laravel.user) {
            this.name = window.Laravel.user.Nom;
        }
    },
    methods: {
        logout(e) {
            console.log('ss')
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/login"
                        } else {
                            console.log(response)
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        flipSideBar(){
            //this.sideBarWidth == 0 ?  this.sideBarWidth = 250: this.sideBarWidth = 0;
            if(this.sideBarWidth == 0){
                this.sideBarWidth = 250;
            }
            else{
                this.sideBarWidth = 0;
            }
        },
        dropdown(i) {
            //this.dropdownListDisplay[i] == "none" ? this.dropdownListDisplay[i] = "block":this.dropdownListDisplay[i] = "block";
            if(this.dropdownListDisplay[i] == "none"){
                this.dropdownListDisplay[i] = "block";
            }
            else{
                this.dropdownListDisplay[i] = "none";
            }
        }
    },
}
</script>