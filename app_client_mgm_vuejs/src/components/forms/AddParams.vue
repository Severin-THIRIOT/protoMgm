<template>
    <div class="container">
        <div id="show" class="columns" v-if="!addParamsMode">
            <div class="column">
                <ul>
                    <li v-for="(param, index) in paramsList">
                        {{param.name}}
                    </li>
                </ul>

                <div>
                    <a href="#" @click="changeMode()">Ajouter</a>
                </div>
            </div>
        </div>
        <div id="add" class="columns" v-if="addParamsMode">
            <div class="column">
                <div>

                    <div>
                        <label class="label">Nom du nouveau paramettre</label>
                        <input type="text" v-model="paramName">
                    </div>

                    <input type="radio" id="date" value="date" v-model="paramTypePicked">
                    <label for="date">Une date</label>
                    <br>
                    <input type="radio" id="choice" value="choice" v-model="paramTypePicked">
                    <label for="choice">Une catégorie</label>
                    <br>
                    <input type="radio" id="int" value="int" v-model="paramTypePicked">
                    <label for="int">Une valeure numerique</label>

                </div>

                <div v-if="paramTypePicked === 'choice'">
                    <div class="control">
                        <label class="label">Nom des catégories</label>
                        <div v-for="choice in choices">
                            <input type="text"  v-model="choice.inputValue">
                        </div>

                    </div>

                    <button @click="addChoice()"  class="button">Ajouter un champ</button>
                    <button @click="removeChoice()"  class="button">Retirer un champ</button>

                    <button @click="saveParam()" class="button">Valider</button>
                </div>

                <div v-if="paramTypePicked === 'date'">
                    <div>
                        <label class="label">Date min</label>
                        <input type="date" v-model="dateB">
                    </div>
                    <div>
                        <label class="label">Date max</label>
                        <input type="date" v-model="dateE">
                    </div>
                    <button @click="saveParam()" class="button">Valider</button>
                </div>

                <div v-if="paramTypePicked === 'int'">
                    <div>
                        <label class="label">Valeur Min</label>
                        <input type="text" v-model="intMin">
                    </div>
                    <div>
                        <label class="label">Valeur Min</label>
                        <input type="text" v-model="intMax">
                    </div>
                    <button @click="saveParam()" class="button">Valider</button>
                </div>


                <div class="container">
                    <a href="#" @click="changeMode()">Mes paramettres</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      props: ['listId'],
      data () {
        return {
          addParamsMode: true,
          user: JSON.parse(sessionStorage.getItem('user')),
          paramsList: [],
          paramTypePicked: '',
          paramName: 'test',
          choices: [{'inputValue': ''}, {'inputValue': ''}],
          dateB: '',
          dateE: '',
          intMin: '',
          intMax: ''
        }
      },
      methods: {
        saveParam: function () {
          console.log(this.dateB)
          console.log(this.dateE)

          let url = 'http://localhost:8000/addParams'
          this.$http.post(url, {
            username: this.user.username,
            password: this.user.userpw,
            listId: this.listId,
            paramName: this.paramName,
            paramType: this.paramTypePicked,
            dateB: this.dateB,
            dateE: this.dateE,
            intMin: this.intMin,
            intMax: this.intMax,
            choices: JSON.stringify(this.choices)
          }).then((response) => {
            console.log(response.body)
          }, (response) => {
            console.log(response)
          })
        },
        addChoice: function () {
          this.choices.push({'inputValue': ''})
        },
        removeChoice: function () {
          this.choices.splice(this.choices.length - 1, 1)
        },
        changeMode: function () {
          if (this.addParamsMode) {
            this.addParamsMode = false
          } else {
            this.addParamsMode = true
          }
        }
      },
      mounted: function () {
        let reqParams = '?username=' + this.user.username + '&password=' + this.user.userpw + '&listId=' + this.listId
        let url = 'http://localhost:8000/getParams'
        this.$http.get(url + reqParams).then((response) => {
          this.paramsList = JSON.parse(JSON.parse(response.body).params)
        }, (response) => {
          console.log(response)
        })
      }
    }
</script>