document.addEventListener('DOMContentLoaded', () => {
    M.AutoInit();
    const datepickerBirthday = document.querySelectorAll('#mobicoop_form_birthDate, #mobicoop_admin_form_birthDate');
    const optionsForDatepickerBirthday = {
        autoClose: true,
        firstDay: 1,
        maxDate: new Date(),
        yearRange: [1950, 2010],
    };
    const instancesOfDatepickerBirthday = M.Datepicker.init(
        datepickerBirthday,
        optionsForDatepickerBirthday,
    );
    const datepickerTrip = document.querySelectorAll('#datepicker_trip, #datepicker_schedule');
    const optionsForDatepickerTrip = {
        autoClose: true,
        firstDay: 1,
        minDate: new Date(),
        yearRange: [2020, 2030],
    };
    const instancesOfDatepickerTrip = M.Datepicker.init(
        datepickerTrip,
        optionsForDatepickerTrip,
    );
    const modals = document.querySelectorAll('.modal');
    const instancesOfModal = M.Modal.init(modals);
    const fixedMenu = document.querySelectorAll('.fixed-action-btn');
    const instancesOfFixedMenu = M.FloatingActionButton.init(fixedMenu, {
        direction: 'left',
        hoverEnabled: false,
    });
});
