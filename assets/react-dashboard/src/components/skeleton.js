import React, {Fragment} from 'react';

export default (props) => {
    return (
        <Fragment>
            <div className="is-skeleton">
                <div className="wpcf-content-header">
                    <div className="wpcf-content-header-left">
                        <button className="wpcf-btn wpcf-link-btn skeleton-bg">
                            Go Back My Placeholder
                        </button>
                    </div>
                    <a className="wpcf-btn wpcf-btn-round skeleton-bg">
                        Create Campaign
                    </a>
                </div>
            </div>

            <div className="wpcf-dashboard-info-cards is-skeleton">
                <div className="wpcf-dashboard-info-card">
                    <h3 className="wpcf-dashboard-info-val skeleton-bg" >20.00</h3>
                    <span className="skeleton-bg">Lorem ipsum</span>
                </div>
                <div className="wpcf-dashboard-info-card">
                    <h3 className="wpcf-dashboard-info-val skeleton-bg" >20.00</h3>
                    <span className="skeleton-bg">Lorem ipsum</span>
                </div>
                <div className="wpcf-dashboard-info-card">
                    <h3 className="wpcf-dashboard-info-val skeleton-bg" >20.00</h3>
                    <span className="skeleton-bg">Lorem ipsum</span>
                </div>
                <div className="wpcf-dashboard-info-card">
                    <h3 className="wpcf-dashboard-info-val skeleton-bg" >20.00</h3>
                    <span className="skeleton-bg">Lorem ipsum</span>
                </div>
            </div>

            <div className="wpcf-mycampaign-filter-group wpcf-btn-group is-skeleton">
                <button className="wpcf-btn wpcf-btn-round skeleton-bg">Pending</button>
                <button className="wpcf-btn wpcf-btn-round skeleton-bg">Pending</button>
                <button className="wpcf-btn wpcf-btn-round skeleton-bg">Pending</button>
                <button className="wpcf-btn wpcf-btn-round skeleton-bg">Pending</button>
            </div>

            <table className="wpcf-report-table is-skeleton skeleton-table">
                <thead>
                <tr>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                </tr>
                <tr>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                </tr>
                <tr>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                </tr>
                <tr>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                    <td><span>Lorem ipsum</span></td>
                </tr>
                </tbody>
            </table>

        </Fragment>
    )
};
