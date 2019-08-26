import React from 'react';

function getStatusColor(status) {
    switch( status ) {
        case 'cancelled':
        case 'failed':
            return '#c91818';
        case 'completed':
        case 'Done':
            return '#00a92f';
        default:
            return '#2b51a1';
    }
}

export default (props) => {
    const { data: {details, details:{ billing } } } = props;
    return (
        <tr>
            <td>#{ details.id }</td>
            <td>{ billing.first_name+' '+billing.last_name }</td>
            <td dangerouslySetInnerHTML={{__html:details.total}} />
            <td>{ details.formatted_c_date }</td>
            <td style={ {color: getStatusColor(details.status)} }>{ details.status_name }</td>
            <td style={ {color: getStatusColor(details.fulfillment)} }>{ details.fulfillment }</td>
            <td><span onClick={ () => props.onClickDetails( details ) }> Details </span></td>
        </tr>
    )
};