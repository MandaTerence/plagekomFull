<template>
        <div class="row">
            <div class="col-6">
                <h4>{{ titre }}</h4>
            </div>
        </div>
        <div class="row">
            <table class="table table-hover">
                <thead >
                    <tr class="bg-primary" style="color:white">
                        <th scope="col-md-2">Designation</th>
                        <th scope="col-md-2 d-none">rang</th>
                        <th scope="col-md-1"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="produit in produits" v-bind:key="produit">
                        <td scope="col-md-2">{{ produit.Designation }}</td>
                        <td scope="col-md-2">0</td>
                        <td scope="col-md-1">
                            <button class="btn btn-danger" v-on:click="remove(produit.Designation)">
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
</template>

<script>
export default {
    name: "ProduitTab",
    props: {
        produits: {
            type: Array,
            required: true
        },
        titre: {
            type: String,
            default: 'Produits'
        }
    },
    created() {
      
    },
    methods: {
        remove(Designation){
            for( var i = 0; i < this.produits.length; i++){ 
                if ( Designation ==  matricule) {
                    this.produits.splice(i, 1);
                    i--; 
                }
            }
        },
        
    }
}
</script>