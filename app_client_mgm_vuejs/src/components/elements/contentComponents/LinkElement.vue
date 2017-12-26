<template>
    <div class="media" @click="goToList(list.id)">
        <image-element :image="list.img" ></image-element>
        <div class="media-content">
            <div class="content">
                <p class="listName">
                    {{list.name}}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import ImageElement from './ImageElement.vue'
export default {
  props: [ 'list', 'index' ],
  components: {
    ImageElement
  },
  methods: {
    goToList: function (list) {
      this.$router.push('/items/' + list)
    }
  }
}
</script>

<style>
    .listName {
        margin-top:10px;
        font-size: 1.4em;
    }
</style>