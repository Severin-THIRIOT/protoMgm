<template>
    <div>
        <ul class="container">
            <li class="level box" v-for="(list, index) in lists">
                <div class="level-left">
                    <link-element :list="list" :key="index"></link-element>
                </div>
                <div class="level-right">
                    <button-edit :id="list.id" @edit-action="editList"></button-edit>
                    <button-delete :stuffToDeleteId="list.id" :index="index" @delete-action="showDeleteAlert"></button-delete>
                </div>
            </li>
        </ul>

        <alert :alert="alert" @confirm-delete="confirmRemoveList" @close-alert="closeAlert"></alert>
    </div>
</template>

<script>
import LinkElement from '../elements/contentComponents/LinkElement.vue'
import ButtonEdit from '../elements/buttons/ButtonEdit.vue'
import ButtonDelete from '../elements/buttons/ButtonDelete.vue'
import Alert from '../elements/alerts/DeleteAlert.vue'

export default {
  data () {
    return {
      user: JSON.parse(sessionStorage.getItem('user')),
      lists: [],
      alert: false,
      toDeleteId: '',
      toDeleteIndex: ''
    }
  },
  components: {
    LinkElement,
    ButtonEdit,
    ButtonDelete,
    Alert
  },
  methods: {
    editList: function (id) {
      this.$router.push('/editList/' + id)
    },
    showDeleteAlert: function (id, index) {
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
      this.$http.delete(url, {username: this.user.username, password: this.user.userpw, itemListId: this.listToDeleteId}).then((response) => {
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
