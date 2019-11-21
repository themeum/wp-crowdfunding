import React from 'react';

function getStatusColor(status) {
    switch( status ) {
        case 'cancelled':
        case 'failed':
            return '#c91818';
        case 'completed':
            return '#00a92f';
        default:
            return '#2b51a1';
    }
}

export default (props) => {
    const { data:{ details, details: {billing, raised, receivable, marketplace, status, status_name} } } = props;
    return (
        <tr>
            <td>{ billing.first_name+' '+billing.last_name }</td>
            <td dangerouslySetInnerHTML={{__html:raised}} />
            <td dangerouslySetInnerHTML={{__html:receivable}} />
            <td dangerouslySetInnerHTML={{__html:marketplace}} />
            <td style={ {color: getStatusColor(status), textTransform: 'uppercase'} }>{ status_name }</td>
            {/* <td><i className="fa fa-envelope"></i></td> */}
            <td><a href="javascript: void(0)" onClick={ () => props.onClickDetails( details ) }> <i>Details</i> </a></td>
        </tr>
    )
};
