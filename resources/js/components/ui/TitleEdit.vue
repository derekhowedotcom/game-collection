<template>
    <!-- {{ collectionItem.title }} -->
    <!-- <router-link class="router-link" :to="{ name: 'collection-items.details', params: { id: this.$route.params.id } }">{{ collectionItem.title }}</router-link>  -->
    
    
    <div v-for="field in fields" :key="field.id" class="editable-field">
      <template v-if="editedFieldId === field.id">
        <input class="mr-1" type="text" v-model="collectionItem.title" :ref="`field${field.id}`" />
        <a href="#" v-if="can('collection-items.delete')" @click.prevent="toggleEdit()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-3 rounded ml-2"><svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Save</a>
      </template>
      <template v-else>
        <span class="mr-1">
          {{ collectionItem.title }}
        </span>
        <a href="#" v-if="can('collection-items.delete')" @click.prevent="toggleEdit(field.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded ml-2"><svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Quick Edit</a>
      </template>
    </div>


</template>

<script>
import { ref } from 'vue';
import { useAbility } from '@casl/vue'

export default {

    setup(){            
        const { can } = useAbility()
        return {  can }
    },
    props: { 
        collectionItem: Object 
    },


    data: function () {
        return {
        editedFieldId: null,
        fields: [
            {
            id: 1,
            value: null,
            }
        ],
        };
    },
    methods: {
        toggleEdit(id) {
            if (id) {
                this.editedFieldId = id;
                this.$nextTick(() => {
                    if (this.$refs["field" + id]) {
                        this.$refs["field" + id][0].focus();
                    }
                });
            } else {
                this.editedFieldId = null;
                this.doSave()
            }
           
            
        },
        doSave(){
            console.log('save here')
        }
    }
    
}
</script>

<style scoped>
.router-link:hover {
    color: rgb(37 99 235)
}





.editable-field {
  margin: 10px 0;
}
.editable-field input,
.editable-field button {
  border: 1px solid #4c4c4c;
  background-color: #fff;
  padding: 4px 6px;
  font-size: 18px;
}
</style>
