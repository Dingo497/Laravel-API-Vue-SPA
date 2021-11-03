<template>
  <main class="flex min-h-screen bg-purple-500 lg:bg-gradient-to-r from-red-400 to-pink">
    <transition-group tag="div" name="list" class="sm:flex items-start w-screen px-4 py-10 overflow-x-auto">

      <div
        v-if="overlay"
        id="overlay"
        class="bg-black bg-opacity-70 fixed top-0 left-0 w-full h-screen z-10"
        key="-1"
      ></div>

      <ul v-for="list in lists" :key="list.id">
        <List @edit-list-title="editListInDatabase($event)" :name="list.name" :id="list.id" :cards="list.cards" />
      </ul>

      <ListCreateForm @add-new-list="addListToDatabase($event)" key="0" />

    </transition-group>
  </main>
</template>

<script>
import List from './components/List.vue'
import ListCreateForm from './components/ListCreateForm.vue'

export default {
  components: {
    List,
    ListCreateForm,
  },

  data() {
    return {
      lists: [],
      newList: {},
      overlay: false
    }
  },

  mounted() {
    axios
      .get('http://127.0.0.1:8000/api/todo-lists')
      .then((response) => {
        this.lists = response.data.body
      })
      .catch((error) => {
        window.mitter.emit('show-alert', error.response.data)
        /** DOROBIT */
      })

    window.mitter.on('toggle-overlay', event => this.overlay = event)
    window.mitter.on('edit-card-data', event => this.editCardInDatabase(event))
    window.mitter.on('update-card', () => this.getListsFromDatabase())
    window.mitter.on('delete-card', () => this.getListsFromDatabase())
    window.mitter.on('delete-list', () => this.getListsFromDatabase())
  },

  methods: {
    addListToDatabase(data) {
      // validate
      if(!data) return
      if(data.length <= 3) return
      if(data.length >= 200) return

      // post new list
      axios
        .post('http://127.0.0.1:8000/api/todo-lists?title=' + data)
        .then((response) => {
          window.mitter.emit('show-alert', response.data.status.message)
          /** DOROBIT */
        })
        .catch((error) => {
          window.mitter.emit('show-alert', error.response.data.status.message)
          /** DOROBIT */
        })
      this.getListsFromDatabase()
    },

    getListsFromDatabase() {
      axios
        .get('http://127.0.0.1:8000/api/todo-lists')
        .then((response) => {
          this.lists = response.data.body
        })
        .catch((error) => {
          window.mitter.emit('show-alert', error.response.data)
          /** DOROBIT */
        })
    },

    editCardInDatabase(data) {
      axios
        .patch('http://127.0.0.1:8000/api/cards/' + data.id + '?title=' + data.text + '&todo_list_id=' + data.todo_list_id)
        .then((response) => {
          window.mitter.emit('show-alert', response.data.status.message)
          /** DOROBIT */
        })
        .catch((error) => {
          window.mitter.emit('show-alert', error.response.data)
          /** DOROBIT */
        })
      this.getListsFromDatabase()
    },

    editListInDatabase(data) {
      axios
        .patch('http://127.0.0.1:8000/api/todo-lists/' + data.id + '?title=' + data.text)
        .then((response) => {
          window.mitter.emit('show-alert', response.data.status.message)
          console.log(response.data.status.message)
        })
        .catch((error) => {
          window.mitter.emit('show-alert', error.response.data)
          console.log(error.response.data)
        })
    }
  }

}
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
  transition: all 0.15s;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: scale(0.75);
}

#overlay {
  transform: scale(1);
}
</style>