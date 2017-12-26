<template>
    <div class="container">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <h1 class="title">Modifier liste</h1>
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
                    <button class="button is-primary" @click="editList()">Ajouter</button>
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
      props: ['id'],
      data () {
        return {
          user: JSON.parse(sessionStorage.getItem('user')),
          alert: false,
          alertMesage: '',
          name: '',
          img: ''
        }
      },
      methods: {
        editList: function () {
          let url = 'http://localhost:8000/updateList'
          this.$http.put(url, {
            username: this.user.username,
            password: this.user.userpw,
            userid: this.user.userid,
            itemListId: this.id,
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
      },
      mounted: function () {
        let url = 'http://localhost:8000/listById'
        let params = '?username=' + this.user.username + '&password=' + this.user.userpw + '&listid=' + this.id
        this.$http.get(url + params).then((response) => {
          if (JSON.parse(response.body).result === 'success') {
            let list = JSON.parse(JSON.parse(response.body).list)
            this.name = list[0].name
            this.img = list[0].img
          } else {
            this.alertMessage = 'Une erreur s\'est produite lors de la recuperation des données. Si le problème persiste merci de contacter contact@websitefr'
            this.alert = true
          }
        }, (response) => {
          this.alertMessage = 'Une erreur s\'est produite lors de la recuperation des données. Si le problème persiste merci de contacter contact@websitefr'
          this.alert = true
        })
      }
    }
</script>