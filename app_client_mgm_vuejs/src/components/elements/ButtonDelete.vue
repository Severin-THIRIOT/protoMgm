<template>
    <div>
        <button class="button" @click="deleteAction">
            <span class="icon">
                <i class="mdi mdi-delete"></i>
            </span>
        </button>
    </div>
</template>

<script>
export default {
  props: [ 'stuffToDeleteId', 'index' ],
  methods: {
    deleteAction: function () {
      this.$emit('delete-action', this.stuffToDeleteId, this.index)
    }
  }
}
</script>