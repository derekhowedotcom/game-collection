// Format as a string with two decimal places and commas
export function formatCurrency(amount) {
    // Ensure amount is a number
    amount = Number(amount);

    const formattedAmount = amount.toLocaleString('en-gb', {
        style: 'currency',
        currency: 'GBP',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Remove any extra characters or spaces
    return formattedAmount.replace(/[^\d\-\,.]/g, '')
}


