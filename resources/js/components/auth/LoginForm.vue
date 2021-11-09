<template>
  <div id="login">
    <i v-if="position === 0" class="previousButton fas fa-user"></i>
    <i v-else class="previousButton fas fa-arrow-left" @click="previousStep"></i>
    <i class="forwardButton fas fa-arrow-right" @click="checkStep"></i>
    
    <div id="inputContainer" :class="{'showContainer': showContainer}">
      <h2 class="FormLabel">Login</h2>

      <form @submit.prevent="checkStep">
        <input id="inputField" :type="inputType" v-model="inputValue" ref="logininput" required />
        <label id="inputLabel">{{ inputLabel }}</label>
      </form>

      <a class="linkToLogin" @click="this.$emit('show-register')"><small>Don't have an account yet?</small></a>
      <div id="inputProgress"></div>
    </div>

  </div>
</template>



<script>
export default {
  // DATAS
  data() {
    return {
      position: 0,
      inputLabel: '',
      inputType: 'text',
      inputValue: '',
      showContainer: false,
      progress: '0%',
      loginSteps: [
        {
          label: "What's your email?",
          type: "text",
          value: "",
          pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        },
        {
          label: "What's your password",
          type: "password",
          value: "",
          pattern: /.+/
        }
      ]
    }
  },


  // MOUNTED
  // start animated form
  mounted() {
    this.setStep();
  },


  // METHODS
  methods: {
    /**
     * Set what step to do from array of loginSteps based on position.
     * Start with position 0 so start with user name
     * then showStep
     */
    setStep() {
      this.inputLabel = this.loginSteps[this.position].label;
      this.inputType = this.loginSteps[this.position].type;
      this.inputValue = this.loginSteps[this.position].value;
      this.$refs.logininput.focus();
      this.showStep();
    },

    /**
     * Show showContainer
     * then program waiting for input
     */
    showStep() {
      setTimeout(() => {
        this.showContainer = true;
      }, 100)
    },

    /**
     * After submit form check if inputs are valid on based pattern
     * if no set add class on wrong animation
     * if yes set class on ok animation and input from form is saved into loginSteps
     * and the position increases by one
     * then if loginSteps have current position, then hide actual step and set then show new one
     * else hide step add close class and send data to AuthForm.vue
     * then set progress
     */
    checkStep() {
      if(!this.loginSteps[this.position].pattern.test(this.inputValue)) {
        login.classList.add('wrong');
        login.classList.add('wronganimation');
        setTimeout(() => {
          login.classList.remove('wronganimation');
        }, 500);
        this.$refs.logininput.focus();
      }
      else {
        login.className = '';
        login.classList.add('okanimation');
        setTimeout(() => {
          login.classList.remove('okanimation');
        }, 200);
        this.loginSteps[this.position].value = this.inputValue;
        this.position += 1;
        if(this.loginSteps[this.position]) {
          this.hideStep(this.setStep);
        }
        else {
          this.hideStep(() => {
            login.className = 'close';
            this.$emit('show-final-login', { 
              'visible': true, 
              email: this.loginSteps[0].value,
              password: this.loginSteps[1].value,
            })
          });
          setTimeout(() => {
              login.className = ''
              this.position = 0
              this.setProgress()
              this.setStep()
          }, 3300);
        }
      }
      this.setProgress();
    },

    /**
     * Hide current step/container and after timeout do a callback
     */
    hideStep(callback) {
      this.showContainer = false;
      setTimeout(callback, 100);
    },

    /**
     * Do a previous step set up position one less and set new progress
     */
    previousStep() {
      this.position -= 1;
      login.className = '';
      this.hideStep(this.setStep);
      this.setProgress();
    },

    /**
     * Set progress on based position / all items in array loginSteps * 100
     * for example step 2: 1/3 * 100 = 33,33% and send it to AuthForm.vue
     */
    setProgress() {
      this.progress = (this.position / this.loginSteps.length * 100) + '%';
      this.$emit('set-progress', this.progress)
    }
  },
}
</script>



<style scoped>
#login {
  position: relative;
  width: 480px;
  height: 80px;
  padding: 10px;
  background-color: #fff;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2), 0 10px 10px rgba(0, 0, 0, 0.2);
}
#login.close {
  width: 0;
  padding: 10px 0;
  overflow: hidden;
  transition: 0.8s ease-in-out;
  box-shadow: 0 16px 24px 2px rgba(0, 0, 0, 0.2);
}
.previousButton {
  position: absolute;
  left: 30px;
  top: 12px;
  font-size: 1rem;
  color: #9e9e9e;
  cursor: pointer;
  z-index: 20;
}
.previousButton:hover {
  color: #009345;
}
.forwardButton {
  position: absolute;
  top: 30px;
  right: 20px;
  font-size: 3rem;
  color: #106B4E;
  cursor: pointer;
  z-index: 20;
}
.forwardButton:hover {
  color: #009345;
}
.wrong .forwardButton {
  color: #D93F38;
}
.close .forwardButton, .close .previousButton {
  color: #fff;
}
#inputContainer {
  position: relative;
  padding: 30px 20px 20px 20px;
  margin-right: 60px;
  opacity: 0;
  transition: opacity 0.3s ease-in-out;
}
#inputContainer input {
  position: relative;
  width: 100%;
  font-size: 1.35rem;
  font-weight: bold;
  outline: 0;
  background: transparent;
  box-shadow: none;
  border: none;
}
#inputContainer input:valid + #inputLabel {
  top: 3px;
  left: 42px;
  font-size: 0.7rem;
  font-weight: normal;
  color: #999;
}
#inputLabel {
  position: absolute;
  top: 32px;
  left: 20px;
  font-size: 1.35rem;
  font-weight: bold;
  pointer-events: none;
  transition: 0.2s ease-in-out;
}
#inputProgress {
  width: 0%;
  border-bottom: 6px solid #106B4E;
  transition: width 0.6s ease-in-out;
}
.wrong #inputProgress {
  border-color: #D93F38;
}
.showContainer {
  opacity: 1 !important;
}
.showContainer #inputProgress {
  width: 100%;
}
.wronganimation {
  animation: 0.5s linear 0s 1 wrong-animation;
}
.okanimation {
  animation: 0.2s linear 0s 1 ok-animation;
}
@keyframes wrong-animation {
  0% {
    transform: translateX(0);
  }
  20% {
    transform: translateX(-20px);
  }
  40% {
    transform: translateX(20px);
  }
  60% {
    transform: translateX(-20px);
  }
  80% {
    transform: translateX(20px);
  }
  100% {
    transform: translateX(0);
  }
}
@keyframes ok-animation {
  0% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(10px);
  }
  100% {
    transform: translateY(0);
  }
}

.FormLabel {
  position: absolute;
  color: white;
  font-size: 25px;
  font-weight: bold;
  top: -55%;
  left: 0%;
}

.linkToLogin {
  position: absolute;
  color: white;
  top: 85%;
  left: 0%;
  opacity: 0.5;
  transition: 0.3s ease-in-out;
}

.linkToLogin:hover {
  cursor: pointer;
  opacity: 1;
  transform: scale(1.10);
}
</style>