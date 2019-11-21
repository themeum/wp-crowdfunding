import React, {Component, Fragment} from 'react';
import { connect } from 'react-redux';
import { fetchInvestedCampaigns } from '../actions/campaignAction';
import ItemCampaign from '../components/itemCampaign';
import Pagination from '../components/pagination';
import Header from "../components/header";
import Skeleton from "../components/skeleton";

class InvestedCampaigns extends Component {
    state = {
        pageOfItems: [],
        filterValue: 'running',
        campaignReport: { id: '', name: '' },
    }

    componentDidMount() {
        const { loaded } = this.props.campaign;
        if( !loaded ) {
            this.props.fetchInvestedCampaigns();
        }
    }

    onChangePage = (pageOfItems) => {
        this.setState({ pageOfItems });
    }

    onClickReport = (campaignReport) => {
        this.setState({ campaignReport });
    }

    onClickFilter = (e) => {
        e.preventDefault();
        const filterValue = e.target.innerText.toLowerCase();
        this.setState({ filterValue });
    }

    getCampaignData = () =>  {
        const { filterValue } = this.state;
        const { campaign } = this.props;
        const filterData = campaign.data.filter( item => item.status == filterValue );
        return filterData;
    }

	render() {
        const { campaign } = this.props;
        if( campaign.loading ) {
            return (
                <Skeleton />
            )
        };

        const { pageOfItems, filterValue } = this.state;

        const campaignData = this.getCampaignData();

        return (
            <Fragment>
                <Header title={"Invested Campaigns"}></Header>
                <div className="wpcf-mycampaign-filter-group wpcf-btn-group">
                    <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='running'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Running</button>
                    <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='completed'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Completed</button>
                </div>
                <div className="wpcf-dashboard-content-inners">
                    { campaignData.length ?
                        <div>
                            { pageOfItems.map( (item, index) =>
                                <ItemCampaign
                                    key={index}
                                    data={ item }
                                    invested={ true }
                                />
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
            </Fragment>
        )
	}
}

const mapStateToProps = state => ({
    campaign: state.investedCampaign
})

export default connect( mapStateToProps, { fetchInvestedCampaigns } )(InvestedCampaigns);
