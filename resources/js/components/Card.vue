<template>
  <li 
    class="card relative"
    :class="{ 'z-10': isPopped }"
  >
    <div class="p-3 flex justify-between">
      <p 
        ref="textCard"
        class="outline-none"
        :class="{ 'shadow-outline': isEditing }"
        :contenteditable="isEditing"
        @keydown.enter="saveText()"
        @blur="restorePreviousValue()"
      >
        {{ title }}
      </p>
      <a 
        @click="doPop()" 
        v-if="!isPopped"
        class="flex items-center px-3 transform hover:translate-x-6 transition duration-130 ease-in"
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
    </div>

  <transition name="pop">
    <CardPopup 
      v-if="isPopped"
      @close-popup="undoPop()"
      @start-edit-card="editCard()"
      @start-delete-card="deleteCard()"
    />
  </transition>

  </li>
</template>

<script>
import CardPopup from './CardPopup.vue';

export default {
  components: {
    CardPopup,
  },

  props: {
    title: {
      type: String,
    },
    id: {
      type: Number,
    },
    idList: {
      type: Number,
    },
  },

  data() {
    return {
      isPopped: false,
      isEditing: false,
    }
  },

  methods: {
    doPop() {
      this.isPopped = true
      window.mitter.emit('toggle-overlay', true)
    },

     undoPop() {
      this.isPopped = false
      window.mitter.emit('toggle-overlay', false)
    },

    editCard() {
      this.isEditing = true
      setTimeout(() => this.$refs.textCard.focus(), 0)
    },

    saveText() {
      // Validation
      if(this.$refs.textCard.textContent.length <= 3 || 
        this.$refs.textCard.textContent.length >= 200) {
         this.$refs.textCard.textContent = this.title
         window.mitter.emit('show-alert', 'something wrong')
         /* DOROBIT */
      } else if(this.$refs.textCard.textContent === this.title) {
        this.$refs.textCard.textContent = this.title
      } else {
        window.mitter.emit('edit-card-data', {
          'id': this.id,
          'text': this.$refs.textCard.textContent,
          'todo_list_id': this.idList,
        })
      }
      // disable poppup
      this.isEditing = false
      this.undoPop()
    },

    deleteCard() {
      if(!confirm('Are you sure about at?')) this.undoPop()
      else {
        
        axios
          .delete('http://127.0.0.1:8000/api/cards/' + this.id)
          .then((response) => {
            console.log(response.data)
          })
          .catch((error) => {
            console.log(error.response.data)
          })

        this.undoPop()
        window.mitter.emit('delete-card')
      }
    },

    restorePreviousValue() {
      this.$refs.textCard.textContent = this.title
      this.isEditing = false
    }
  },

}
</script>

<style scoped>
.pop-enter-active,
.pop-leave-active {
  transition: all 0.1s ease-in;
}

.pop-enter-from,
.pop-leave-to {
  opacity: 0;
  transform: translateX(-25%);
}
</style>