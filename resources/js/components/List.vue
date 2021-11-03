<template>
  <transition-group name="card" tag="section" class="list">
    <header key="0" class="p-1 flex justify-between">
      <h3 
        :contenteditable="isEditing"
        ref="nameList"
        @blur="restorePreviousValue()"
        class="p-1 font-bold leading-none text-gray-900 outline-none"
        :class="{ 'shadow-outline': isEditing }"
        @keydown.enter="saveTitle()"
      >
        {{ name }}
      </h3>
      <div class="flex">
        <a 
        @click="startEditing()" 
        v-if="!isEditing"
        class="flex pointer items-center px-3 edit-title-btn"
        >
          <svg class="w-4 h-4 mr-1.5" viewBox="0 0 20 20">
            <path
              class="fill-current"
              d="M9.634,10.633c0.116,0.113,0.265,0.168,0.414,0.168c0.153,0,0.308-0.06,0.422-0.177l4.015-4.111c0.229-0.235,0.225-0.608-0.009-0.836c-0.232-0.229-0.606-0.222-0.836,0.009l-3.604,3.689L6.35,5.772C6.115,5.543,5.744,5.55,5.514,5.781C5.285,6.015,5.29,6.39,5.522,6.617L9.634,10.633z"
            ></path>
            <path
              class="fill-current"
              d="M17.737,9.815c-0.327,0-0.592,0.265-0.592,0.591v2.903H2.855v-2.903c0-0.327-0.264-0.591-0.591-0.591c-0.327,0-0.591,0.265-0.591,0.591V13.9c0,0.328,0.264,0.592,0.591,0.592h15.473c0.327,0,0.591-0.264,0.591-0.592v-3.494C18.328,10.08,18.064,9.815,17.737,9.815z"
            ></path>
          </svg>
        </a>
        <a class="flex pointer pb-0-5 delete-btn" @click="deleteList()">
          X
        </a>
      </div>
    </header>
    <ul v-for="card in cards" :key="card.id">
      <Card v-show="card" :title="card.title" :key="card.id" :id="card.id" :idList="id" />
    </ul>
    <CardCreateForm key="-1" @add-new-card="addCardToDatabase($event)" />
  </transition-group>
</template>

<script>
import Card from './Card.vue';
import CardCreateForm from './CardCreateForm.vue';

export default {
  components: {
    Card,
    CardCreateForm,
  },

  props: {
    name: {
      type: String,
    },
    id: {
      type: Number
    },
    cards: {
      type: Array,
    },
  },

  data() {
    return {
      newCard: {},
      newListId: null,
      newCards: null,
      isEditing: false,
    }
  },

  methods: {
    addCardToDatabase(data) {
      axios
        .post('http://127.0.0.1:8000/api/cards?title=' + data + '&todo_list_id=' + this.id)
        .then((response) => {
          window.mitter.emit('show-alert', response.data.status.message)
          /** DOROBIT */
        })
        .catch((error) => {
          window.mitter.emit('show-alert', error.response.data.status.message)
          /** DOROBIT */
        })
      window.mitter.emit('update-card')
    },

    startEditing() {
      this.isEditing = true
      setTimeout(() => this.$refs.nameList.focus(), 0)
    },

    saveTitle() {
      // validation
      if(this.$refs.nameList.textContent.length <= 3 || 
        this.$refs.nameList.textContent.length >= 200) {
         this.$refs.nameList.textContent = this.name
         window.mitter.emit('show-alert', 'something wrong')
         /* DOROBIT */
      } else if(this.$refs.nameList.textContent === this.name) {
        this.$refs.nameList.textContent = this.name
      } else {
        this.$emit('edit-list-title', {
          'id': this.id,
          'text': this.$refs.nameList.textContent,
        })
      }
      this.isEditing = false
    },

    deleteList() {
      if(!confirm('Are you sure about at?')) return
      else {
        axios
          .delete('http://127.0.0.1:8000/api/todo-lists/' + this.id)
          .then((response) => {
            console.log(response.data)
          })
          .catch((error) => {
            console.log(error.response.data)
          })
        window.mitter.emit('delete-list')
      }
    },

    restorePreviousValue() {
      this.$refs.nameList.textContent = this.name
      this.isEditing = false
    }
  },
  
}
</script>

<style scoped>
.card-enter-active,
.card-leave-active {
  transition: all 0.15s;
}

.card-enter-from,
.card-leave-to {
  opacity: 0;
  transform: scale(0.75);
}

.pointer {
  cursor: pointer;
}

.delete-btn {
  font-size: 15px;
  font-weight: bold;
  opacity: 0.3;
}

.delete-btn:hover {
  opacity: 1;
}

.edit-title-btn {
  font-weight: bold;
  opacity: 0.3;
}

.edit-title-btn:hover {
  opacity: 1;
}
</style>