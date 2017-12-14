<template>
    <!--todo sur tout les forms verifier les users entry (0 caracteres 2000 caracteres....)-->
    <div class="container">
        <div class="columns">

            <div class="column is-half is-offset-one-quarter">

                <h1 class="title">Créer un nouvel élément</h1>

                <div class="control">
                    <label class="label">Nom</label>
                    <input class="input" type="text" v-model="name">
                </div>

                <div class="control">
                    <label class="label" >Image</label>
                    <input class="input" type="text" v-model="img">
                </div>

                <!--<label>Date</label>-->
                <!--<input type="date" v-model="date">-->

                <div class="control">
                    <label class="label">Description</label>
                    <input class="input" type="text" v-model="description">
                </div>

                <div class="control">
                    <label class="label">Prix</label>
                    <input class="input" type="number" v-model="prix">
                </div>

                <div v-for="(param, index) in paramsList">
                    <div v-if="param.type === 'int'">
                        <label class="label">{{param.name}}</label>
                        <input type="number" v-model="newParams[index].value">
                    </div>
                    <div  v-if="param.type === 'date'">
                        <label class="label">{{param.name}}</label>
                        <input type="date" v-model="newParams[index].value">
                    </div>
                    <div v-if="param.type === 'choice'">
                        <label class="label">{{param.name}}</label>
                        <div v-for="choice in param.paramsTypeChoice">
                            <input type="radio" :id="choice.name"  :value="choice.id" v-model="newParams[index].value">
                            <label :for="choice.name">{{choice.name}}</label>
                        </div>
                    </div>
                </div>

                <br>
                <div class="control">
                    <button class="button is-primary" @click="addItem()">Ajouter</button>
                </div>

                <div class="notification is-warning" v-if="alert">
                    <p>{{alertMessage}}</p>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import Logout from '../elements/Logout.vue'
  export default {
    props: [ 'id' ],
    data () {
      return {
        user: JSON.parse(sessionStorage.getItem('user')),
        alert: false,
        alertMesage: '',
        name: '',
        img: '',
        description: '',
        prix: 0,
        paramsList: [],
        newParams: []
      }
    },
    methods: {
      addItem: function () {
        console.log(this.newParams[0])
        let url = 'http://localhost:8000/addItem'
        this.$http.post(url, {
          username: this.user.username,
          password: this.user.userpw,
          userid: this.user.userid,
          itemname: this.name,
          itemimg: this.img,
          itemListId: this.id,
          description: this.description,
          price: this.prix,
          newParams: JSON.stringify(this.newParams)
        }).then((response) => {
          console.log(response.body)
          if (JSON.parse(response.body).result === 'success') {
            this.$router.push('/items/' + this.id)
          } else {
            this.alertMessage = 'Une erreur s\'est produite lors de l\'enregistrement. Si le problème persiste merci de contacter contact@websitefr'
            this.alert = true
          }
        }, (response) => {
          this.alertMessage = 'Une erreur s\'est produite lors de l\'enregistrement. Si le problème persiste merci de contacter contact@websitefr'
          this.alert = true
        })
      },
      getParamsByListId: function () {
        let reqParams = '?username=' + this.user.username + '&password=' + this.user.userpw + '&listId=' + this.id
        let url = 'http://localhost:8000/getParams'
        this.$http.get(url + reqParams).then((response) => {
          console.log(JSON.parse(JSON.parse(response.body).params))
          this.paramsList = JSON.parse(JSON.parse(response.body).params)
          this.createNewParams()
          console.log(this.newParams)
        }, (response) => {
          console.log(response)
        })
      },
      createNewParams: function () {
        for (let i = 0; i < this.paramsList.length; i++) {
          let newParam = {'id': this.paramsList[i].id, 'type': this.paramsList[i].type, 'value': ''}
          this.newParams.push(newParam)
        }
      }
    },
    mounted: function () {
      this.getParamsByListId()
    }
  }
</script>