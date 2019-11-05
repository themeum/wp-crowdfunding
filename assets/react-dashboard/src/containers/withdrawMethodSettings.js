import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchWithdrawMethods, saveWithdrawAccount } from '../actions/withdrawAction';
import WithdrawMethodForm from '../components/withdrawMethodForm';
import Skeleton from "../components/skeleton";

class WithdrawMethodSettings extends Component {
	constructor (props) {
        super(props);
        this.onClickSaveData = this.onClickSaveData.bind(this);
    }

    componentDidMount() {
        const { withdrawMethod } = this.props;
        if( !withdrawMethod.loaded ) {
            this.props.fetchWithdrawMethods();
        }
    }

    componentDidUpdate(prevProps, prevState) {
        const { saveReq, error } = this.props.withdrawMethod;
        if ( saveReq !== prevProps.withdrawMethod.saveReq ) {
            if( saveReq == 'complete' ) {
                alert( 'data saved' );
            }
            if( saveReq == 'error' ) {
                alert( error );
            }
        }
    }

    onClickSaveData( selected_method ) {
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
            <div>
                <h3>Select a withdraw method</h3>
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
            </div>
        )
	}
}

const mapStateToProps = state => ({
    withdrawMethod: state.withdrawMethod,
})

export default connect( mapStateToProps, { fetchWithdrawMethods, saveWithdrawAccount } )(WithdrawMethodSettings);
