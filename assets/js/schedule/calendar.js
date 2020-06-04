import { addSchedule } from '../axios/ajaxSchedule';

function createLine(tableRef, value, i) {
    // Insert a row in the table at row index 0
    const newRow = tableRef.insertRow(tableRef.rows.length);
    // Insert a cell in the row at index 0
    let newCell = newRow.insertCell(0);
    // Append a text node to the cell
    let newText = document.createTextNode(value[i].date);
    newCell.appendChild(newText);

    newCell = newRow.insertCell(1);
    newCell.className = 'center-align';
    let icon = document.createElement('i');
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
}

const buttonSchedule = document.getElementById('button_schedule');

buttonSchedule.addEventListener('click', (e) => {
    const formSchedule = document.getElementById('form_schedule');
    const formData = new FormData(formSchedule);
    buttonSchedule.setAttribute('disabled', '');
    addSchedule(formData).then((value) => {
        buttonSchedule.removeAttribute('disabled');
        const tableRef = document.getElementById('toto').getElementsByTagName('tbody')[0];
        tableRef.innerHTML = '';
        for (let i = 0; i < value.length; i++) {
            createLine(tableRef, value, i);
        }
    }).catch((error) => {
        buttonSchedule.removeAttribute('disabled');
    });
});
