import React from 'react';
import Header from "./contentHeader";
import decodeEntities from "../helpers/decodeEntities";

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
                        <div>
                            <h5>{ billing.first_name+' '+billing.last_name }</h5>
                            <span>{ (line_items.length) ? line_items[0].product_name : '' }</span>
                            <strong dangerouslySetInnerHTML={{__html: 'Pledged '+data.total}}></strong>
                        </div>
                        <div>
                            <h5>Payment Status</h5>
                            <strong>{ data.status_name }</strong>
                            <span>{ data.formatted_oc_date }</span>
                        </div>
                        <div>
                            <h5>Fulfillment</h5>
                            <strong>{data.fulfillment}</strong>
                        </div>
                    </div>
                </div>

                <div className="wpcf-dashboard-item-wraper ">
                    <h5 className="wpcf-billing-details-title">Billing Details</h5>
                    <div className="wpcf-dashboard-row">
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>First Name</span>
                                <strong>{ billing.first_name }</strong>
                            </div>
                        </div>
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Last Name</span>
                                <strong>{ billing.last_name }</strong>
                            </div>
                        </div>
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Company</span>
                                <strong>{ billing.company }</strong>
                            </div>
                        </div>
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Country</span>
                                <strong>{ billing.country_name }</strong>
                            </div>
                        </div>
                    </div>
                    <div className="wpcf-dashboard-row">
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Address</span>
                                <strong dangerouslySetInnerHTML={{__html: data.formatted_b_addr}}></strong>
                            </div>
                        </div>
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Post Code</span>
                                <strong>{ billing.postcode }</strong>
                            </div>
                        </div>
                    </div>
                    <div className="wpcf-dashboard-row">
                        <div className="wpcf-dashboard-col">
                            <div className="wpcf-order-details-item">
                                <span>Note</span>
                                <strong>{ data.customer_note }</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
};
