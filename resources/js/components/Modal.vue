<template>
    <transition name="modal-animation">
      <div v-show="modalActive" class="modal flex justify-center items-center h-screen w-screen fixed top-0 left-0">
        <transition name="modal-animation-inner">
          <div v-show="modalActive" class="modal-inner relative max-w-screen-sm w-4/5 bg-white">
            <i @click="close" class="far fa-times-circle absolute text-xl cursor-pointer"></i>
            <!-- Modal Content -->
            <slot />
            <div class="flex items-center w-full">
                <button @click="close" type="button" class="inline-flex content-center items-center mt-3 px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">Close</button>
            </div>
        </div>
        </transition>
      </div>
    </transition>
  </template>

<script setup>
const props = defineProps({
  modalActive: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(['close']);

const close = () => {
  emit('close');
};
</script>


<style scoped>
.modal-animation-enter-active,
.modal-animation-leave-active {
    transition: opacity 0.3s cubic-bezier(0.52, 0.02, 0.19, 1.02);
}
.modal-animation-enter-from,
.modal-animation-leave-to {
    opacity: 0;
}
.modal-animation-inner-enter-active {
    transition: all 0.3s cubic-bezier(0.52, 0.02, 0.19, 1.02) 0.15s;
}
.modal-animation-inner-leave-active {
    transition: all 0.3s cubic-bezier(0.52, 0.02, 0.19, 1.02);
}
.modal-animation-inner-enter-from {
    opacity: 0;
    transform: scale(0.8);
}
.modal-animation-inner-leave-to {
    transform: scale(0.8);
}
.modal {

    background-color: rgba(37, 36, 36, 0.9);
}
.modal-inner {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      background-color: #fff;
      padding: 15px 15px;
      border-radius: 15px;
}
i {
    position: absolute;
    top: 15px;
    right: 15px;
}
i:hover {
    color: crimson;
}
</style>
