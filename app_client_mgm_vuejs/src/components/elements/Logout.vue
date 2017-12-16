
<template>
    <div>
        <button class="button" @click="logout()" v-if="$route.path !== '/connection' && $route.path !== '/inscription' ">Deconnexion</button>
    </div>
</template>

<script>
export default {
  name: 'Logout',
  data () {
    return {
      user: sessionStorage.getItem('user')
    }
  },
  methods: {
    logout: function () {
      sessionStorage.removeItem('user')
      this.user = sessionStorage.getItem('user')
      this.$router.push('/connection')
    }
  }
}
</script>
<style type="text/css">
    button{
        color: #494c51;
    }
</style>