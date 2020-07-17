import { showPage } from '../axios/ajaxPaginatorTrips';
import { createLine } from './createTripsRow';
import { calculateLimit } from './paginatorButton';

const paginators = document.getElementsByClassName('paginator');

for (let i = 0; i < paginators.length; i++) {
    paginators[i].addEventListener('click', (evt) => {
        const button = paginators[i];
        const paginatorType = button.dataset.paginator;
        const limit = Number(button.dataset.limit);
        showPage(limit).then((value) => {
            const tableRef = document.getElementById('trips-table').getElementsByTagName('tbody')[0];
            tableRef.innerHTML = '';
            for (let j = 0; j < value.length; j++) {
                createLine(tableRef, value, j);
            }
            calculateLimit(paginatorType, value.length);
        }).catch((error) => {

        });
    });
}
