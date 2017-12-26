<template>
    <div>
        <button class="button" @click="edit()"><span class="icon"><i class="mdi mdi-border-color"></i></span></button>
    </div>
</template>

<script>
export default {
  props: [ 'id' ],
  methods: {
    edit: function () {
      this.$emit('edit-action', this.id)
    }
  }
}
</script>