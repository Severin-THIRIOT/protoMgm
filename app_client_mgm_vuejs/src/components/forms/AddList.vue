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
          image: '',
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
        addList: function () {
          let formData = new FormData()
          formData.append('username', this.user.username)
          formData.append('password', this.user.userpw)
          formData.append('userid', this.user.userid)
          formData.append('itemListId', this.id)
          formData.append('listname', this.name)
          formData.append('listimg', this.imageFile)
          let url = 'http://localhost:8000/api/createList'
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
      }
    }
</script>