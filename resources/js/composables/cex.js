import axios from 'axios'
import { ref, watchEffect, inject } from 'vue'


export default function useCex() {
    const cexItem = ref({});
    const isLoading = ref(false);

    const getCexItem = async (barcode) => {
        isLoading.value = true;
      return new Promise((resolve, reject) => {
        axios
          .get('/api/cex-item-details/' + barcode)
          .then((response) => {

            cexItem.value = response.data;
            resolve(cexItem.value);
              isLoading.value = false;
          })
          .catch((error) => {
            reject(error);
          });
      });
    };

    return { cexItem, getCexItem, isLoading };
  }
