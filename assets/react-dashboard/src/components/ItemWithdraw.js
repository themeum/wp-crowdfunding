import React from 'react';

export default (props) => {
    const { data } = props;
    return (
        <tr>
            <td>{ data.campaign_title }</td>
            <td>{ data.raised_percentage }%</td>
            <td dangerouslySetInnerHTML={{__html: data.total_raised}} />
            <td dangerouslySetInnerHTML={{__html: data.total_receivable}} />
            <td><span onClick={ () => props.onClickWithdrawDetails( data ) }> Withdraw </span></td>
        </tr>
    )
};