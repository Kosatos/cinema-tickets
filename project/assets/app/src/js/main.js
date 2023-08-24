document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', (evt) => {
        const chair = evt.target.closest('.buying-scheme__chair')
        if (chair && !chair.classList.contains('buying-scheme__chair_taken')) {
            if (!chair.classList.contains('buying-scheme__chair_selected')) {
                chair.classList.add('buying-scheme__chair_selected')
            } else {
                chair.classList.remove('buying-scheme__chair_selected')
            }
        }
    })
})