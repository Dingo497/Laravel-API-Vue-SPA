<template>
  <div v-if="visible" class="errorMsg" :class="{successMsg: isSuccess}">
    <i class="fa fa-times-circle"></i>
    This is a error message.
  </div>
</template>

<script>
export default {
  data() {
    return {
      visible: false,
      isSuccess: false,
    }
  },

  mounted() {
    window.mitter.on('show-alert', event => {
      console.log(event)
    })
  }
}
</script>

<style scoped>
@import url('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
.successMsg,
.errorMsg {
  margin: 10px 0;
  padding: 10px;
  border-radius: 3px 3px 3px 3px;
}
.successMsg {
  color: #270;
  background-color: #DFF2BF;
}
.errorMsg {
  color: #D8000C;
  background-color: #FFBABA;
}
</style>