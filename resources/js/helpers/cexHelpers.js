import {CEX_CATEGORY_MAPPER_ARRAY} from "../constants/cexConstants";

// Function that gets the cex category and matches it to the local category
export function getCexCategory(cexCategory) {
    // Set the cex category match to null
    let cexCategoryMatch = null;

    // Find the category in the array
    cexCategoryMatch = CEX_CATEGORY_MAPPER_ARRAY.find(category => category.name === cexCategory);

    // If the category is found return the local category id
    if(cexCategoryMatch){
        return cexCategoryMatch.localCategoryId;
    }

    return null;
}
