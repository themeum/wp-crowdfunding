import React from 'react';

export default (props) => {
    const { data:{ details, details: {billing, raised, receivable, marketplace, status} } } = props;
    return (
        <tr>
            <td>{ billing.first_name+' '+billing.last_name }</td>
            <td dangerouslySetInnerHTML={{__html:raised}} />
            <td dangerouslySetInnerHTML={{__html:receivable}} />
            <td dangerouslySetInnerHTML={{__html:marketplace}} />
            <td>{ status }</td>
            <td><i className="fa fa-envelope"></i></td>
            <td><span onClick={ () => props.onClickDetails( details ) }> Details </span></td>
        </tr>
    )
};