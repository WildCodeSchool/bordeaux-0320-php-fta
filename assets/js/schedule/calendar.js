import {addSchedule} from '../axios/ajaxSchedule';

let buttonSchedule = document.getElementById('button_schedule');

buttonSchedule.addEventListener('click', (e) => {

    let formSchedule = document.getElementById('form_schedule')
    let formData = new FormData(formSchedule)
    buttonSchedule.setAttribute('disabled', '');
    addSchedule(formData, (value) => {
        console.log(value)
        for (let i = 0; i < value.length; i++ ) {
            console.log(value.date)
        }


    }).catch((error) => {

    });

})
