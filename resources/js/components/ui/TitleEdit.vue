<template>
  <div class="editable-field">
      <input
          class="mr-1"
          type="text"
          v-model="collectionItem.title"
          ref="field"
          v-if="editedField"
          @keydown.esc="reset"
          @keydown.enter="toggleEdit"
      />
      <a
          href="#"
          @click.prevent="toggleEdit()"
          class="text-green-700 font-bold py-2 px-3 rounded"
          v-if="editedField"
      >
        <span v-html="SVG_TICK_MED"></span>
      </a>
      <span v-if="!editedField" class="mr-1">{{ collectionItem.title }}</span>
      <a
          href="#"
          @click.prevent="toggleEdit()"
          class="text-blue-700 font-bold py-2 px-2 rounded"
          v-if="!editedField && canEdit"
      >
        <div v-if="isLoading"
             class="inline-block animate-spin w-4 h-4 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></div>
        <span v-else>
          <span v-html="SVG_EDIT_MED"></span>
        </span>
      </a>
  </div>
</template>

<script setup>
import { ref, defineProps, nextTick } from 'vue';
import { SVG_EDIT_MED, SVG_TICK_MED } from "../../constants/svgConstants";

const props = defineProps({
  collectionItem: Object,
  canEdit: Boolean,
  isLoading: Boolean,
});

const editedField = ref(false);
const field = ref(null);
const emit = defineEmits(['quick-edit-save', 'quick-edit-esc-key']);
const defaultTitle = ref('');

// function to reset the title on escape key
const reset = (event) => {
    if (event.key === 'Escape') {
      editedField.value = false;
      // Reset the title
      emit('quick-edit-esc-key', defaultTitle.value);
    }
};

const toggleEdit = async () => {
  if (editedField.value) {
    editedField.value = false;
    await doSave();
  } else {
    editedField.value = true;
    // Focus the input field on next tick once it's shown
    await nextTick(() => {
      field.value.focus();
    });
    // Save the default title
   defaultTitle.value = props.collectionItem.title;
  }
};

const doSave = () => {
  emit('quick-edit-save');
};
</script>

<style scoped>
.editable-field input,
.editable-field button {
  border: 1px solid #4c4c4c;
  background-color: #fff;
  padding: 4px 6px;
  font-size: 18px;
}
</style>
