<template>
    <div class="container">
        <div class="columns">
            <div class="column is-offset-0.5">
                <div class="container">
                    <div class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <h1 class="title">Mes listes</h1>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button class="button is-link" @click="addList()">Créer une nouvelle liste</button>
                            </div>
                        </div>
                    </div>
                </div>

                <list></list>

            </div>
        </div>
    </div>
</template>

<script>
    import List from './elements/List.vue'
    export default {
      components: {
        List
      },
      methods: {
        addList: function () {
          this.$router.push('/addList')
        }
      }
    }
</script>
