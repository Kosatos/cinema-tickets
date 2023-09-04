import axios from "axios";
document.addEventListener('DOMContentLoaded', () => {
    const bookingBtn = document.querySelector('.acceptin-button.booking-btn')
    const getQRBtn = document.querySelector('.acceptin-button.qr-btn')
    document.addEventListener('click', (evt) => {
        const day = evt.target.closest('.page-nav__day')
        if (day && !day.classList.contains('page-nav__day_chosen')) {
            document.querySelector('.page-nav__day_chosen').classList.remove('page-nav__day_chosen')
            day.classList.add('page-nav__day_chosen')
            axios(`?films=${day.dataset.date}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(({ data }) => {
                const wrapper = document.querySelector('.sessions-wrapper')
                wrapper.innerHTML = data
            }).catch(e => console.log(e))
        }

        const chair = evt.target.closest('.buying-scheme__chair')
        if (chair && !chair.classList.contains('buying-scheme__chair_taken')) {
            if (!chair.classList.contains('buying-scheme__chair_selected')) {
                chair.classList.add('buying-scheme__chair_selected')
            } else {
                chair.classList.remove('buying-scheme__chair_selected')
            }
            const selected = [...document.querySelectorAll('.buying-scheme__chair_selected:not(.buying-scheme__chair_legend)')]
            bookingBtn.dataset.seat = selected.map(item => item.dataset.seatId).join(',')
            bookingBtn.disabled = false
        }
    })

    bookingBtn && bookingBtn.addEventListener('click', evt => {
        window.location = `/payment?sessionId=${evt.currentTarget.dataset.session}&seat=${evt.currentTarget.dataset.seat}`
    })

    getQRBtn && getQRBtn.addEventListener('click', evt => {
        axios.post('/api/ticket', {
            sessionId: evt.currentTarget.dataset.session,
            seatId: evt.currentTarget.dataset.seat.split(',')
        }).then(({ data }) => {
            document.querySelector('.ticket').innerHTML = data
        })
    })
})