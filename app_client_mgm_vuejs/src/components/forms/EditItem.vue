<template>
    <div class="container">
        <div class="columns">

            <div class="column is-half is-offset-one-quarter">

                <h1 class="title">Modifier l'élément</h1>

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
                    <input class="input" type="number" v-model="price">
                </div>

                <div v-for="(param, index) in paramsList">
                    <div v-if="param.type === 'int'">
                        <label class="label">{{param.name}}</label>
                        <input class="input" type="number" v-model="newParams[index].value">
                    </div>
                    <div  v-if="param.type === 'date'">
                        <label class="label">{{param.name}}</label>
                        <input class="input"  type="date" v-model="newParams[index].value">
                    </div>
                    <div v-if="param.type === 'choice'">
                        <label class="label">{{param.name}}</label>
                        <div v-for="choice in param.paramsTypeChoice">
                            <input type="radio" :id="choice.name" :value="choice.id"  v-model="newParams[index].value">
                            <label :for="choice.name">{{choice.name}}</label>
                        </div>
                    </div>
                </div>


                <br>
                <div class="control">
                    <button class="button is-primary" @click="editItem()">Modifier</button>
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
      props: ['listId', 'id'],
      data () {
        return {
          user: JSON.parse(sessionStorage.getItem('user')),
          alert: false,
          alertMesage: '',
          name: '',
          img: '',
          description: '',
          price: 0,
          item: '',
          paramsList: [],
          newParams: [],
          defaultParamsValue: []
        }
      },
      methods: {
        editItem: function () {
          console.log(this.newParams[0])
          console.log(this.newParams[1])
          console.log(this.newParams[2])
          let url = 'http://localhost:8000/updateItem'
          this.$http.put(url, {
            username: this.user.username,
            password: this.user.userpw,
            userid: this.user.userid,
            itemname: this.name,
            itemimg: this.img,
            itemId: this.id,
            description: this.description,
            price: this.price,
            newParams: JSON.stringify(this.newParams)
          }).then((response) => {
            console.log(response)
            if (JSON.parse(response.body).result === 'success') {
//              this.$router.push('/items/' + this.listId)
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
          let reqParams = '?username=' + this.user.username + '&password=' + this.user.userpw + '&listId=' + this.listId
          let url = 'http://localhost:8000/getParams'
          this.$http.get(url + reqParams).then((response) => {
            console.log(JSON.parse(JSON.parse(response.body).params))
            this.paramsList = JSON.parse(JSON.parse(response.body).params)
            this.createNewParams()
          }, (response) => {
            console.log(response)
          })
        },
        createNewParams: function () {
          let self = this
          let paramDate = this.item[0].paramsTypeDate
          let paramInt = this.item[0].paramsTypeInt
          let paramChoice = this.item[0].paramsTypeChoice
          for (let i = 0; i < this.paramsList.length; i++) {
            let modelValue = ''
            paramChoice.forEach(function (e) {
              if (e.Param.id === self.paramsList[i].id) {
                modelValue = e.id
              }
            })
            paramInt.forEach(function (e) {
              if (e.Param.id === self.paramsList[i].id) {
                modelValue = e.intValue
              }
            })
            paramDate.forEach(function (e) {
              if (e.Param.id === self.paramsList[i].id) {
                let d = new Date(e.dateValue.date)
                let month = '' + (d.getMonth() + 1)
                let day = '' + d.getDate()
                let year = d.getFullYear()
                if (month.length < 2) month = '0' + month
                if (day.length < 2) day = '0' + day
                let modelValueDate = [year, month, day].join('-')
                modelValue = modelValueDate
              }
            })
            let newParam = {'id': this.paramsList[i].id, 'type': this.paramsList[i].type, 'value': modelValue}
            this.newParams.push(newParam)
          }
        }
      },
      mounted: function () {
        let url = 'http://localhost:8000/itemById'
        let params = '?username=' + this.user.username + '&password=' + this.user.userpw + '&itemid=' + this.id
        this.$http.get(url + params).then((response) => {
          if (JSON.parse(response.body).result === 'success') {
            this.item = JSON.parse(JSON.parse(response.body).item)
            this.name = this.item[0].name
            this.img = this.item[0].img
            this.description = this.item[0].description
            this.price = Number(this.item[0].price)
            this.getParamsByListId()
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