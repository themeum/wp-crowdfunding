import React from 'react';

export default (props) => {
    const { data, data:{ billing, line_items } } = props;
    return (
        <div className="wpcf-dashboard-content">
            <a href="javascript:void(0)" onClick={ () => props.onClickBack( '' ) }>Go Back Order List</a>
            <h3>Order #{ data.id }</h3>
            <p>Created { data.formatted_c_date }</p>
            <div className="wpcf-dashboard-content-inner">
                <div>
                    <h5>Order Details</h5>
                    <div>
                        <p>{ billing.first_name+' '+billing.last_name }</p>
                        <p>{ (line_items.length) ? line_items[0].product_name : '' }</p>
                        <p dangerouslySetInnerHTML={{__html: 'Pledged '+data.total}} />
                    </div>
                    <div>
                        <p>Payment Status</p>
                        <p>{ data.status_name }</p>
                        <p>{ data.formatted_oc_date }</p>
                    </div>
                    <div>
                        <p>Fulfillment</p>
                        <p>{ data.fulfillment }</p>
                    </div>
                </div>

                <div>
                    <h5>Billing Details</h5>
                    <div>
                        <p>First Name</p>
                        <p>{ billing.first_name }</p>
                    </div>
                    <div>
                        <p>Last Name</p>
                        <p>{ billing.last_name }</p>
                    </div>
                    <div>
                        <p>Company</p>
                        <p>{ billing.company }</p>
                    </div>
                    <div>
                        <p>Country</p>
                        <p>{ billing.country_name }</p>
                    </div>
                    <div>
                        <p>Address</p>
                        <p>{ data.formatted_b_addr }</p>
                    </div>
                    <div>
                        <p>Zip Code</p>
                        <p>{ billing.postcode }</p>
                    </div>
                    <div>
                        <p>Note</p>
                        <p>{ data.customer_note }</p>
                    </div>
                </div>
            </div>
        </div>
    )
};