<template>
  <form
    @submit.prevent="handleName()"
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
export default {
  data() {
    return {
      name: ''
    }
  },

  methods: {
    handleName() {
      this.$emit('add-new-list', this.name)
      this.name = ''
    }
  }
}
</script>

<style lang="scss" scoped>

</style>