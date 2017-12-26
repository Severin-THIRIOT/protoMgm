<template>
    <div v-if="paramsList.length > 0">
        <div v-for="(param, index) in paramsList">
            <div v-if="param.type === 'int'">
                <label class="label">{{param.name}}</label>
                <div v-for="int in param.paramsTypeInt">
                    <input @click="emitFilter(int.id, int.intValue,'int',int.model)" type="checkbox" v-model="int.model" :value="int.model">
                    <label>{{int.intValue}}</label>
                </div>
            </div>
            <div  v-if="param.type === 'date'">
                <label class="label">{{param.name}}</label>
                <div v-for="date in param.paramsTypeDate">
                    <input @click="emitFilter(date.id, date.dateValue.date, 'date',date.model)"  type="checkbox" v-model="date.model">
                    <label>{{date.dateValue.display}}</label>
                </div>
            </div>
            <div v-if="param.type === 'choice'">
                <label class="label">{{param.name}}</label>
                <div v-for="choice in param.paramsTypeChoice">
                    <input @click="emitFilter(choice.id, choice.name,'choice',choice.model)"  type="checkbox" v-model="choice.model">
                    <label>{{choice.name}}</label>
                </div>
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
      paramsList: []
    }
  },
  methods: {
    getFilters: function () {
      let reqParams = '?username=' + this.user.username + '&password=' + this.user.userpw + '&listId=' + this.id
      let url = 'http://localhost:8000/getParams'
      this.$http.get(url + reqParams).then((response) => {
        let params = JSON.parse(JSON.parse(response.body).params)
        params = this.transformDate(params)
        this.paramsList = params
      }, (response) => {
        console.log(response)
      })
    },
    transformDate: function (params) {
      let self = this
       params.forEach( function (param) {
        if (param.type === "date") {
           param.paramsTypeDate = self.getDatesToFormat(param.paramsTypeDate)
        }
      })
      return params
    },
    getDatesToFormat: function (paramsTypeDate) {
      let self = this
       paramsTypeDate.forEach( function (date) {
         date.dateValue = self.formatDateToString(date.dateValue)
      })
      return paramsTypeDate
    },
    formatDateToString: function (date) {
      console.log(date.date)
      let monthNames = [
        'Jan', 'Fév', 'Mar',
        'Avr', 'Mai', 'Jun', 'Jul',
        'Aou', 'Sep', 'Oct',
        'Nov', 'Déc'
      ];
      let newDate = new Date(date.date)
      let day = newDate.getDate();
      let monthIndex = newDate.getMonth();
      let year = newDate.getFullYear();

      date.display = day + ' ' + monthNames[monthIndex] + ' ' + year
      return date
    },
    emitFilter: function (id, name, type, model) {
      this.$emit('filter-action', id, name, type, model)
    }
  },
  mounted: function () {
    this.getFilters()
  }
}
</script>