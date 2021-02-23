<template>
    <header>
        <div class="banniere">
            <h1>Plagekom</h1>
        </div>
        <div class="navigation">
            <nav class="d-none d-lg-block navbar navbar-expand-lg navbar-dark bg-primary">
            <!-- for logged-in user-->
            <div class="navbar-nav" v-if="isLoggedIn">
                <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
                <router-link to="/books" class="nav-item nav-link">Books</router-link>
                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>
                <a class="nav-item nav-link" style="cursor: pointer;" v-on:click="flipMenu">menu</a>
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
                    <a class="nav-item nav-link" style="cursor: pointer;" v-on:click="flipMenu">menu</a>
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
            
        </div>
        <div class="row">
            <div id="mySidebar" class="sidebar">
                <a href="javascript:void(0)" class="closebtn" v-on:click="flipMenu">&times;</a>
                <router-link to="/" class="nav-item nav-link" >Acceuil</router-link>
                <router-link to="/about" class="nav-item nav-link" >Controle telephonique</router-link>
                <button class="dropdown-btn">Dropdown<i class="fa fa-caret-down"></i></button>
                <div class="dropdown-container" v-on:click="dropdown">
                    <a href="#">Link 1</a>
                    <a href="#">Link 2</a>
                    <a href="#">Link 3</a>
                </div>
                <router-link to="/" class="nav-item nav-link" >Planning et accompagnement</router-link>
                <router-link to="/about" class="nav-item nav-link" >salaire</router-link>
                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>
            </div>
            <div id="main" class="col-md-12">
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
            isLoggedIn: false,
            displayMenu: true
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
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
        flipMenu(){
            if(this.displayMenu){
                
                this.closeNav();

                this.displayMenu = false;
            }
            else{
                this.openNav();
                this.displayMenu = true;
            }
        },
        openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        },
        closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        },
        dropdown() {
            this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
                } else {
                dropdownContent.style.display = "block";
            }
           /* var dropdown = document.getElementsByClassName("dropdown-btn");
            var i;

            for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
                } else {
                dropdownContent.style.display = "block";
                }
            });
            }*/
        }
    },
}
</script>
<style>
.dropdown-btn {
  text-decoration: none;
  display: block;
  border: none;
  background: none;
  outline: none;
  padding: 8px 8px 8px 32px;
  font-size: 15px;
  color: #cacaca;
  transition: 0.3s;
}
.dropdown-btn:hover {
  color: #ffffff;
}
.navigation {
    margin-top: -10px;
    padding-top: 0px;
}
.banniere {
    text-align: center;
    padding-top: 10px;
    font-size: 20px;
    color: rgb(255, 255, 255);
    background-color: #3490DC;
}

 /* The sidebar menu */
.sidebar {
  height: 100%; /* 100% Full-height */
  width: 0; /* 0 width - change this with JavaScript */
  position: fixed; /* Stay in place */
  z-index: 1; /* Stay on top */
  top: 0;
  left: 0;
  background-color: #3490DC; /* Black*/
  overflow-x: hidden; /* Disable horizontal scroll */
  padding-top: 60px; /* Place content 60px from the top */
  transition: 0.5s; /* 0.5 second transition effect to slide in the sidebar */
}

/* The sidebar links */
.sidebar a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 15px;
  color: #cacaca;
  display: block;
  transition: 0.3s;
}

/* When you mouse over the navigation links, change their color */
.sidebar a:hover {
  color: #ffffff;
}

/* Position and style the close button (top right corner) */
.sidebar .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

/* The button used to open the sidebar */
.openbtn {
  font-size: 20px;
  cursor: pointer;
  background-color: #111;
  color: white;
  padding: 10px 15px;
  border: none;
}

.openbtn:hover {
  background-color: #444;
}

/* Style page content - use this if you want to push the page content to the right when you open the side navigation */
#main {
  transition: margin-left .5s; /* If you want a transition effect */
  padding: 20px;
}

/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
@media screen and (max-height: 450px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
} 

</style>