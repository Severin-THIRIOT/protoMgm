<template>
    <div class="container">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <h1 class="title">Nouvelle liste</h1>
                <label class="label">Nom</label>
                <div class="control">
                    <input class="input" type="text" v-model="name">
                </div>
                <!--todo importer image depuis les fichiers locaux-->
                <label class="label">Image</label>
                <div class="control">
                    <input class="input" type="text" v-model="img">
                </div>
                <!--<label>Date</label>-->
                <!--<input type="date" v-model="img">-->
                <br>
                <div class="control">
                    <button class="button is-primary" @click="addList()">Ajouter</button>
                </div>

                <div class="notification is-warning" v-if="alert">
                    <p>{{alertMessage}}</p>
                </div>

            </div>
        </div>
    </div>
</template>


<script>
    export default {
      data () {
        return {
          alert: false,
          alertMesage: '',
          user: JSON.parse(sessionStorage.getItem('user')),
          name: '',
          img: ''
        }
      },
      methods: {
        addList: function () {
          let url = 'http://localhost:8000/createList'
          this.$http.post(url, {
            username: this.user.username,
            password: this.user.userpw,
            userid: this.user.userid,
            listname: this.name,
            listimg: this.img
          }).then((response) => {
            console.log(response)
            if (JSON.parse(response.body).result === 'success') {
              this.$router.push('/')
            } else {
              this.alertMessage = 'Une erreur s\'est produite lors de l\'enregistrement. Si le problème persiste merci de contacter contact@websitefr'
              this.alert = true
            }
          }, (response) => {
            this.alertMessage = 'Une erreur s\'est produite lors de l\'enregistrement. Si le problème persiste merci de contacter contact@websitefr'
            this.alert = true
          })
        }
      }
    }
</script>