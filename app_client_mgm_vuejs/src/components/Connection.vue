<template>
    <div class="container" v-on:keypress.enter="submit()">
        <div class="columns" v-if="!inscription">
            <div class="column is-half is-offset-one-quarter">
                <h1 class="title">Se connecter</h1>
                <div class="field">
                    <label class="label">Username</label>
                    <div class="control">
                        <input class="input" type="text" v-model="username">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <div class="control">
                        <input class="input" type="password" v-model="password">
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button class="button is-primary" @click="login()">Se connecter</button>
                    </div>
                </div>

                <div class="notification is-warning" v-if="alert">
                    <p>{{alertMessage}}</p>
                </div>

                <div class="container">
                    <span>
                        <a href="#" @click="inscriptionMode()">S'inscrire</a>
                    </span>
                </div>

            </div>
        </div>
        <div class="columns" v-else>
            <div class="column is-half is-offset-one-quarter">
                <h1 class="title">S'inscrire</h1>
                <div class="field">
                    <label class="label">Username</label>
                    <div class="control">
                        <input class="input" type="text" v-model="username">
                    </div>
                </div>
                <div class="field">
                    <label class="label">E-mail</label>
                    <div class="control">
                        <input class="input" type="text" v-model="email">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <div class="control">
                        <input class="input" type="password" v-model="password">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Verification password</label>
                    <div class="control">
                        <input class="input" type="password" v-model="passwordBis">
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button class="button is-primary" @click="register()">S'inscrire</button>
                    </div>
                </div>

                <div class="notification is-warning" v-if="alert">
                    <p>{{alertMessage}}</p>
                </div>

                <div class="container">
                        <a href="#" @click="connectionMode()">Se connecter</a>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
      name: 'Connexion',
      data () {
        return {
          alert: false,
          alertMessage: '',
          inscription: false,
          email: 'test@gmail.com',
          username: 'kambei312@hotmail.com',
          password: 'test1234',
          passwordBis: ''
        }
      },
      methods: {
        login: function () {
          this.$http.post('http://localhost:8000/api/connect', { username: this.username, password: this.password }).then((response) => {
            let receivedData = response.body
            console.log(JSON.parse(receivedData))
            if (JSON.parse(receivedData).result === 'success') {
              sessionStorage.setItem('user', response.body)
              this.$router.push('/')
            } else {
              this.alertMessage = 'L\'identifiant ou le mot de passe saisi est erroné'
              this.alert = true
            }
          }, (response) => {
            this.alertMessage = 'Une erreur s\'est produite lors de l\'identification. Si le problème persiste merci de contacter contact@websitefr'
            this.alert = true
          })
        },
        register: function () {
          if (this.password !== this.passwordBis) {
            this.alertMessage = 'Les 2 passwords sont différents'
            this.alert = true
          } else {
            this.$http.post('http://localhost:8000/api/create', { username: this.username, password: this.password, email: this.email }).then((response) => {
              let receivedData = response.body
              console.log(JSON.parse(receivedData).result)
              if (JSON.parse(receivedData).result === 'success') {
                sessionStorage.setItem('user', response.body)
                this.$router.push('/')
              } else {
                this.alertMessage = 'Le nom d\'ulisateur est déjà utilisé'
                this.alert = true
              }
            }, (response) => {
              this.alertMessage = 'Une erreur s\'est produite lors de l\'identification. Si le problème persiste merci de contacter contact@websitefr'
              this.alert = true
            })
          }
        },
        connectionMode: function () {
          this.inscription = false
        },
        inscriptionMode: function () {
          this.inscription = true
        },
        submit: function () {
          if (!this.inscription) {
            this.login()
          } else {
            this.register()
          }
        }
      }
    }
</script>