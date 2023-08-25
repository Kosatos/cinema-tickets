import axios from "axios";

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

        const day = evt.target.closest('.page-nav__day')
        if (day && !day.classList.contains('page-nav__day_chosen')) {
            document.querySelector('.page-nav__day_chosen').classList.remove('page-nav__day_chosen')
            day.classList.add('page-nav__day_chosen')
            axios(`?films=${day.dataset.date}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                const wrapper = document.querySelector('.sessions-wrapper')
                wrapper.innerHTML = response.data
            }).catch(e => console.log(e))
        }
    })
})