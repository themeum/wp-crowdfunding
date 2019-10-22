import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchInvestedCampaigns } from '../actions/campaignAction';
import ItemCampaign from '../components/itemCampaign';
import Pagination from '../components/pagination';

class InvestedCampaigns extends Component {
	constructor (props) {
        super(props);
        this.state = {
            pageOfItems: [],
            filterValue: 'running'
        };
        this.onChangePage = this.onChangePage.bind(this);
    }

    componentDidMount() {
        const { loaded } = this.props.campaign;
        if( !loaded ) {
            this.props.fetchInvestedCampaigns();
        }
    }

    onChangePage(pageOfItems) {
        this.setState({ pageOfItems });
    }

    onClickFilter(e) {
        e.preventDefault();
        const filterValue = e.target.innerText.toLowerCase();
        this.setState({ filterValue });
    }

    getCampaignData() {
        const { filterValue } = this.state;
        const { campaign } = this.props;
        const filterData = campaign.data.filter( item => item.status == filterValue );
        return filterData;
    }

	render() {
        const { campaign } = this.props;
        if( campaign.loading ) { 
            return (
                <div>
                    Loading...
                </div>
            )
        };

        const { pageOfItems, filterValue } = this.state;
        const campaignData = this.getCampaignData();
        
        return (
            <div>
                <h3>My Campaigns</h3>
                <div>
                    <span onClick={ e => this.onClickFilter(e) }>Running</span>
                    <span onClick={ e => this.onClickFilter(e) }>Pending</span>
                    <span onClick={ e => this.onClickFilter(e) }>Draft</span>
                    <span onClick={ e => this.onClickFilter(e) }>Completed</span>
                </div>
                <div className="wpcf-dashboard-content-inner">
                    { campaignData.length ?
                        <div>
                            { pageOfItems.map( (item, index) =>
                                <ItemCampaign 
                                    key={index}
                                    data={ item }
                                    pledge={ true }/>
                            ) }
                            <Pagination
                                items={ campaignData }
                                pageSize={ 5 }
                                filterValue={ filterValue }
                                onChangePage={ this.onChangePage } />
                        </div>
                    :   <div>
                            Campaign not found
                        </div>
                    }
                        
                </div>
            </div>
        )
	}
}

const mapStateToProps = state => ({
    campaign: state.investedCampaign
})

export default connect( mapStateToProps, { fetchInvestedCampaigns } )(InvestedCampaigns);