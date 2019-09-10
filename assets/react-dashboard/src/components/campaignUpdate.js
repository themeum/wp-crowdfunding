import React, { Component } from 'react';
import { connect } from 'react-redux';
import { saveCampaignUpdates } from '../actions/campaignAction';
import DatePicker from '../components/datePicker';

class CampaignUpdate extends Component {
    constructor(props) {
        super(props);
        this.state = { campaignId: this.props.campaignId, updates: this.props.updates };
        this.addItem = this.addItem.bind(this);
        this.removeItem = this.removeItem.bind(this);
        this.onChangeInput = this.onChangeInput.bind(this);
        this.onSubmitUpates = this.onSubmitUpates.bind(this);
    }

    componentDidUpdate(prevProps, prevState) {
        const { saveReq, error } = this.props.campaign;
        if ( saveReq !== prevProps.campaign.saveReq ) {
            if( saveReq == 'complete' ) {
                alert( 'data saved' );
            }
            if( saveReq == 'error' ) {
                alert( error );
            }
        }
    }

    addItem() {
        const updates = [ ...this.state.updates, { date:'', title:'', details:'' } ];
        this.setState( { updates } );
    }

    removeItem(index) {
        let updates = [ ...this.state.updates ];
        updates.splice(index, 1);
        this.setState( { updates } );
    }

    onChangeInput(e, index) {
        let updates = [ ...this.state.updates ];
        updates[index][e.target.name] = e.target.value;
        this.setState( updates );
    }

    onSubmitUpates(e) {
        e.preventDefault();
        let postData = { ...this.state };
        this.props.saveCampaignUpdates( postData );
    }

    render() {
        const { updates } = this.state;
        return (
            <div className="wpcf-dashboard-content">
                <h3>Updates</h3>
                <div className="wpcf-dashboard-content-inner">
                    <div className="withdraw-method-forms-wrap">
                        <form className="withdraw-method-form" onSubmit={ this.onSubmitUpates }>
                            { updates.length > 0 && 
                                updates.map( ( item, index ) =>
                                    <div key={index} className="">
                                        <DatePicker name="date" value={item.date} onChange={ e => this.onChangeInput(e, index) } format="yy-mm-dd"
                                        />
                                        <div className="">
                                            <label>Update Title:</label>
                                            <input type="text"name="title" value={ item.title } onChange={ (e) => this.onChangeInput(e, index) } required/>
                                        </div>
                                        <div className="">
                                            <label>Update Details:</label>
                                            <input type="textarea" name="details" value={ item.details } onChange={ (e) => this.onChangeInput(e, index) } required/>
                                        </div>
                                        <button type="button" onClick={ () => this.removeItem(index) }>Remove</button>
                                    </div>
                            )}

                            <button type="button" onClick={ this.addItem }>Add Update</button>
                            

                            <div className="withdraw-account-save-btn-wrap">
                                <button type="submit" className="wpcf-btn">Save Updates</button>
                                <button type="button" onClick={() => this.props.onClickUpdates( '', '' )}> Back </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        )
    }
}

const mapStateToProps = state => ({
    campaign: state.myCampaign
})

export default connect( mapStateToProps, { saveCampaignUpdates })(CampaignUpdate);