import axios from 'axios';

const activateUser = (id) => {
    axios.post(`/ajax/activate/${id}`)
        .then((response) => {
            console.log(response.data);
        })
        .catch((error) => {
            console.log(error);
        });
};

const switchUser = document.getElementsByClassName('switchActiveUser');
const responseAjax = '<span>Modification enregistrée !</span><i class="material-icons ml-s pb-s">thumb_up</i>';

for (let i = 0; i < switchUser.length; i++) {
    switchUser[i].addEventListener('click', (event) => {
        activateUser(event.target.dataset.id);
        M.toast({ html: responseAjax, classes: 'rounded bck-green color-black txt-shadow-white' });
    });
}
