import axios from 'axios'
import { ref, watchEffect, inject } from 'vue'


export default function useCex() {
    const cexItem = ref({});
    const swal = inject('$swal')
  
    const getCexItem = async (barcode) => {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/cex-item-details/' + barcode)
          .then((response) => {
            
            cexItem.value = response.data;
            resolve(cexItem.value);
          })
          .catch((error) => {
            reject(error);
          });
      });
    };
  
    return { cexItem, getCexItem };
  }
  