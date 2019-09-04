import React, { Component } from 'react';
import { Link } from "react-router-dom";
import { connect } from 'react-redux';
import { postWithdrawRequest } from '../actions/withdrawAction';

class WithdrawDetails extends Component {
    constructor(props) {
        super(props);
        this.state = {
            withdraw_amount: '',
            withdraw_message: '',
            withdraw_method: '',
            errorMsg: '',
        };
        this.onChangeInput = this.onChangeInput.bind(this);
        this.onSubmitWithdrawReq = this.onSubmitWithdrawReq.bind(this);
    }

    componentDidUpdate(prevProps, prevState) {
        const { withdraw, withdraw: { reqStatus } } = this.props;
        if ( reqStatus !== prevProps.withdraw.reqStatus ) {
            if( reqStatus == 'complete' ) {
                this.setState({
                    withdraw_amount: '',
                    withdraw_message: '',
                    withdraw_method: '',
                    errorMsg: ''
                });
            }
            if( reqStatus == 'error' ) {
                this.setState({
                    errorMsg: withdraw.error
                });
            }
        }
    }

    onChangeInput(e) {
        this.setState( { [e.target.name]: e.target.value } );
    }

    onSubmitWithdrawReq(e) {
        e.preventDefault();
        const { campaign_id } = this.props.data;
        const { withdraw_amount } = this.state;
        if( withdraw_amount <= 0 ) {
            this.setState({ errorMsg: "Please enter valid amount"});
            return false;
        }
        let postData = { ...this.state, campaign_id };
        delete postData.errorMsg; //remove error msg from postData
        //Send withdraw request
        this.props.postWithdrawRequest( postData );
    }

    render() {
        const { data, data: { withdraw, methods }, onClickBack } = this.props;
        const { withdraw_amount, withdraw_message, withdraw_method, errorMsg } = this.state;

        return (
            <div className="wpcf-dashboard-content">
                <h3>{data.campaign_title} <a href="javascript:void(0)" onClick={() => onClickBack('')}> Back </a></h3>
                <div className="wpcf-dashboard-content-inner">
                    {withdraw.request_items.length > 0 &&
                        <div className="wpcf-dashboard-info-table-wrap">
                            <table className="wpcf-dashboard-info-table">
                                <thead>
                                    <tr>
                                        <th>#Title</th>
                                        <th>#Amount</th>
                                        <th>#Method</th>
                                        <th>#Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {withdraw.request_items.map((item, index) =>

                                        <tr key={index}>
                                            <td dangerouslySetInnerHTML={{ __html: item.title }} />
                                            <td dangerouslySetInnerHTML={{ __html: item.amount }} />
                                            <td> {item.method} </td>
                                            <td> 
                                                { item.status == 'paid' ?
                                                    <span className="label-success">Paid</span>
                                                :   <span className="label-warning">Not Paid</span>
                                                }
                                            </td>
                                        </tr>
                                    )}
                                    <tr>
                                        <td colSpan="1"></td>
                                        <td><strong>Receivable</strong></td>
                                        <td dangerouslySetInnerHTML={{ __html: data.total_receivable }} />
                                    </tr>
                                    <tr>
                                        <td colSpan="1"></td>
                                        <td> <strong> Total Withdraw </strong> </td>
                                        <td dangerouslySetInnerHTML={{ __html: withdraw.total_withdraw }} />
                                    </tr>
                                    <tr>
                                        <td colSpan="1"></td>
                                        <td> <strong> Balance </strong> </td>
                                        <td dangerouslySetInnerHTML={{ __html: withdraw.balance }} />
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    }

                    <div className="withdraw-method-forms-wrap">
                        <form className="withdraw-method-form" onSubmit={ this.onSubmitWithdrawReq }>
                            { errorMsg &&
                                <div className="alert alert-danger">{errorMsg}</div>
                            }
                            <div className="withdraw-method-field-wrap">
                                <label htmlFor="wpcf_withdraw_amount">Amount</label>
                                <input id="wpcf_withdraw_amount" type="number" name="withdraw_amount" value={ withdraw_amount } onChange={ this.onChangeInput } required/>
                                <p className="withdraw-field-desc">Remain Amount <span dangerouslySetInnerHTML={{ __html: withdraw.balance }} /></p>
                            </div>
                            <div className="withdraw-method-field-wrap">
                                <label htmlFor="wpcf_withdraw_message">Message</label>
                                <input id="wpcf_withdraw_message" type="textarea" name="withdraw_message" value={ withdraw_message } onChange={ this.onChangeInput }/>
                            </div>
                            <div className="withdraw-method-select-wrap">
                                { Object.keys( methods.data ).map( (key) =>
                                    <div key={ key } className="withdraw-method-select">
                                        <input type="radio" id={`wpcf_withdraw_method_${key}`} className="withdraw-method-select-input" name="withdraw_method" value={ key } onChange={ this.onChangeInput } required checked={ withdraw_method == key ? true : false }/>
                                        <label htmlFor={`wpcf_withdraw_method_${key}`} className={ withdraw_method == key ? 'active' : '' }>
                                            <p>{methods.data[key].method_name}</p>
                                            <span dangerouslySetInnerHTML={{__html: data.min_withdraw}}/>
                                            <Link to="/settings/withdraw">Change info</Link>
                                        </label>
                                    </div>
                                )}
                            </div>
                            <div className="withdraw-account-save-btn-wrap">
                                <button type="submit" className="wpcf-btn">Confirm Withdrawal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        )
    }
}

const mapStateToProps = state => ({
    withdraw: state.withdraw
})

export default connect( mapStateToProps, { postWithdrawRequest })(WithdrawDetails);