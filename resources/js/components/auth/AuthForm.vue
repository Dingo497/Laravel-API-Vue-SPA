<template>
  <div class="container">
    <div id="progress" :style="{width: progress}"></div>
    <h1 :class="{'show-final': showFinalMessage}">{{ responseMessage }}</h1>

    <RegisterForm
      v-if="visible"
      @set-progress="progress = $event" 
      @show-final-register="register($event)" 
      @show-login="visible = false"
    />

    <LoginForm
      v-if="!visible"
      @show-register="visible = true"
      @show-final-login="login($event)"
      @set-progress="progress = $event" 
    />

  </div>
</template>



<script>
import RegisterForm from './RegisterForm.vue';
import LoginForm from './LoginForm.vue';

export default {
  // COMPONENTS
  components: {
    RegisterForm,
    LoginForm,
  },


  // DATAS
  data: () => {
    return {
      progress: '0%',
      showFinalMessage: false,
      responseMessage: '',
      userLoginData: {},
      visible: true,
    }
  },


  // METHODS
  methods: {
    /**
     * Send Data to API, register new user and "redirect" to lists
     */
    register(data) {
      axios
        .post('http://127.0.0.1:8000/api/register'+
            '?name=' + data.username +
            '&email=' + data.email + 
            '&password=' + data.password)
          .then((response) => {
            this.showFinalMessage = data.visible
            this.responseMessage = response.data.status.message + ', ' + data.username
            setTimeout(() => {
              this.$emit('auth-successfully', response.data.body)
            }, 3000);
          })
          .catch((error) => {
            this.showFinalMessage = data.visible
            this.responseMessage = error.response.data.status.message
            setTimeout(() => {
              this.showFinalMessage = false
              this.visible = true
            }, 3000);
          })
    },

    /**
     * Send Data to API, login user and "redirect" to lists
     */
    login(data) {
      axios
        .post('http://127.0.0.1:8000/api/login'+
            '?email=' + data.email + 
            '&password=' + data.password)
          .then((response) => {
            this.showFinalMessage = data.visible
            this.responseMessage = response.data.status.message + ', ' + data.email
            setTimeout(() => {
              this.$emit('auth-successfully', response.data.body)
            }, 3000);
          })
          .catch((error) => {
            this.showFinalMessage = data.visible
            this.responseMessage = error.response.data.status.message
            setTimeout(() => {
              this.showFinalMessage = false
              this.visible = false
            }, 3000);
          })
    },
  },
  
}
</script>



<style scoped>
@import url('https://use.fontawesome.com/releases/v5.8.2/css/all.css');

  .container {
    position: relative;
    font-family: 'Noto Sans', sans-serif;
    font-size: 1rem;
    color: #333;
    width: 100%;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: radial-gradient(#009345, #106B4E);
  }

  .container #progress {
    position: absolute;
    top: 0;
    left: 0;
    width: 0%;
    height: 100vh;
    background-color: #106B4E;
    transition: width 0.8s ease-in-out;
  }

  h1 {
    position: absolute;
    width: max-content;
    font-size: 2rem;
    color: #fff;
    opacity: 0;
    transition: 0.8s ease-in-out;
  }

  h1.show-final {
    opacity: 1;
  }
</style>