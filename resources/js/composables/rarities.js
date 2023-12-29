import axios from 'axios'
import { ref } from 'vue'

export default function useRarities() {
    const rarities = ref({})

    const getRarities = async (page = 1) => {
        axios.get('/api/rarities')
            .then(response => {
                rarities.value = response.data.data
            })
    }

    return { rarities, getRarities }
}
