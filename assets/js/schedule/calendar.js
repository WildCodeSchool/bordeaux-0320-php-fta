import { addSchedule } from '../axios/ajaxSchedule';

let buttonSchedule = document.getElementById('button_schedule');

buttonSchedule.addEventListener('click', (e) => {
    let formSchedule = document.getElementById('form_schedule');
    let formData = new FormData(formSchedule);
    buttonSchedule.setAttribute('disabled', '');
    addSchedule(formData).then((value) => {
        buttonSchedule.removeAttribute('disabled');
        let tableRef = document.getElementById('toto').getElementsByTagName('tbody')[0];
        tableRef.innerHTML = '';
        for (let i = 0; i < value.length; i++) {
            // Insert a row in the table at row index 0
            let newRow = tableRef.insertRow(tableRef.rows.length);
            // Insert a cell in the row at index 0
            let newCell = newRow.insertCell(0);
            // Append a text node to the cell
            let newText = document.createTextNode(value[i].date);
            newCell.appendChild(newText);

            newCell = newRow.insertCell(1);
            newText = document.createTextNode(value[i].isAfternoon);
            newCell.appendChild(newText);

            newCell = newRow.insertCell(2);
            newText = document.createTextNode(value[i].isMorning);
            newCell.appendChild(newText);
        }
    }).catch((error) => {
        buttonSchedule.removeAttribute('disabled');
    });
});
