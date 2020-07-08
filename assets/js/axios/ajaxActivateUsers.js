import axios from 'axios';

const activateUser = (id) => {
    axios.post(`/ajax/activate/${id}`)
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.log(error);
        });
};

const switchUser = document.getElementsByClassName('switchActiveUser');

for (let i = 0; i < switchUser.length; i++) {
    switchUser[i].addEventListener('click', (event) => {
        activateUser(event.target.dataset.id);
    });
}
