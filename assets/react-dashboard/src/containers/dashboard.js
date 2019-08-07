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
                                <span>Enrolled Course</span>
                                <span className="wpcf-dashboard-info-val">9</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Active Course</span>
                                <span className="wpcf-dashboard-info-val">4</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Completed Course</span>
                                <span className="wpcf-dashboard-info-val">5</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Total Students</span>
                                <span className="wpcf-dashboard-info-val">7</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Total Courses</span>
                                <span className="wpcf-dashboard-info-val">7</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span>Total Earning</span>
                                <span className="wpcf-dashboard-info-val"><span className="woocommerce-Price-amount amount"><span className="woocommerce-Price-currencySymbol">&#36;</span>880.00</span></span>
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
