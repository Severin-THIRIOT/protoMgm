<template>
    <div>
        {{completion}} %


        <alert :alert="alert" @confirm-delete="confirmRemoveItem" @close-alert="closeAlert"></alert>

        <div class="columns">
            <div class="column is-one-fifth">
                <filters :id="id" @filter-action="addFiltersAction"></filters>
            </div>

            <div class="column">
                <ul>
                    <li class="level box" v-for="(item, index) in items">
                        <div class="level-left">
                            <div class="media">
                                <image-element :image="item.img" ></image-element>
                                <div class="media-content">
                                    <div class="content">
                                        <p class="listName">{{item.name}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="level-right">
                            <button-edit :id="item.id" @edit-action="editItem"></button-edit>
                            <button-delete :stuffToDeleteId="item.id" :index="index" @delete-action="showDeleteAlert"></button-delete>
                            <label>Terminé</label>
                            <input type="checkbox" v-model="item.completed" @click="completed(item)">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>

import ImageElement from '../elements/contentComponents/ImageElement.vue'
import ButtonEdit from '../elements/buttons/ButtonEdit.vue'
import ButtonDelete from '../elements/buttons/ButtonDelete.vue'
import Alert from '../elements/alerts/DeleteAlert.vue'
import Filters from './ItemsFilters.vue'

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
      filters: []
    }
  },
  components: {
    ImageElement,
    ButtonEdit,
    ButtonDelete,
    Alert,
    Filters
  },
  methods: {
    editItem: function (id) {
      let listId = this.id
      this.$router.push('/editItem/' + listId + '/' + id)
    },
    showDeleteAlert: function (id, index) {
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
      this.$http.delete(url, {username: this.user.username, password: this.user.userpw, itemId: this.itemToDeleteId}).then((response) => {
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
      this.$http.put(url, {username: this.user.username, password: this.user.userpw, itemId: item.id}).then((response) => {
        this.setCompletionPercent()
      }, (response) => {
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
    }
  },
  mounted: function () {
    let params = '?username=' + this.user.username + '&password=' + this.user.userpw + '&userid=' + this.user.userid + '&listid=' + this.id
    let url = 'http://localhost:8000/itemsByList'
    this.$http.get(url + params).then((response) => {
//            console.log(JSON.parse(JSON.parse(response.body).items))
      this.items = JSON.parse(JSON.parse(response.body).items)
      this.itemsModel = JSON.parse(JSON.parse(response.body).items)
      this.setCompletionPercent()
    }, (response) => {
//            todo gerer les failure (else)
    })
  }
}
</script>