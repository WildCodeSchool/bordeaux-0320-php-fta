const numberActionIcon = 2;
const listActions = ['edit', 'delete'];
const actionsHref = ['/admin/common/edit/', '/admin/common/delete'];

export function toggleLoading() {
    const loading = document.getElementById('loading');
    const searchRow = document.getElementById('searchRow');
    searchRow.classList.toggle('display-loading');
    loading.classList.toggle('display-loading');
}

const createSwitch = (isActive) => {
    const div = document.createElement('div');
    const label = document.createElement('label');
    const input = document.createElement('input');
    const span = document.createElement('span');

    span.className = 'lever';
    input.setAttribute('type', 'checkbox');
    input.checked = isActive;
    div.className = 'switch';

    label.appendChild(input);
    label.appendChild(span);
    div.appendChild(label);

    return div;
};

const createIcon = (name) => {
    const icon = document.createElement('i');
    icon.className = 'material-icons';
    const newText = document.createTextNode(name);
    icon.appendChild(newText);

    return icon;
};

const createActions = (newCell, id) => {
    for (let j = 0; j < numberActionIcon; j++) {
        const aHref = document.createElement('a');
        aHref.setAttribute('href', actionsHref[j] + id);
        aHref.appendChild(createIcon(listActions[j]));
        newCell.appendChild(aHref);
    }
};

export function createLine(tableRef, value, i) {
    const newRow = tableRef.insertRow(tableRef.rows.length);

    let newCell = newRow.insertCell(0);
    newCell.className = 'center-align';
    let newText = document.createTextNode(i + 1);
    newCell.appendChild(newText);

    newCell = newRow.insertCell(1);
    newCell.className = 'center-align';
    newText = document.createTextNode(value[i].givenName);
    newCell.appendChild(newText);

    newCell = newRow.insertCell(2);
    newCell.className = 'center-align';
    newText = document.createTextNode(value[i].familyName);
    newCell.appendChild(newText);

    newCell = newRow.insertCell(3);
    newCell.className = 'center-align';
    const createA = document.createElement('a');
    const hrefEditTrip = value[i].status === 'beneficiary' ? 'beneficiary/trip/' : 'volunteer/schedule/';
    createA.setAttribute('href', `/admin/${hrefEditTrip} ${value[i].id}`);
    createA.appendChild(createIcon('date_range'));
    newCell.appendChild(createA);

    newCell = newRow.insertCell(4);
    newCell.className = 'center-align';
    newCell.appendChild(createSwitch(value[i].isActive));

    newCell = newRow.insertCell(5);
    newCell.className = 'center-align';
    createActions(newCell, value[i].id);
}
