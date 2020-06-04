import axios from 'axios';

export function addSchedule(form) {
    return new Promise((resolve, reject) => {
        axios.post('/ajax/schedule', form
        ).then(response => {
            resolve(response.data)
        }).catch(error => {
            reject(error.response.statusText);
        });
    });
}