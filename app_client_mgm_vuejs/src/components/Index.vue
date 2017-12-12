<template>
    <div class="container">
        <div class="columns">
            <div class="column is-offset-0.5">
                <div class="container">
                    <div class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <h1 class="title">Mes listes</h1>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button class="button is-link" @click="addList()">Créer une nouvelle liste</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal" v-bind:class="{'is-active':alert}">
                    <div @click="closeAlert()" class="modal-background"></div>
                    <div class="modal-content">
                        <div class="message is-dark" v-if="alert">
                            <div class="message-body">
                                <p style="font-size: 1.2em">Voulez-vous vraiment supprimer cette liste ?</p>
                                <br>
                                <div class="columns">
                                    <div class="column is-two-third is-offset-5">
                                        <button class="button is-link" @click="confirmRemoveList()">Oui</button>
                                        <button class="button" @click="closeAlert()">Non</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button @click="closeAlert()" class="modal-close is-large" aria-label="close"></button>
                </div>


                <ul class="container">
                    <li class="level box" v-for="(list, index) in lists">
                        <div class="level-left">
                            <div class="media" @click="goToList(list.id)">
                                <div class="media-left">
                                     <p class="image is-64x64">
                                        <img class="image is-64x64" :src="list.img" alt="">
                                     </p>
                                </div>
                                <div class="media-content">
                                    <div class="content">
                                        <p class="listName">
                                            {{list.name}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="level-right">
                            <button class="button" @click="editList(list.id)"><span class="icon"><i class="mdi mdi-border-color"></i></span></button>
                            <button class="button" @click="showAlert(list.id, index)"><span class="icon"><i class="mdi mdi-delete"> </i></span></button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      data () {
        return {
          user: JSON.parse(sessionStorage.getItem('user')),
          lists: [],
          alert: false,
          listToDeleteId: '',
          listToDeleteIndex: ''
        }
      },
      methods: {
        goToList: function (list) {
          this.$router.push('/items/' + list)
        },
        addList: function () {
          this.$router.push('/addList')
        },
        editList: function (list) {
          this.$router.push('/editList/' + list)
        },
        showAlert: function (id, index) {
          this.alert = true
          this.listToDeleteId = id
          this.listToDeleteIndex = index
        },
        closeAlert: function () {
          this.alert = false
          this.listToDeleteId = ''
          this.listToDeleteIndex = ''
        },
        confirmRemoveList: function () {
          let url = 'http://localhost:8000/deleteList'
          this.$http.post(url, {username: this.user.username, password: this.user.userpw, itemListId: this.listToDeleteId}).then((response) => {
            if (JSON.parse(response.body).result === 'success') {
              this.lists.splice(this.listToDeleteIndex, 1)
              this.closeAlert()
            }
//            todo gerer les failure (else)
          }, (response) => {
          })
        }
      },
      mounted: function () {
        let params = '?username=' + this.user.username + '&password=' + this.user.userpw + '&userid=' + this.user.userid
        let url = 'http://localhost:8000/itemsListByUser'
        this.$http.get(url + params).then((response) => {
          console.log(JSON.parse(JSON.parse(response.body).list))
          this.lists = JSON.parse(JSON.parse(response.body).list)
        }, (response) => {
          console.log(response.body)
        })
      }
    }
</script>

<style>
.listName {
   margin-top:10px;
    font-size: 1.4em;
}
</style>