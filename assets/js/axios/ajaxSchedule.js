import axios from 'axios';

export function addSchedule(form, action) {
    return new Promise((resolve, reject) => {
        axios.post('/ajax/schedule', form
        ).then(response => {
            action(response.data)
            resolve(response.data)
        }).catch(error => {
            reject(error.response.statusText);
        });
    });
}