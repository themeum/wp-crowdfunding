import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchCampaignsReport } from '../actions/campaignAction';
import LineGraph from '../components/lineGraph';

class Dashboard extends Component {
	constructor (props) {
		super(props);
		this.state = { 
            query_args: { 'date_range': 'last_7_days'},
            option_params: {
                last_7_days: 'Last Week',
                last_14_days: 'Last 14 Days',
                this_month: 'This Month',
                last_3_months: 'Last 3 Months',
                last_6_months: 'Last 6 Months',
                this_year: 'This Year'
            }
        };
        this.genQueryArgs = this.genQueryArgs.bind( this );
    }

    componentDidMount() {
        let { query_args } = this.state;
        this.props.fetchCampaignsReport(query_args);
    }

    genQueryArgs(e) {
        let { query_args } = this.state;
        query_args = Object.assign({}, query_args, { [e.target.name]: e.target.value });
        this.props.fetchCampaignsReport(query_args);
        this.setState( { query_args } );
    }
    
	render () {
        const { query_args, option_params } = this.state;
        const { loading, data:{ csv, format, label, fundRaised, raisedPercent, totalBacked, pledges } } = this.props.report;
        
        if( loading ) {
            return (
                <div>
                    Loading...
                </div>
            )
        };
        
        return (
            <div className="wpcf-dashboard-content">
                <h3>Dashboard</h3>
                <div className="wpcf-dashboard-content-inner">
                    <div className="wpcf-dashboard-info-cards">
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Fund Raised</span>
                                <span className="wpcf-dashboard-info-val" dangerouslySetInnerHTML={{__html: fundRaised}} />
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Funded</span>
                                <span className="wpcf-dashboard-info-val">{raisedPercent}%</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Total Backed</span>
                                <span className="wpcf-dashboard-info-val">{ totalBacked }</span>
                            </p>
                        </div>
                    </div>

                    <div className="filter">
                        <select id="date_range" name="date_range" value={query_args.date_range} onChange={ this.genQueryArgs }>
                            { Object.keys( option_params ).map( key => 
                                <option key={key} value={key}> {option_params[key]} </option>
                            )}
                        </select>
                    </div>

                   {/*  <LineGraph format={ format } label={ label }/> */}
                   
                    <div className="wpcf-dashboard-info-table-wrap">
                        <h3>Most Popular Courses</h3>
                        <table className="wpcf-dashboard-info-table">
                            <thead>
                                <tr>
                                    <td>Course Name</td>
                                    <td>Enrolled</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tutor LMS – eLearning and online course solution</td>
                                    <td>1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
		)
	}
}


const mapStateToProps = state => ({
    report: state.campaignsReport
})

export default connect( mapStateToProps, { fetchCampaignsReport } )(Dashboard);
