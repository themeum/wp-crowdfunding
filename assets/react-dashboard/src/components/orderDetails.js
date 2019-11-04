import React from 'react';
import Header from "./header";


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
    const { data, data:{ billing, line_items } } = props;
    return (
        <div>
            <Header title={"Order #" + data.id} subtitle={"Created " + data.formatted_c_date}>
                <button className="wpcf-btn wpcf-link-btn" onClick={ () => props.onClickBack( '' ) }>
                    <span className="wpcf-icon fas fa-long-arrow-alt-left"></span>
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
                            {
                                billing.first_name &&
                                <div className="wpcf-order-details-item">
                                    <span>First Name</span>
                                    <strong>{ billing.first_name }</strong>
                                </div>
                            }
                        </div>
                        {
                            billing.last_name &&
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Last Name</span>
                                    <strong>{ billing.last_name }</strong>
                                </div>
                            </div>
                        }
                        {
                            billing.company &&
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Company</span>
                                    <strong>{ billing.company }</strong>
                                </div>
                            </div>
                        }
                        {
                            billing.country_name &&
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Country</span>
                                    <strong>{ billing.country_name }</strong>
                                </div>
                            </div>
                        }
                    </div>
                    <div className="wpcf-dashboard-row">
                        {
                            console.log(data.formatted_b_addr)
                        }
                        {
                            data.formatted_b_addr &&
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Address</span>
                                    <strong>{data.formatted_b_addr.replace(/<br\/>/g, ", ")}</strong>
                                </div>
                            </div>
                        }
                        {
                            billing.postcode &&
                            <div className="wpcf-dashboard-col">
                                <div className="wpcf-order-details-item">
                                    <span>Post Code</span>
                                    <strong>{ billing.postcode }</strong>
                                </div>
                            </div>
                        }
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
            </div>
        </div>
    )
};
