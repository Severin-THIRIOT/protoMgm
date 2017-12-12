<template>
    <div class="container">

        <!--todo ajouter une colone a droite pour donner des infos supplémentaire sur un jeu? ou sur un élément-->
        <!--todo gerer la sécurité des accés par l'url entre utilisteurs-->
        <!--todo diviser en plusieurs composants-->
        <!--todo mettre le style pour le pourcentage de jeux fini-->
        <!--todo params faire des verifications sur les entré de formulaire-->
        <!--todo params dans les catégorie empecher les users de krenter plusieurs l'exact meme valeur-->
        <!--todo ajouter un loader-->

        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h1 class="title">Votre liste contient:</h1>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <button class="button is-link" @click="addItem()">Créer un nouvel item</button>
                    <a @click="redirect('addParams')">Ajouter des pramettres</a>
                </div>
            </div>
        </div>

        {{completion}} %



        <div class="modal" v-bind:class="{'is-active':alert}">
            <div @click="closeAlert()" class="modal-background"></div>
            <div class="modal-content">
                <div class="message is-dark" v-if="alert">
                    <div class="message-body">
                        <p style="font-size: 1.2em">Voulez-vous vraiment supprimer cet élément ?</p>
                        <br>
                        <div class="columns">
                            <div class="column is-two-third is-offset-5">
                                <button class="button is-link" @click="confirmRemoveItem()">Oui</button>
                                <button class="button" @click="closeAlert()">Non</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button @click="closeAlert()" class="modal-close is-large" aria-label="close"></button>
        </div>

        <div class="columns">
            <div class="column is-one-quarter" v-if="paramsList.length > 0">
                <div v-for="(param, index) in paramsList">
                    <div v-if="param.type === 'int'">
                        <label class="label">{{param.name}}</label>
                        <div v-for="int in param.paramsTypeInt">
                            <label>{{int.intValue}}</label>
                            <input @click="addFiltersAction(int.id, int.intValue,'int',int.model)" type="checkbox" v-model="int.model" :value="int.model">
                        </div>
                    </div>
                    <div  v-if="param.type === 'date'">
                        <label class="label">{{param.name}}</label>
                        <div v-for="date in param.paramsTypeDate">
                            <label>{{date.dateValue.date}}</label>
                            <input @click="addFiltersAction(date.id, date.dateValue.date, 'date',date.model)"  type="checkbox" v-model="date.model">
                        </div>
                    </div>
                    <div v-if="param.type === 'choice'">
                        <label class="label">{{param.name}}</label>
                        <div v-for="choice in param.paramsTypeChoice">
                            <input @click="addFiltersAction(choice.id, choice.name,'choice',choice.model)"  type="checkbox" v-model="choice.model">
                            <label>{{choice.name}}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column">
                <ul>
                    <li class="level box" v-for="(item, index) in items">
                        <div class="level-left">
                            <div class="media">
                                <div class="media-left">
                                    <p class="image is-64x64">
                                        <img class="image is-64x64" :src="item.img" alt="">
                                    </p>
                                </div>
                                <div class="media-content">
                                    <div class="content">
                                        <p class="listName">
                                            {{item.name}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="level-right">
                            <button class="button" @click="editItem(item.id)"><span class="icon"><i class="mdi mdi-border-color"></i></span></button>
                            <button class="button" @click="showAlert(item.id, index)"><span class="icon"><i class="mdi mdi-delete"> </i></span></button>
                            <input type="checkbox" v-model="item.completed" @click="completed(item)">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
      props: [ 'id' ],
      data () {
        return {
          user: JSON.parse(sessionStorage.getItem('user')),
          items: [],
          itemsModel: [],
          alert: false,
          itemToDeleteId: '',
          itemToDeleteIndex: '',
          completion: 0,
          paramsList: [],
          filters: []
        }
      },
      methods: {
        addItem: function () {
          this.$router.push('/addItem/' + this.id)
        },
        editItem: function (id) {
          let listId = this.id
          this.$router.push('/editItem/' + listId + '/' + id)
        },
        showAlert: function (id, index) {
          this.alert = true
          this.itemToDeleteId = id
          this.itemToDeleteIndex = index
        },
        closeAlert: function () {
          this.alert = false
          this.itemToDeleteId = ''
          this.itemToDeleteIndex = ''
        },
        confirmRemoveItem: function () {
          let url = 'http://localhost:8000/deleteItem'
          this.$http.post(url, {username: this.user.username, password: this.user.userpw, itemId: this.itemToDeleteId}).then((response) => {
            if (JSON.parse(response.body).result === 'success') {
              this.items.splice(this.itemToDeleteIndex, 1)
              this.closeAlert()
            }
//            todo gerer les failure (else)
          }, (response) => {
          })
        },
        completed: function (item) {
          let url = 'http://localhost:8000/completed'
          this.$http.post(url, {username: this.user.username, password: this.user.userpw, itemId: item.id}).then((response) => {
            this.setCompletionPercent()
          }, (response) => {
          })
        },
        getLists: function () {
          let params = '?username=' + this.user.username + '&password=' + this.user.userpw + '&userid=' + this.user.userid + '&listid=' + this.id
          let url = 'http://localhost:8000/itemsByList'
          this.$http.get(url + params).then((response) => {
//            console.log(JSON.parse(JSON.parse(response.body).items))
            this.items = JSON.parse(JSON.parse(response.body).items)
            this.itemsModel = JSON.parse(JSON.parse(response.body).items)
            this.setCompletionPercent()
          }, (response) => {
          })
        },
        getFilters: function () {
          let reqParams = '?username=' + this.user.username + '&password=' + this.user.userpw + '&listId=' + this.id
          let url = 'http://localhost:8000/getParams'
          this.$http.get(url + reqParams).then((response) => {
//            console.log(JSON.parse(JSON.parse(response.body).params))
            this.paramsList = JSON.parse(JSON.parse(response.body).params)
          }, (response) => {
            console.log(response)
          })
        },
        setCompletionPercent: function () {
          let total = this.items.length
          let completTotal = 0
          for (let i = 0; i < total; i++) {
            let item = this.items[i]
            if (item.completed === true) {
              completTotal++
            }
          }
          this.completion = (completTotal * 100) / total
        },
        addFiltersAction: function (id, value, type, inputModel) {
          if (inputModel === false || inputModel === undefined) {
            this.filters.push(type + '_' + id + '_' + value)
          } else {
            let index = this.filters.indexOf(type + '_' + id + '_' + value)
            if (index !== -1) {
              this.filters.splice(index, 1)
            }
          }
          if (this.filters.length === 0) {
            this.items = this.itemsModel
          } else {
            this.applyFilters()
          }
        },
        applyFilters: function () {
          let self = this
          let newItems = []
          this.filters.forEach(function (el) {
            let splited = el.split('_')
            let type = splited[0]
            let id = Number(splited[1])
            let value = splited[2]
            self.itemsModel.forEach(function (item) {
              let isParam = false
              if (type === 'int') {
                item.paramsTypeInt.forEach(function (e) {
                  if (e.intValue === Number(value)) {
                    isParam = true
                  }
                })
              } else if (type === 'date') {
                item.paramsTypeDate.forEach(function (e) {
                  if (e.dateValue.date === value) {
                    isParam = true
                  }
                })
              } else if (type === 'choice') {
                item.paramsTypeChoice.forEach(function (e) {
                  if (e.id === id) {
                    isParam = true
                  }
                })
              }
              if (isParam && newItems.indexOf(item) === -1) {
                newItems.push(item)
              }
            })
          })
          self.items = newItems
        },
        dateToString: function (date) {
          let d = new Date(date)
          let month = '' + (d.getMonth() + 1)
          let day = '' + d.getDate()
          let year = d.getFullYear()
          if (month.length < 2) month = '0' + month
          if (day.length < 2) day = '0' + day
          return [year, month, day].join('-')
        },
        redirect: function (route) {
          this.$router.push('/' + route + '/' + this.id)
        }
      },
      mounted: function () {
        this.getLists()
        this.getFilters()
      }
    }
</script>