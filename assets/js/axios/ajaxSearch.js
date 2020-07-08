import axios from 'axios';

export function searchUser(name, type)
{
    return new Promise((resolve, reject) => {
        axios.get('/admin/ajax/search/users', {
            params: {
                name: name,
                type: type,
            },
        }).then((response) => {
            resolve(response.data);
        }).catch((error) => {
            reject(error.response.statusText);
        });
    });
}
