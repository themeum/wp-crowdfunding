import React, { Component } from 'react';

class Dashboard extends Component {
	constructor (props) {
		super(props);
		this.state = { };
    }
    
	render () {
		return (
            <div className="wpcf-dashboard-content">
                <h3>Dashboard</h3>
                <div className="wpcf-dashboard-content-inner">
                    <div className="wpcf-dashboard-info-cards">
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Fund Raised</span>
                                <span className="wpcf-dashboard-info-val">9</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Funded</span>
                                <span className="wpcf-dashboard-info-val">4</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Backers</span>
                                <span className="wpcf-dashboard-info-val">4</span>
                            </p>
                        </div>
                    </div>

                    
                   
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
                                <tr>
                                    <td>Tutor LMS – eLearning and online course solution</td>
                                    <td>6</td>
                                </tr>
                                <tr>
                                    <td>Tutor LMS – eLearning and online course solution</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td>Tutor LMS – eLearning and online course solution</td>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <td>Tutor LMS – eLearning and online course solution</td>
                                    <td>5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
		)
	}
}

export default Dashboard;
