import axios from 'axios';

export function showPage(limit, type)
{
    return new Promise((resolve, reject) => {
        axios.get('/admin/ajax/page/users', {
            params: {
                limit: limit,
                type: type,
            },
        }).then((response) => {
            resolve(response.data);
        }).catch((error) => {
            reject(error.response.statusText);
        });
    });
}
