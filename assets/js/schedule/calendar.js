import { addSchedule } from '../axios/ajaxSchedule';

const createLine = (tableRef, value, i) => {
    const newRow = tableRef.insertRow(tableRef.rows.length);
    let newCell = newRow.insertCell(0);
    let newText = document.createTextNode(value[i].date);
    newCell.appendChild(newText);

    let icon = document.createElement('i');
    newCell = newRow.insertCell(1);
    newCell.className = 'center-align';
    icon.className = value[i].isMorning ? 'material-icons schedule-true' : 'material-icons schedule-false';
    newText = document.createTextNode('brightness_1');
    icon.appendChild(newText);
    newCell.appendChild(icon);


    newCell = newRow.insertCell(2);
    newCell.className = 'center-align';
    icon = document.createElement('i');
    icon.className = value[i].isAfternoon ? 'material-icons schedule-true' : 'material-icons schedule-false';
    newText = document.createTextNode('brightness_1');
    icon.appendChild(newText);
    newCell.appendChild(icon);
};

const buttonSchedule = document.getElementById('button_schedule');

buttonSchedule.addEventListener('click', (e) => {
    const formSchedule = document.getElementById('form_schedule');
    const formData = new FormData(formSchedule);
    buttonSchedule.setAttribute('disabled', '');
    addSchedule(formData).then((value) => {
        const tableRef = document.getElementById('calendar-table').getElementsByTagName('tbody')[0];
        buttonSchedule.removeAttribute('disabled');
        tableRef.innerHTML = '';
        for (let i = 0; i < value.length; i++) {
            createLine(tableRef, value, i);
        }
    }).catch((error) => {
        buttonSchedule.removeAttribute('disabled');
    });
});
