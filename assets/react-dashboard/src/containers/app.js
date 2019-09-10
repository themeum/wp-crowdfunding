import React, { Component } from 'react';
import { BrowserRouter as Router, HashRouter, Route, Link } from "react-router-dom";
import { connect } from 'react-redux';
import { fetchUser } from '../actions/userAction';
import Dashboard from './dashboard';
import Profile from './profile';
import MyCampaigns from './myCampaigns';
import InvestedCampaigns from './investedCampaigns';
import PledgeReceived from './pledgeReceived';
import BookmarkCampaigns from './bookmarkCampaigns';
import Order from './order';
import Withdraw from './withdraw';
import ProfileSettings from './profileSettings';
import WithdrawMethodSettings from './withdrawMethodSettings';
import Rewards from './rewards';
import '../styles/style.scss';

class App extends Component {
	constructor (props) {
		super(props);
		const basePath = WPCF.dashboard_url.replace( window.location.origin, '' );
		this.state = { basePath };
		this.logout = this.logout.bind(this);
	}

	componentDidMount() {
        const { loaded } = this.props.user;
        if( !loaded ) {
            this.props.fetchUser();
		}
    }

	logout(e) {
		e.preventDefault();
		fetch(`${WPCF.rest_url}/logout`)
			.then(res => res.json())
			.then(res => {
				if(res.success) {
					location.href = res.redirect;
				}
			});
	}

	render () {
		const { basePath } = this.state;
		const { loading, data } = this.props.user;
        if( loading ) { 
            return (
                <div>
                    Loading...
                </div>
            )
        };
		return (
			<div className="wpcf-wrap wpcf-dashboard">
				<div className="wpcf-container">
					<div className="wpcf-row">
						<div className="wpcf-col-12">
							<div className="wpcf-dashboard-header">
								<div className="wpcf-dashboard-header-avatar">
								<img className="profile-form-img" src={ data.profile_image } alt="Profile Image" />
								</div>
								<div className="wpcf-dashboard-header-info">
									<div className="wpcf-dashboard-header-display-name">
										<h4>{data.first_name+' '+data.last_name}</h4>
									</div>
								</div>
								<div className="wpcf-dashboard-header-button">
									<a className="wpcf-btn bordered-btn" href="#">
									<i className="wpcf-icon-checkbox-pen-outline"></i> &nbsp; Create Campaign                                 </a>
								</div>
							</div>
						</div>
					</div>
					<div className="wpcf-row">
						<HashRouter>
							<div className="wpcf-col-3 wpcf-dashboard-left-menu">
								<ul className="wpcf-dashboard-permalinks">
									<li className='wpcf-dashboard-menu-index'><Link to="/">Dashboard</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/profile">My Profile</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/my-campaigns">My Campaigns</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/invested-campaigns">Invested Campaigns</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/pledge-received">Pledge Received</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/bookmark-campaigns">Bookmarks</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/order">Order</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/withdraw">Withdraw</Link></li>
									<li className='wpcf-dashboard-menu-index'><a href="javascript:void(0)">User Settings</a></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/settings/profile">Profile</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/settings/withdraw">Withdraw Method</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/rewards">Rewards</Link></li>
									<li className='wpcf-dashboard-menu-logout'><a href="javascript:void(0)" onClick={this.logout}>Logout</a></li>
								</ul>
							</div>
							<div className="wpcf-col-9">
								<Route path="/" exact component={ Dashboard } />
								<Route path="/profile" component={ Profile } />
								<Route path="/my-campaigns" component={ MyCampaigns } />
								<Route path="/invested-campaigns" component={ InvestedCampaigns } />
								<Route path="/pledge-received" component={ PledgeReceived } />
								<Route path="/bookmark-campaigns" component={ BookmarkCampaigns } />
								<Route path="/order" component={ Order } />
								<Route path="/withdraw" component={ Withdraw } />
								<Route path="/settings/profile" component={ ProfileSettings } />
								<Route path="/settings/withdraw" component={ WithdrawMethodSettings } />
								<Route path="/rewards" component={ Rewards } />
							</div>
						</HashRouter>
					</div>
				</div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
    user: state.user
})

export default connect( mapStateToProps, { fetchUser } )(App);