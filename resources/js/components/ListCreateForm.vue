<template>
  <form
    @submit.prevent="addListToDatabase()"
    class="flex-shrink-0 sm:w-80 sm:mx-4 sm:mb-0 sm:pr-8 text-gray-800"
    action="/"
  >
    <input
      v-model="name"
      class="w-full p-2.5 bg-white bg-opacity-40 focus:bg-opacity-100 placeholder-white focus:placeholder-gray-600 font-bold rounded-lg outline-none focus:shadow-xl"
      type="text"
      placeholder="Add another list"
    />
  </form>
</template>



<script>
// IMPORTS
import { validateLengthInputs } from '../functions/requests.js'

export default {
  // PROPS
  props: {
    accessToken: {
      type: Object,
    }
  },

  // DATA
  data() {
    return {
      name: ''
    }
  },



  // METHODS
  methods: {
    /**
     * Validation List
     * Post List to database
     * Reset List name
     * Send emit to reload array of Lists
     */
    addListToDatabase() {
      if(validateLengthInputs(this.name) === false) {
          this.name = ''
         return
      }

      axios
        .post('http://127.0.0.1:8000/api/todo-lists', {
          title: this.name,
        }, {
          headers: {
            Authorization: this.accessToken.token_type + ' ' + this.accessToken.access_token
          }
        })
        .then((response) => {
          window.mitter.emit('show-alert', { message: response.data.status.message, status: true })
        })
        .catch((error) => {
          window.mitter.emit('show-alert', { message: error.response.data.status.message, status: false })
        })
        
      this.name = ''
      this.$emit('list-create', this.name)
    },
  },
}
</script>