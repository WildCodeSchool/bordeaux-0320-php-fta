import { searchUser } from '../axios/ajaxSearch';

const toggleLoading = () => {
    const loading = document.getElementById('loading');
    const searchRow = document.getElementById('searchRow');
    searchRow.classList.toggle('display-loading');
    loading.classList.toggle('display-loading');
};

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

const createAction = (newCell, id) => {
    const aHref = document.createElement('a');
    aHref.setAttribute('href', `/admin/common/edit/${id}/volunteer`);
    aHref.appendChild(createIcon('edit'));
    aHref.setAttribute('class', 'admin-link');
    newCell.appendChild(aHref);
};

const createLine = (tableRef, value, i) => {
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
    const hrefEditTrip = value[i].status === 'beneficiary' ? 'beneficiary/trips/' : 'volunteer/schedule/';
    const iconUser = value[i].status === 'beneficiary' ? 'directions_car' : 'date_range';
    createA.setAttribute('href', `/admin/${hrefEditTrip}${value[i].id}`);
    createA.setAttribute('class', 'admin-link');
    createA.appendChild(createIcon(iconUser));
    newCell.appendChild(createA);

    newCell = newRow.insertCell(4);
    newCell.className = 'center-align';
    newCell.appendChild(createSwitch(value[i].isActive));

    newCell = newRow.insertCell(5);
    newCell.className = 'center-align';
    createAction(newCell, value[i].id);
};

const button = document.getElementById('searchButton');

button.addEventListener('click', () => {
    const searchInput = document.getElementById('searchUser');
    toggleLoading();
    const name = searchInput.value;
    const userType = searchInput.dataset.type;
    searchUser(name, userType).then((value) => {
        const tableRef = document.getElementById('users-table').getElementsByTagName('tbody')[0];
        tableRef.innerHTML = '';
        if (value.length > 0) {
            for (let i = 0; i < value.length; i++) {
                createLine(tableRef, value, i);
            }
        }
        toggleLoading();
    }).catch((error) => {
        toggleLoading();
    });
});
