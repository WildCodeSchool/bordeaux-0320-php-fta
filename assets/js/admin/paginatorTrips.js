import { showPage } from '../axios/ajaxPaginatorTrips';
import { createLine } from './createTripsRow';

const paginators = document.getElementsByClassName('paginator');
const displayListNumber = 5;

const calculateLimit = (paginatorType, lenghtValue) => {
    const buttonNext = document.getElementById('next');
    const buttonNextLimit = Number(buttonNext.dataset.limit);
    const buttonPrevious = document.getElementById('previous');
    const buttonPreviousLimit = Number(buttonPrevious.dataset.limit);
    if (paginatorType === 'next') {
        buttonNext.dataset.limit = buttonNextLimit + displayListNumber;
        if (buttonNextLimit % 10 === 0) {
            buttonPrevious.dataset.limit = buttonPreviousLimit + displayListNumber;
        }
        if (buttonNextLimit + displayListNumber === 10 && buttonPrevious.classList[4] === 'display-button') {
            buttonPrevious.classList.toggle('display-button');
        }
        if (lenghtValue < displayListNumber) {
            buttonNext.classList.toggle('display-button');
        }
    }
    if (paginatorType === 'previous') {
        if (buttonNext.classList[4] === 'display-button') {
            buttonNext.classList.toggle('display-button');
        }
        if (buttonPreviousLimit - displayListNumber >= 0) {
            buttonPrevious.dataset.limit = buttonPreviousLimit - displayListNumber;
        }
        if (buttonNextLimit - displayListNumber >= displayListNumber) {
            buttonNext.dataset.limit = buttonNextLimit - displayListNumber;
        }
        if (buttonPreviousLimit - displayListNumber < 0) {
            buttonPrevious.classList.toggle('display-button');
        }
    }
};

for (let i = 0; i < paginators.length; i++) {
    paginators[i].addEventListener('click', (evt) => {
        const button = evt.target;
        const paginatorType = button.dataset.paginator;
        const limit = Number(button.dataset.limit);
        showPage(limit).then((value) => {
            const tableRef = document.getElementById('users-table').getElementsByTagName('tbody')[0];
            tableRef.innerHTML = '';
            for (let j = 0; j < value.length; j++) {
                createLine(tableRef, value, j);
            }
            calculateLimit(paginatorType, value.length);
        }).catch((error) => {

        });
    });
}
