<template>
    <div class="modal" v-bind:class="{'is-active':alert}">
        <div @click="closeAlertAction" class="modal-background"></div>
        <div class="modal-content">
            <div class="message is-dark">
                <div class="message-body">
                    <p style="font-size: 1.2em">Voulez-vous vraiment supprimer cet élément ?</p>
                    <br>
                    <div class="columns">
                        <div class="column is-two-third is-offset-5">
                            <button class="button is-link" @click="confirmDeleteAction">Oui</button>
                            <button class="button" @click="closeAlertAction">Non</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button @click="closeAlertAction" class="modal-close is-large" aria-label="close"></button>
    </div>
</template>

<script>
    export default {
      props: [ 'alert' ],
      methods: {
        closeAlertAction: function () {
          this.$emit('close-alert')
        },
        confirmDeleteAction: function () {
          this.$emit('confirm-delete')
        }
      }
    }
</script>