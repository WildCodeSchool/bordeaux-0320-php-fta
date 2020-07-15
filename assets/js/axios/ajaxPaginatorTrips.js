import axios from 'axios';

export function showPage(limit) {
    return new Promise((resolve, reject) => {
        axios.get('/admin/ajax/page/trips', {
            params: {
                limit: limit,
            },
        }).then((response) => {
            resolve(response.data);
        }).catch((error) => {
            reject(error.response.statusText);
        });
    });
}
