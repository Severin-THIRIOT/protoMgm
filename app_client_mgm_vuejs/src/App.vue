<template>
  <div id="app">
    <section class="hero is-dark is-bold">
      <div class="hero-body">
        <nav class="level">
          <div class="level-left">
            <div class="level-item has-text-centered">
              <h1 class="title"><a @click="redirect('/')">MGM</a></h1>
            </div>
          </div>
          <div class="level-right">
            <div class="level-item">
              <logout></logout>
            </div>
          </div>
        </nav>
      </div>
    </section>
    <router-view/>
    <footer class="footer">
      <div class="container">
        <div class="content has-text-centered">
          <p>
            <strong>MGM</strong> by <a href="http://jgthms.com">Séverin Thiriot</a>
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
  import Logout from './components/elements/Logout.vue'
  export default {
    name: 'app',
    components: { Logout },
    methods: {
      redirect: function (route) {
        this.$router.push(route)
      }
    }
  }
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
.container{
  margin-top: 30px;
}
/**
 * Demo Styles
 */

html {
  height: 100%;
}

body {
  position: relative;
  margin: 0;
  padding-bottom: 6rem;
  min-height: 100%;
}


/**
 * Footer Styles
 */

.footer {
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  padding: 1rem;
  background-color: #efefef;
  text-align: center;
}
</style>
