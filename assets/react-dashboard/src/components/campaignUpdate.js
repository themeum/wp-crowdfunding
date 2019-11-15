import React, { Component, Fragment } from 'react';
import { connect } from 'react-redux';
import { saveCampaignUpdates } from '../actions/campaignAction';
import DatePicker from './datePicker';
import Header from "./header";

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
            <Fragment>
                <Header title={"Updates"}>
                    <button className="wpcf-btn wpcf-link-btn" onClick={ () => this.props.onClickUpdates( '', '' ) }>
                        <span className="wpcf-icon fas fa-long-arrow-alt-left"></span>
                        Go Back My Campaigns
                    </button>
                </Header>
                <div className="wpcf-dashboard-content-inner">
                    <form onSubmit={ this.onSubmitUpates }>
                        { updates.length > 0 &&
                            updates.map( ( item, index ) =>
                                <div key={index} className="wpcf-dashboard-item-wraper">
                                    <div className="wpcf-form-group">
                                        <label>Date: </label>
                                        <DatePicker placeholder="2040/12/16" name="date" value={item.date} onChange={ e => this.onChangeInput(e, index) } format="yy-mm-dd"/>
                                    </div>
                                    <div className="wpcf-form-group">
                                        <label>Update Title:</label>
                                        <input placeholder="Update Title" type="text"name="title" value={ item.title } onChange={ (e) => this.onChangeInput(e, index) } required/>
                                    </div>
                                    <div className="wpcf-form-group">
                                        <label>Update Details:</label>
                                        <textarea placeholder="Update description" name="details" onChange={ (e) => this.onChangeInput(e, index) } required>{ item.details }</textarea>
                                    </div>
                                    <button className="wpcf-btn wpcf-btn-round wpcf-btn-outline wpcf-btn-danger wpcf-btn-sm" type="button" onClick={ () => this.removeItem(index) }>
                                        <span className="far fa-trash-alt wpcf-icon"></span> Remove</button>
                                </div>
                        )}

                        <div className="wpcf-update-btn-group wpcf-btn-group">
                            <button type="button" className="wpcf-btn wpcf-btn-round" onClick={ this.addItem }><span className="wpcf-icon fas fa-plus"></span> Add Update</button>
                            <button style={{marginLeft: 'auto'}} type="button" className="wpcf-btn wpcf-btn-round wpcf-btn-outline wpcf-btn-warning" onClick={() => this.props.onClickUpdates( '', '' )}> Back </button>
                            <button type="submit" className="wpcf-btn wpcf-btn-round wpcf-btn-success"><span className="wpcf-icon far fa-save"></span>Save Updates</button>
                        </div>
                    </form>
                </div>
            </Fragment>
        )
    }
}

const mapStateToProps = state => ({
    campaign: state.myCampaign
})

export default connect( mapStateToProps, { saveCampaignUpdates })(CampaignUpdate);
