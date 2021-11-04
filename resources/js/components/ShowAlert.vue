<template>
  <div 
    v-if="visible" 
    class="errorMsg alert" 
    :class="{successMsg: status}"
  >
    <i 
      class="fa fa-times-circle cursor"
      @click="visible = false"
    ></i>
    {{ message }}
  </div>
</template>



<script>
export default {
  // DATAS
  data() {
    return {
      visible: false,
      status: false,
      message: '',
    }
  },


  // CREATED
  created() {
    window.mitter.on('show-alert', event => {
      this.visible   = false
      this.status    = event.status
      this.message   = event.message
      this.visible   = true

      setTimeout(() => {
        this.visible = false
      }, 3000);
    })
  },
}
</script>



<style scoped>
@import url('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
.alert {
  padding: 10px;
  font-weight: bold;
  border-radius: 3px 3px 3px 3px;
  position: absolute;
  top: 2%;
  margin-left: -96.48px;
  left: 50%;
}
.successMsg {
  color: rgb(255, 255, 255);
  background-color: #8bdb00;
}
.errorMsg {
  color: #ffffff;
  background-color: #c51e1e;
}
.cursor {
  cursor: pointer;
}
.cursor:hover {
  color: #bada55;
}
</style>