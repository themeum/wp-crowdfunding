import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchCampaignsReport } from '../actions/campaignAction';
import DatePicker from '../components/datePicker';
import LineGraph from '../components/lineGraph';
import PledgeReports from '../components/pledgeReports';
import ExportCSV from '../components/exportCSV';
import Header from './contentHeader'

class CampaignReport extends Component {
	constructor (props) {
        super(props);
		this.state  = {
            query_args: { 
                date_range: 'last_7_days',
                campaign_id: (this.props.campaign.id) ? this.props.campaign.id : ''
            },
            option_params: {
                last_7_days: 'Last Week',
                last_14_days: 'Last 14 Days',
                this_month: 'This Month',
                last_3_months: 'Last 3 Months',
                last_6_months: 'Last 6 Months',
                this_year: 'This Year'
            }
        };
        this._onChange = this._onChange.bind(this);
    }

    componentDidMount() {
        let { query_args } = this.state;
        this.props.fetchCampaignsReport( this.encodeQueryArgs(query_args) );
    }

    encodeQueryArgs(data) {
        const args = [];
        for (let key in data) {
            args.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
        }
        return args.join('&');
    }

    _onChange(e) {
        let { query_args, query_args: {campaign_id} } = this.state;
        const{ name, value } = e.target;
        query_args = (name =="date_range") ? {date_range: value, campaign_id} : Object.assign(query_args, {[name]: value});
        this.props.fetchCampaignsReport( this.encodeQueryArgs(query_args) );
        this.setState( { query_args } );
    }

	render () {
        const { query_args, option_params } = this.state;
        const { loading, data:{ csv, format, label, fundRaised, raisedPercent, totalBacked, pledges } } = this.props.report;
        
        if (loading) {
            return (
                <div>
                    Loading...
                </div>
            )
        };
        
        return (
            <div>
                <Header title={this.props.campaign.name ? ( "Showing Report for " + this.props.campaign.name ) : "Dashboard"}>
                    {this.props.campaign.name && (
                        <button className="wpcf-btn wpcf-link-btn" onClick={ () => this.props.onClickBack({id:'',name:''}) }>
                            <span className="wpcf-icon fas fa-long-arrow-alt-left"></span>
                            Go Back My Campaigns
                        </button>
                    )}
                </Header>
                <div className="wpcf-dashboard-content-inner">
                    <div className="wpcf-dashboard-info-cards">
                        <div className="wpcf-dashboard-info-card">
                            <span>Fund Raised</span>
                            <span className="wpcf-dashboard-info-val" dangerouslySetInnerHTML={{__html: fundRaised}} />
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <span>Funded</span>
                            <span className="wpcf-dashboard-info-val">{raisedPercent}%</span>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <span>Total Backed</span>
                            <span className="wpcf-dashboard-info-val">{ totalBacked }</span>
                        </div>
                    </div>

                    <div className="filter">
                        <select id="date_range" name="date_range" value={query_args.date_range} onChange={ this._onChange }>
                            { Object.keys( option_params ).map( key => 
                                <option key={key} value={key}> {option_params[key]} </option>
                            )}
                        </select>
                        <DatePicker
                            name="date_range_from"
                            value={query_args.date_range_from}
                            onChange={ e => this._onChange(e) }
                            placeholder="From"
                            format="yy-mm-dd"
                        />
                        <DatePicker
                            name="date_range_to"
                            value={query_args.date_range_to}
                            onChange={ e => this._onChange(e) }
                            placeholder="To"
                            format="yy-mm-dd"
                        />
                    </div>
                    <ExportCSV data={csv} file_name="campaigns-report"/>
                    <LineGraph format={ format } label={ label }/>
                    <hr />
                    <PledgeReports pledges={pledges} />
                </div>
            </div>
		)
	}
}

const mapStateToProps = state => ({
    report: state.campaignsReport
})

CampaignReport.defaultProps = {
    campaign: { id: '', name: ''}
}

export default connect( mapStateToProps, { fetchCampaignsReport } )(CampaignReport);
