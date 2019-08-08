import React, { Component } from 'react';
import { BrowserRouter as Router, Route, Link } from "react-router-dom";
import '../styles/style.scss';
import Dashboard from './dashboard';
import Profile from './profile';
import MyCampaigns from './myCampaigns';

class App extends Component {
	constructor (props) {
		super(props);
		const basePath = WPCF.dashboard_url.replace( window.location.origin, '' );
		this.state = { basePath };
	}

	render () {
		const { basePath } = this.state;
		return (
			<div className="wpcf-wrap wpcf-dashboard">
				<div className="wpcf-container">
					<div className="wpcf-row">
						<div className="wpcf-col-12">
							<div className="wpcf-dashboard-header">
								<div className="wpcf-dashboard-header-avatar">
									<img src="http://1.gravatar.com/avatar/169dc7be10b05744eadec177079f9031?s=150&d=mm&r=g" />
								</div>
								<div className="wpcf-dashboard-header-info">
									<div className="wpcf-dashboard-header-display-name">
										<h4>Howdy, <strong>John Doe</strong> </h4>
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
						<Router basename={ basePath }>
							<div className="wpcf-col-3 wpcf-dashboard-left-menu">
								<ul className="wpcf-dashboard-permalinks">
									<li className='wpcf-dashboard-menu-index'><Link to="/">Dashboard</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/profile">My Profile</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/messages">Messages</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/notifications">Notifications</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/my-campaigns">My Campaigns</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/">User Settings</Link></li>
									<li className='wpcf-dashboard-menu-index'><Link to="/rewards">Rewards</Link></li>
									<li className='wpcf-dashboard-menu-logout  '><Link to="/">Logout</Link></li>
								</ul>
							</div>
							<div className="wpcf-col-9">
								<Route path="/" exact component={ Dashboard } />
								<Route path="/profile" exact component={ Profile } />
								<Route path="/my-campaigns" exact component={ MyCampaigns } />
							</div>
						</Router>
					</div>
				</div>
			</div>
		)
	}
}

export default App;