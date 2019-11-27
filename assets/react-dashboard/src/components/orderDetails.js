import React from 'react';
import Header from "./header";
import Icon from './Icon'


function getStatusColor(status) {
    switch( status.toLowerCase() ) {
        case 'cancelled':
        case 'failed':
        case 'refunded':
            return 'var(--wpcf-danger-color)';
        case 'on hold':
        case 'onhold':
            return 'var(--wpcf-warning-color)';
        case 'completed':
        case 'done':
            return 'var(--wpcf-success-color)';
        default:
            return 'var(--wpcf-primary-color)';
    }
}

export default (props) => {
    const { data, data:{ billing, shipping, line_items } } = props;
    return (
        <div>
            <Header title={"Order #" + data.id} subtitle={"Created " + data.formatted_c_date}>
                <button className="wpcf-btn wpcf-link-btn" onClick={ () => props.onClickBack( '' ) }>
                    <Icon className="wpcf-icon" name="arrowLeft"/>
                    Go Back Order
                </button>
            </Header>

            <div className="wpcf-dashboard-content-inner">

                <div className="wpcf-dashboard-item-wraper">
                    <h5 className="wpcf-billing-details-title">Order Details</h5>
                    <div className="wpcf-order-details">
                        <div className="wpcf-order-details-item wpcf-order-details-name-item">
                            <h5>{ billing.first_name+' '+billing.last_name }</h5>
                            <span>{ (line_items.length) ? line_items[0].product_name : '' }</span>
                            <strong dangerouslySetInnerHTML={{__html: 'Pledged '+data.total}}></strong>
                        </div>
                        <div className="wpcf-order-details-item">
                            <h5>Payment Status</h5>
                            <strong style={{color: getStatusColor(data.status_name)}}>{ data.status_name }</strong>
                            <span>{ data.formatted_oc_date }</span>
                        </div>
                        <div className="wpcf-order-details-item">
                            <h5>Fulfillment</h5>
                            <strong style={{color: getStatusColor(data.fulfillment)}}>{data.fulfillment}</strong>
                        </div>
                    </div>
                </div>

                <div className="wpcf-dashboard-item-wraper">
                    <h5 className="wpcf-billing-details-title">Billing Details</h5>
                    <div className="wpcf-dashboard-row">
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>First Name</span>
                                <strong>{ billing.first_name || '' }</strong>
                            </div>
                        </div>
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Last Name</span>
                                <strong>{ billing.last_name || '' }</strong>
                            </div>
                        </div>
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Company</span>
                                <strong>{ billing.company || '' }</strong>
                            </div>
                        </div>
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Country</span>
                                <strong>{ billing.country_name || '' }</strong>
                            </div>
                        </div>
                    </div>
                    <div className="wpcf-dashboard-row">
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Address</span>
                                <strong>{data.formatted_b_addr ? data.formatted_b_addr.replace(/<br\/>/g, ", ") : ''}</strong>
                            </div>
                        </div>
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Post Code</span>
                                <strong>{ billing.postcode || '' }</strong>
                            </div>
                        </div>
                    </div>
                    {
                        data.customer_note &&
                        <div className="wpcf-dashboard-row">
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Note</span>
                                    <strong>{ data.customer_note }</strong>
                                </div>
                            </div>
                        </div>
                    }
                </div>

                { shipping.first_name &&
                    <div className="wpcf-dashboard-item-wraper">
                        <h5 className="wpcf-billing-details-title">Shipping Details</h5>
                        <div className="wpcf-dashboard-row">
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>First Name</span>
                                    <strong>{ shipping.first_name || ''}</strong>
                                </div>
                            </div>
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Last Name</span>
                                    <strong>{ shipping.last_name || '' }</strong>
                                </div>
                            </div>
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Company</span>
                                    <strong>{ shipping.company || '' }</strong>
                                </div>
                            </div>
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Country</span>
                                    <strong>{ shipping.country_name || '' }</strong>
                                </div>
                            </div>
                        </div>
                        <div className="wpcf-dashboard-row">
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Address</span>
                                    <strong>{data.formatted_s_addr ? data.formatted_s_addr.replace(/<br\/>/g, ", ") : ''}</strong>
                                </div>
                            </div>
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Post Code</span>
                                    <strong>{ shipping.postcode || '' }</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                }
            </div>
        </div>
    )
};
