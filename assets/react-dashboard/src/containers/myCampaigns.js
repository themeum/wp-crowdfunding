import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchCampaigns } from '../actions/campaignAction';
import ItemCampaign from '../components/itemCampaign';

class MyCampaigns extends Component {
	constructor (props) {
        super(props);
    }

    componentDidMount() {
        const { loaded } = this.props.campaign;
        if( !loaded ) {
            this.props.fetchCampaigns();
        }
    }
    
	render() {
        const { loading, data } = this.props.campaign;
        if( loading ) { 
            return (
                <div>
                    Loading...
                </div>
            ) 
        };
        return (
            <div className="wpcf-dashboard-content">
                <h3>My Campaigns</h3>
                <div className="wpcf-dashboard-content-inner">
                    { data.map( (campaign, index) => 
                        <ItemCampaign key={index} data={campaign} />
                    )}
                </div>
            </div>
        )
	}
}


const mapStateToProps = state => ({
    campaign: state.campaign
})

export default connect( mapStateToProps, { fetchCampaigns } )(MyCampaigns);