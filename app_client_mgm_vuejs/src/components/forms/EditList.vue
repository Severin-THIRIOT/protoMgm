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

                <div class="control">
                    <div v-if="!image">
                        <h2>Select an image</h2>
                        <input type="file" @change="onFileChange">
                    </div>
                    <div v-else>
                        <img :src="image" />
                        <button @click="removeImage">Remove image</button>
                    </div>
                </div>
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
          image: '',
          url: 'http://localhost:8000/',
          imageFile: ''
        }
      },
      methods: {
        onFileChange: function (e) {
          let files = e.target.files || e.dataTransfer.files
          if (!files.length) {
            return
          }
          this.createImage(files[0])
        },
        createImage: function (file) {
          let reader = new FileReader()
          let vm = this
          reader.onload = (e) => {
            vm.image = e.target.result
          }
          this.imageFile = file
          reader.readAsDataURL(file)
        },
        removeImage: function (e) {
          this.image = ''
        },
        editList: function () {
          console.log(this.imageFile)
          let formData = new FormData()
          formData.append('username', this.user.username)
          formData.append('password', this.user.userpw)
          formData.append('userid', this.user.userid)
          formData.append('itemListId', this.id)
          formData.append('listname', this.name)
          formData.append('listimg', this.imageFile)
          let url = this.url + 'api/updateList'
          this.$http.post(url, formData).then((response) => {
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
        let url = 'http://localhost:8000/api/listById'
        let params = '?username=' + this.user.username + '&password=' + this.user.userpw + '&listid=' + this.id
        this.$http.get(url + params).then((response) => {
          if (JSON.parse(response.body).result === 'success') {
            let list = JSON.parse(JSON.parse(response.body).list)
            this.name = list[0].name
            this.image = this.url + 'uploads/files/images/' + list[0].img
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