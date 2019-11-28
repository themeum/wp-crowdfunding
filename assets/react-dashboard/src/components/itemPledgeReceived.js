import React from 'react';
import { __ } from '@wordpress/i18n'

export default (props) => {
    const { data:{ details, details: {billing, raised, receivable, marketplace, status, status_name} } } = props;
    return (
        <tr>
            <td><span className="td-title">{__("Name", "wp-crowdfunding")}</span>{ billing.first_name+' '+billing.last_name }</td>
            <td><span className="td-title">{__("Raised", "wp-crowdfunding")}</span><span dangerouslySetInnerHTML={{__html: raised}}/></td>
            <td><span className="td-title">{__("Receivable", "wp-crowdfunding")}</span><span dangerouslySetInnerHTML={{__html: receivable}}/></td>
            <td><span className="td-title">{__("Marketplace", "wp-crowdfunding")}</span><span dangerouslySetInnerHTML={{__html: marketplace}}/></td>
            <td className={"status " + (status && status)}><span className="td-title">{__("Status", "wp-crowdfunding")}</span>{ status_name } </td>
            <td><a href="javascript: void(0)" onClick={ () => props.onClickDetails( details ) }> <i>Details</i> </a></td>
        </tr>
    )
};
