<template>
  <main class="flex min-h-screen bg-purple-500 lg:bg-gradient-to-r from-red-400 to-pink">
    <transition-group 
      tag="div" 
      name="list" 
      class="sm:flex items-start w-screen px-4 py-10 overflow-x-auto"
    >
      <div
        v-if="overlay"
        id="overlay"
        class="bg-black bg-opacity-70 fixed top-0 left-0 w-full h-screen z-10"
        key="0"
      ></div>

      <ul v-for="list in lists" :key="list.id">
        <List 
          @list-delete="getListsFromDatabase()" 
          @card-create="getListsFromDatabase()" 
          @list-title-edit="getListsFromDatabase()"
          @card-edit="getListsFromDatabase()"
          @delete-card="getListsFromDatabase()"
          :name="list.name" 
          :id="list.id" 
          :cards="list.cards" 
        />
      </ul>

      <ListCreateForm 
        @list-create="getListsFromDatabase()" 
        key="-1" 
      />

    </transition-group>
  </main>

  <transition name="list">
    <ShowAlert key="-2" />
  </transition>

</template>



<script>
// IMPORTS
import List from './components/List.vue'
import ListCreateForm from './components/ListCreateForm.vue'
import ShowAlert from './components/ShowAlert.vue';

export default {
  // COMPONENTS
  components: {
    List,
    ListCreateForm,
    ShowAlert,
  },


  // MOUNTED APP
  mounted() {
    this.getListsFromDatabase()

    window.mitter.on('toggle-overlay', event => this.overlay = event)
  },


  // DATAS
  data() {
    return {
      lists: [],
      overlay: false
    }
  },


  // METHODS
  methods: {
    /**
     * Get Lists from database
     */
    getListsFromDatabase() {
      axios
        .get('http://127.0.0.1:8000/api/todo-lists')
        .then((response) => {
          this.lists = response.data.body
        })
        .catch((error) => {
          window.mitter.emit('show-alert', { message: error.response.data.status.message, status: false })
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