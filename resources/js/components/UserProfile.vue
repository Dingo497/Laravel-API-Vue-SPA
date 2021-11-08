<template>
<transition-group 
    name="card" 
    tag="section" 
    class="userProfile list"
>
    <header 
      key="0" 
      class="p-1 flex justify-between"
    >
      <h3 key="1" class="p-1 font-bold leading-none text-gray-900 outline-none">
        This is your profile <strong>{{ userData.name }}</strong>
      </h3>
    </header>

    <ul key="2">
      <li class="card">
        <div class="p-3 flex justify-between">
          <p class="outline-none">
            Email: {{ userData.email }}
          </p>
        </div>
      </li>
    </ul>
  </transition-group>
</template>



<script>
export default {
  // PROPS
  props: {
    userData: {
      type: Object,
    },
  },
}
</script>



<style scoped>
.userProfile {
  position: absolute;
  justify-content: center;
  left: 50%;
  margin-left: -284.75px;
  width: 50%;
  z-index: 20;
}

.card-enter-active,
.card-leave-active {
  transition: all 0.15s;
}

.card-enter-from,
.card-leave-to {
  opacity: 0;
  transform: scale(0.75);
}
</style>