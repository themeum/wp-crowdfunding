import React from 'react';

export default (props) => {
    const { data, data:{ billing, line_items } } = props;
    return (
        <div className="wpneo-modal-wrapper" style={ {display: 'block'} }>
            <div className="wpneo-modal-content">
                <div className="wpneo-modal-wrapper-head">
                    <h4 id="wpcf_modal_title">Pledge Details</h4>
                    <a href="javascript:void(0);" onClick={ () => props.onClickModalClose() } className="wpneo-modal-close">×</a>
                </div>
                <span className="wpcf-print-button button">print</span>
                <div className="wpneo-modal-content-inner">
                    <div id="wpcf_modal_message">
                        <div>
                            <div><span>Order ID:</span> {data.id}</div>
                            <div><span>Order Date:</span> {data.formatted_c_date}</div>
                            <div><span>Order Status:</span> {data.status_name}</div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{ line_items[0].product_name }</td>
                                        <td className="woocommerce-table__product-total product-total" dangerouslySetInnerHTML={{__html:data.raised}}/>
                                    </tr>
                                    { data.selected_reward &&
                                        <tr>
                                            <td colSpan="2" dangerouslySetInnerHTML={{__html:data.selected_reward}} />
                                        </tr>
                                    }
                                    <tr>
                                        <td>Subtotal:</td>
                                        <td dangerouslySetInnerHTML={{__html:data.subtotal}}/>
                                    </tr>
                                    <tr>
                                        <td>Payments Method:</td>
                                        <td>{data.payment_method_title}</td>
                                    </tr>
                                    <tr>
                                        <td>Total:</td>
                                        <td dangerouslySetInnerHTML={{__html:data.raised}}/>
                                    </tr>
                                </tbody>
                            </table>
                            <h3>Customer details</h3>
                            <table>
                                <tbody>
                                    <tr>
                                        <th>Note:</th>
                                        <td>{data.customer_note}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{billing.email}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{billing.phone}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <h3>Billing Address:</h3>
                            <address dangerouslySetInnerHTML={{__html:data.formatted_b_addr}} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
};