<template>
    <div>
        <button class="button" @click="editList()"><span class="icon"><i class="mdi mdi-border-color"></i></span></button>
    </div>
</template>

<script>
export default {
  props: [ 'listId' ],
  methods: {
    editList: function () {
      let listId = this.listId
      this.$router.push('/editList/' + listId)
    }
  }
}
</script>