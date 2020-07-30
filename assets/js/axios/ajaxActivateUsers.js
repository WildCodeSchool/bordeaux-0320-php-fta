import axios from 'axios';

const responseAjax = '<span>Modification enregistrée !</span><i class="material-icons ml-s pb-s">thumb_up</i>';

const switchUser = document.getElementsByClassName('switchActiveUser');

const activateUser = (id) => {
    axios.post(`/ajax/activate/${id}`)
        .then((response) => {
            M.toast({ html: responseAjax, classes: 'rounded bck-green color-black txt-shadow-white' });
        })
        .catch((error) => {
            // do somethings
        });
};

export function toggleUser() {
    for (let i = 0; i < switchUser.length; i++) {
        switchUser[i].addEventListener('click', (event) => {
            activateUser(event.target.dataset.id);
        });
    }
}

toggleUser();
