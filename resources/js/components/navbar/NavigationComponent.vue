<template>
  <ul class="navbarLinks">
    <li>
      <a 
        class="link w-full p-2.5 bg-white bg-opacity-40 focus:bg-opacity-100 placeholder-white font-bold rounded-lg outline-none focus:shadow-xl"
        @click="logout()"
      >
        Logout
      </a>
    </li>

    <li>
      <a 
        class="link w-full p-2.5 bg-white bg-opacity-40 focus:bg-opacity-100 placeholder-white font-bold rounded-lg outline-none focus:shadow-xl"
        @click="showProfile()"
      >
        Profile
      </a>
    </li>
  </ul>
</template>



<script>
export default {
  // PROPS
  props: {
    accessToken: {
      type: Object,
    }
  },


  // METHODS
  methods: {
    /**
     * Logout user and "redirect" to login form
     */
    logout() {
      axios
        .post('http://127.0.0.1:8000/api/logout', {}, {
          headers: {
            Authorization: this.accessToken.token_type + ' ' + this.accessToken.access_token
          }
        })
        .then((response) => {
          this.$emit('logout-user')
          window.mitter.emit('show-alert', { message: response.data.status.message, status: true })
        })
        .catch((error) => {
          window.mitter.emit('show-alert', { message: error.response.data.status.message, status: false })
        })
    },

    /**
     * Show info about User
     */
    showProfile() {
      axios
        .post('http://127.0.0.1:8000/api/profile',{}, {
          headers: {
            Authorization: this.accessToken.token_type + ' ' + this.accessToken.access_token
          }
        })
        .then((response) => {
          this.$emit('show-profile', response.data.body)
        })
        .catch((error) => {
          window.mitter.emit('show-alert', { message: error.response.data.status.message, status: false })
        })
    },
  },

}
</script>



<style scoped>
.navbarLinks {
  position: absolute;
  top: 1%;
  right: 0.5%;
  display: flex;
  width: 50%;
	justify-content: flex-end;
	align-items: flex-start;
	align-content: center;
}

.link {
  cursor: pointer;
  margin: 0px 5px;
  font-size: 70%;
  opacity: 0.6;
  color: white;
  transition: 0.3s ease-in-out;
}

.link:hover {
  color: #18181b;
  cursor: pointer;
  opacity: 1;
}
</style>