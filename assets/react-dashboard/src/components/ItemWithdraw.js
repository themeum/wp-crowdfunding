import React from 'react';

export default (props) => {
    const { data } = props;
    return (
        <tr>
            <td>{ data.campaign_title }</td>
            <td>{ data.raised_percentage }%</td>
            <td dangerouslySetInnerHTML={{__html: data.total_raised}} />
            <td dangerouslySetInnerHTML={{__html: data.total_receivable}} />
            <td><button className="wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-sm" onClick={ () => props.onClickWithdrawDetails( data ) }> Withdraw </button></td>
        </tr>
    )
};
