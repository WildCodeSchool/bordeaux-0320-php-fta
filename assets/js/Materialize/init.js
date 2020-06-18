document.addEventListener('DOMContentLoaded', () => {
    M.AutoInit();
    const datepickers = document.querySelectorAll('.datepicker');
    const optionsForDatepickers = {
        autoClose: true,
        firstDay: 1,
    };
    const instancesOfDatepickers = M.Datepicker.init(datepickers, optionsForDatepickers);
    const modals = document.querySelectorAll('.modal');
    const instancesOfModal = M.Modal.init(modals);
});
