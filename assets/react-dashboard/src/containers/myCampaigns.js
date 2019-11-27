import React, { Component, Fragment } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { fetchMyCampaigns, deleteCampaign } from '../actions/campaignAction';
import ConfirmAlert from '../components/confirmAlert';
import CampaignReport from '../containers/campaignReport';
import ItemCampaign from '../components/itemCampaign';
import CampaignUpdate from '../components/campaignUpdate';
import Pagination from '../components/pagination';
import Header from '../components/header';
import Skeleton from '../components/skeleton'
import Icon from '../components/Icon'

class MyCampaigns extends Component {
    state = {
        pageOfItems: [],
        filterValue: 'running',
        campaignReport: { id: '', name: '' },
        campaignId: '',
        updates: [],
    }

    componentDidMount() {
        const { loaded } = this.props.campaign;
        if( !loaded ) {
            this.props.fetchMyCampaigns();
        }
    }

    onChangePage = (pageOfItems) => {
        this.setState({ pageOfItems });
    }

    onClickFilter = (e) => {
        e.preventDefault();
        const filterValue = e.target.innerText.toLowerCase();
        this.setState({ filterValue });
    }

    onClickReport = (campaignReport) => {
        this.setState({ campaignReport });
    }

    onClickUpdates = (campaignId, updates) => {
        this.setState({ campaignId, updates });
    }

    onClickDelete = (campaignId) => {
        const data = {
            id: campaignId,
            bookmark: false
        }
        ConfirmAlert({
            title: 'Confirm to submit',
            message: 'Are you sure to do this.',
            buttons: [
                {
                    label: 'Yes',
                    onClick: () => this.props.deleteCampaign(data)
                },
                {
                    label: 'No'
                }
            ],
            /* childrenElement: () =>
            <div className="wpcf-campaign-links">
                <a title="Edit"><i className="far fa-edit"></i></a>
            </div>, */
        });
    }

    getCampaignData = () => {
        const { filterValue } = this.state;
        const { campaign } = this.props;
        const filterData = campaign.data.filter( item => item.status == filterValue );
        return filterData;
    }

	render() {
        const { campaign } = this.props;
        const { pageOfItems, filterValue, campaignReport, campaignId, updates } = this.state;
        if( campaign.loading ) {
            return (
                <Skeleton />
            )
        };

        if( campaignReport.id ) {
            return (
                <CampaignReport
                    campaign={ campaignReport }
                    onClickBack={ this.onClickReport }
                />
            );
        }

        if( campaignId ) {
            return (
                <CampaignUpdate
                    updates={ updates }
                    campaignId={ campaignId }
                    onClickUpdates={ this.onClickUpdates }
                />
            );
        }

        const campaignData = this.getCampaignData();

        return (
            <Fragment>
                <Header title={"My Campaigns"} />
                <div className='wpcf-mycampaign-filter-group wpcf-btn-group'>
                    <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='running'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Running</button>
                    <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='pending'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Pending</button>
                    <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='draft'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Draft</button>
                    <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='completed'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Completed</button>
                </div>
                <div className="wpcf-dashboard-content-inner">
                    { campaignData.length ?
                        <Fragment>
                            { pageOfItems.map( (item, index) =>
                                <ItemCampaign key={index} data={item}>
                                    { item.access.manage ? (
                                        <div className="wpcf-campaign-links">
                                            <button aria-label="Report" title="Report" onClick={ () => this.onClickReport({id:item.id, name:item.title}) }>
                                                <Icon name="graph"/>
                                            </button>
                                            <button aria-label="Updates" title="Updates" onClick={ () => this.onClickUpdates(item.id, item.updates) }>
                                                <Icon name="update"/>
                                            </button>
                                            <a href={item.edit_link} aria-label="Edit" title="Edit">
                                                <Icon name="pen"/>
                                            </a>
                                            <button aria-label="Delete" title="Delete" onClick={ () => this.onClickDelete(item.id) }>
                                                <Icon name="trash"/>
                                            </button>
                                        </div>
                                    ) : item.access.edit ? (
                                        <div className="wpcf-campaign-links">
                                            <a href={item.edit_link} aria-label="Edit" title="Edit">
                                                <Icon name="pen"/>
                                            </a>
                                        </div>
                                    ) : ''}
                                </ItemCampaign>
                            ) }
                            <Pagination
                                items={ campaignData }
                                pageSize={ 5 }
                                filterValue={ filterValue }
                                onChangePage={ this.onChangePage } />
                        </Fragment>
                    :   <Fragment>
                            Campaign not found
                        </Fragment>
                    }

                </div>
            </Fragment>
        )
	}
}

const mapStateToProps = state => ({
    campaign: state.myCampaign
})

const mapDispatchToProps = dispatch => {
    return bindActionCreators({
        fetchMyCampaigns,
        deleteCampaign
    }, dispatch);
}

export default connect( mapStateToProps, mapDispatchToProps )(MyCampaigns);
