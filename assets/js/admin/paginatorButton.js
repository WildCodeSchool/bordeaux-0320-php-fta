const displayListNumber = 5;

export function calculateLimit(paginatorType, lenghtValue) {
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
