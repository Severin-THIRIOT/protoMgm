<template>
    <div class="container">

        <!--todo ajouter une ligne pour donner des infos supplémentaire sur un jeu? ou sur un élément-->
        <!--todo gerer la sécurité des accés par l'url entre utilisteurs-->
        <!--todo mettre le style pour le pourcentage de jeux fini et pour les ajout de prametres-->
        <!--todo params faire des verifications sur les entré de formulaire-->
        <!--todo ajouter un loader grace a bulma css-->

        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h1 class="title">Votre liste contient:</h1>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <button class="button is-link" @click="addItem()">Créer un nouvel item</button>
                    <a id="settingsIcon" @click="redirect('addParams')"><img src="../assets/settings.png" alt="parametres" height="45" width="45"></a>
                </div>
            </div>
        </div>

        <itemsList :id="id"></itemsList>

    </div>
</template>


<script>
    import ItemsList from './containers/ItemsList.vue'
    export default {
      props: [ 'id' ],
      data () {
        return {
          user: JSON.parse(sessionStorage.getItem('user'))
        }
      },
      components: {
        ItemsList
      },
      methods: {
        addItem: function () {
          this.$router.push('/addItem/' + this.id)
        },
        redirect: function (route) {
          this.$router.push('/' + route + '/' + this.id)
        }
      }
    }
</script>

<style type="text/css">
    #settingsIcon{
        margin-left:5%;
    }
</style>