import React from 'react';
import { wcPice } from "../helper";
import { __ } from '@wordpress/i18n'


export default (props) => {
    const { data: {details, details:{ billing } } } = props;
    return (
        <tr>
            <td>#{ details.id }</td>
            <td><span className="td-title">{__("Name", "wp-crowdfunding")}</span> { billing.first_name+' '+billing.last_name }</td>
            <td><span className="td-title">{__("Total", "wp-crowdfunding")}</span><span dangerouslySetInnerHTML={{__html: wcPice(details.total)}}/></td>
            <td><span className="td-title">{__("Date", "wp-crowdfunding")}</span>{ details.formatted_c_date }</td>
            <td className={"status " + (details.status && details.status)}><span className="td-title">{__("Status", "wp-crowdfunding")}</span>{ details.status_name }</td>
            <td className={"status2 " + (details.fulfillment && details.fulfillment)}><span className="td-title">{__("Fulfillment", "wp-crowdfunding")}</span>{ details.fulfillment }</td>
            <td><a href="javascript:" onClick={ () => props.onClickDetails( details ) }> <i>Details</i> </a></td>
        </tr>
    )
};
