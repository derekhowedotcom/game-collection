import axios from 'axios'
import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import useCollectionItemCounts from "./collectionItemCounts";

export default function useCollectionItems() {
    const collectionItems = ref({})
    const collectionItem = ref({})
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const isProcessing = ref(false)
    const swal = inject('$swal')
    const { collectionItemCounts, getCollectionItemCounts, getCollectionItemValueAndAmountSpent } = useCollectionItemCounts({
        categoryNames: 'hardware,software,other',
    })
    //get all collectionItems
    const getCollectionItems = async (
        page = 1,
        search_category = '',
        search_id = '',
        search_title = '',
        search_description = '',
        search_global = '',
        order_column = 'title',
        order_direction = 'asc'
    ) => {
        isLoading.value =true
        axios.get('/api/collection-items?page=' + page +
            '&search_category=' + search_category +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_description=' + search_description +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction
            )
            .then(response => {
                collectionItems.value = response.data
                isLoading.value = false
            })
    }

    //get one collectionItem
    const getCollectionItem = async (id) => {
        isLoading.value = true

        axios.get('/api/collection-items/' + id)
            .then(response => {
                collectionItem.value = response.data.data;
                isLoading.value = false
            })
    }

    // Store a new collectionItem
    const storeCollectionItem = async (collectionItem) => {

        if(isLoading.value) return;
        console.log(collectionItem);

       isLoading.value =true
       validationErrors.value = {}

       let serializedCollectionItem = new FormData()
       for (let item in collectionItem){
            if(collectionItem.hasOwnProperty(item)){
                serializedCollectionItem.append(item, collectionItem[item])
            }
       }

        axios.post('/api/collection-items', serializedCollectionItem)
            .then(response => {
                getCollectionItemCounts();
                getCollectionItemValueAndAmountSpent();
                //redirect to the index page
                //router.push({ name: 'collection-items.index' })

                // redirect to the details page of the item just created
                router.push({ name: 'collection-items.details', params: { id: response.data.data.id } })
                swal({
                    icon: 'success',
                    title: 'Collection Item saved successfully'
                })
                collectionItems.value = response.data

            })
            .catch(error => {

                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)

    }

    // Update a collectionItem
    const updateCollectionItem = async (collectionItem, qucikEdit) => {
        console.log(collectionItem);
        if(isProcessing.value) return;

        isProcessing.value = true
       validationErrors.value = {}

       let serializedCollectionItem = new FormData()
       for (let item in collectionItem){
            if(collectionItem.hasOwnProperty(item)){
                // Do not include thubnail if it's not a file object
                if(!(item === 'thumbnail' && collectionItem[item] != '[object File]')){
                    // If the value is null, set it to empty string because FormData will treat null as a string
                    if(collectionItem[item] === null){
                        collectionItem[item] = '';
                    }
                    serializedCollectionItem.append(item, collectionItem[item])
                }
            }
       }

        axios.post('/api/collection-items/' + collectionItem.id, serializedCollectionItem)
            .then(response => {
                //router.push({ name: 'collection-items.index' })
                if(!qucikEdit){
                    // redirect to the details page of the item just updated
                    router.push({ name: 'collection-items.details', params: { id: response.data.data.id } })
                    swal({
                        icon: 'success',
                        title: 'Collection Item saved successfully'
                    })
                }
                collectionItems.value = response.data
            })
            .catch(error => {

                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isProcessing.value = false)
    }

    // Delete a collectionItem
    const deleteCollectionItem = async (id) => {
        swal({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this action!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        })
        .then(result => {
            if (result.isConfirmed) {
                axios.delete('/api/collection-items/' + id)
                    .then(response => {
                        getCollectionItems()
                        router.push({name: 'collection-items.index'})
                        swal({
                            icon: 'success',
                            title: 'Collection Item deleted successfully'
                        })
                    })
                    .catch(error => {
                        swal({
                            icon: 'error',
                            title: 'Something went wrong'
                        })
                    })
            }
        })
    }


    return {
        collectionItems,
        collectionItem,
        getCollectionItems,
        getCollectionItem,
        storeCollectionItem,
        updateCollectionItem,
        deleteCollectionItem,
        collectionItemCounts,
        validationErrors,
        isLoading,
        isProcessing
    }

}




