import { showPage } from '../axios/ajaxPaginator';
import { createLine } from './createUserRow';
import { calculateLimit } from './paginatorButton';
import { toggleUser } from '../axios/ajaxActivateUsers';

const paginators = document.getElementsByClassName('paginator');

for (let i = 0; i < paginators.length; i++) {
    paginators[i].addEventListener('click', (evt) => {
        const button = paginators[i];
        const type = button.dataset.type;
        const paginatorType = button.dataset.paginator;
        const limit = Number(button.dataset.limit);

        showPage(limit, type).then((value) => {
            const tableRef = document.getElementById('users-table').getElementsByTagName('tbody')[0];
            tableRef.innerHTML = '';
            for (let j = 0; j < value.length; j++) {
                createLine(tableRef, value, j);
            }
            calculateLimit(paginatorType, value.length);
            toggleUser();
        }).catch((error) => {

        });
    });
}
