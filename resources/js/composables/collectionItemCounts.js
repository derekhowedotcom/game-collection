import axios from 'axios'
import { ref } from 'vue'
const collectionItemCount = ref(null)
const collectionItemCounts = ref({})
const collectionItemValueAndAmountSpent = ref({})
// Get collection item counts
export default function useCollectionItemCount({ categoryName = null, categoryNames = null }) {

    // Return total count of collection items based on category name like 'software'
    const getCollectionItemCount = async () => {
        await axios.get('/api/collection-items/count/' + categoryName)
            .then(response => {
                collectionItemCount.value = response.data
            })
    }

    // Return total multi counts of collection items based on category name like 'software'
    const getCollectionItemCounts = async () => {
        await axios.get('/api/collection-items/multi-count/' + categoryNames)
            .then(response => {
                collectionItemCounts.value = response.data
            })
    }

    // Return total value and price paid for all collection items
    const getCollectionItemValueAndAmountSpent = async () => {
        await axios.get('/api/collection-items/value-and-amount-spent')
            .then(response => {
                collectionItemValueAndAmountSpent.value = response.data
            })
    }

    return {
        collectionItemCount,
        getCollectionItemCount,
        collectionItemCounts,
        getCollectionItemValueAndAmountSpent,
        collectionItemValueAndAmountSpent,
        getCollectionItemCounts,
    }


}

