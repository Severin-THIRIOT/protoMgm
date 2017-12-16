<template>
    <div class="media-left">
        <p class="image is-64x64">
            <img class="image is-64x64" :src="image" alt="">
        </p>
    </div>
</template>

<script>
export default {
  props: [ 'image' ]
}
</script>