import { searchUser } from '../axios/ajaxSearch';
import { toggleLoading, createLine } from './createUserRow';

const button = document.getElementById('searchButton');

button.addEventListener('click', () => {
    const searchInput = document.getElementById('searchUser');
    toggleLoading();
    const name = searchInput.value;
    const userType = searchInput.dataset.type;
    searchUser(name, userType).then((value) => {
        const tableRef = document.getElementById('users-table').getElementsByTagName('tbody')[0];
        tableRef.innerHTML = '';
        console.log(value);
        if (value.length > 0) {
            for (let i = 0; i < value.length; i++) {
                createLine(tableRef, value, i);
            }
        }
        toggleLoading();
    }).catch((error) => {
        toggleLoading();
    });
});
