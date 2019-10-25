import React from 'react';

function exportToCsv(data, file_name) {
    let csv = '';
    data.forEach( (row) => {
        csv += row.join(',');
        csv += "\n";
    });
    let hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    hiddenElement.target = '_blank';
    hiddenElement.download = file_name+'.csv';
    hiddenElement.click();
}

export default (props) => {
    const { data, file_name } = props;
    return (
        <button className="wpcf-btn wpcf-btn-round wpcf-btn-outline wpcf-success-btn" onClick={e => exportToCsv(data, file_name)}>Download CSV</button>
    )
};
