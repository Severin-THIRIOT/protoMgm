<template>
    <div class="media-left">
        <p class="image is-64x64">
            <img class="image is-64x64" :src="url+imageToDisplay">
        </p>
    </div>
</template>

<script>
export default {
  props: [ 'image' ],
  data () {
    return {
      url: 'http://localhost:8000/uploads/files/images/',
      imageToDisplay: this.image
    }
  },
  mounted: function () {
    if (this.image === '') {
      this.url = 'http://localhost:8000/assets/images/defaults/'
      this.imageToDisplay = 'default.png'
    }
  }
}
</script>