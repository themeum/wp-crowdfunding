import React, { Component, Fragment } from 'react';
import { connect } from 'react-redux';
import { fetchWithdrawMethods, saveWithdrawAccount } from '../actions/withdrawAction';
import ToastAlert from '../components/toastAlert';
import WithdrawMethodForm from '../components/withdrawMethodForm';
import Skeleton from "../components/skeleton";
import Header from '../components/header'
import Icon from '../components/Icon'

class WithdrawMethodSettings extends Component {

    componentDidMount() {
        const { withdrawMethod } = this.props;
        if( !withdrawMethod.loaded ) {
            this.props.fetchWithdrawMethods();
        }
    }

    componentDidUpdate(prevProps) {
        const { saveReq, error } = this.props.withdrawMethod;
        if ( saveReq !== prevProps.withdrawMethod.saveReq ) {
            if( saveReq == 'complete' ) {
                ToastAlert({
                    type: 'success',
                    message: 'Data saved'
                });
            } else if( saveReq == 'error' ) {
                ToastAlert({
                    type: 'error',
                    message: error
                });
            }
        }
    }

    onClickSaveData = (selected_method) => {
        this.props.saveWithdrawAccount( selected_method );
    }

	render() {
        const { loading, data } = this.props.withdrawMethod;

        if( loading ) {
            return (
                <Skeleton />
            );
        };

        return (
            <Fragment>

                <Header title="Select a withdraw method"/>

                <div className="wpcf-dashboard-content-inner">
                { Object.keys(data.methods).length ?
                    <div id="wpcf-withdraw-account-set-form">
                        <WithdrawMethodForm
                            data={ data }
                            onChangeData={ this.onChangeData }
                            onClickSaveData={ this.onClickSaveData }/>
                    </div>
                :  <div>
                        Data not found
                    </div>
                }
                </div>
            </Fragment>
        )
	}
}

const mapStateToProps = state => ({
    withdrawMethod: state.withdrawMethod,
})

export default connect( mapStateToProps, { fetchWithdrawMethods, saveWithdrawAccount } )(WithdrawMethodSettings);
