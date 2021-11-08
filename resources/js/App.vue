<template>

  <AuthForm 
    v-if="!loggedIn"
    @auth-successfully="showMainContent($event)"
  />

  <main 
    v-if="loggedIn"
    class="flex min-h-screen background"
  >

  <NavigationComponent
    :accessToken="accessToken"
    @logout-user="loggedIn = false"
    @show-profile="showProfile($event)"
  />

    <transition-group 
      tag="div" 
      name="list" 
      class="sm:flex items-start w-screen px-4 py-10 overflow-x-auto"
    >
    
    <UserProfile
      v-if="showProfileOverlay"
      :userData="userData"
      @click="hideProfile()"
    />

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
          :accessToken="accessToken"
        />
      </ul>

      <ListCreateForm 
        @list-create="getListsFromDatabase()" 
        key="-1" 
        :accessToken="accessToken"
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
import AuthForm from './components/auth/AuthForm.vue';
import NavigationComponent from './components/navbar/NavigationComponent.vue';
import UserProfile from './components/UserProfile.vue';

export default {
  // COMPONENTS
  components: {
    List,
    ListCreateForm,
    ShowAlert,
    AuthForm,
    NavigationComponent,
    UserProfile,
  },


  // DATAS
  data() {
    return {
      lists: [],
      overlay: false,
      loggedIn: false,
      accessToken: {},
      showProfileOverlay: false,
      userData: {},
    }
  },


  // METHODS
  methods: {
    showMainContent(accessToken) {
      this.accessToken = accessToken
      this.getListsFromDatabase()
      this.loggedIn = true
      window.mitter.on('toggle-overlay', event => this.overlay = event)
    },

    /**
     * Get Lists from database
     */
    getListsFromDatabase() {
      axios
        .get('http://127.0.0.1:8000/api/todo-lists', {
          headers: {
            Authorization: this.accessToken.token_type + ' ' + this.accessToken.access_token
          }
        })
        .then((response) => {
          this.lists = response.data.body
        })
        .catch((error) => {
          window.mitter.emit('show-alert', { message: error.response.data.status.message, status: false })
        })
    },

    showProfile(data) {
      this.overlay = true
      this.showProfileOverlay = true
      this.userData = data
    },

    hideProfile() {
      this.overlay = false
      this.showProfileOverlay = false
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