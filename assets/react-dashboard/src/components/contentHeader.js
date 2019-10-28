import React, { Component, Fragment } from 'react'

export default class contentHeader extends Component {
    render() {
        return (
            <Fragment>
                <div className="wpcf-content-header">
                    <div className="wpcf-content-header-left">
                        {this.props.children}
                    </div>
                    <a className="wpcf-btn wpcf-btn-round" href="#">
                        <span className="wpcf-icon fas fa-plus"></span>
                        Create Campaign
                    </a>
                </div>
                <h3 className="wpcf-content-heading">{this.props.title}</h3>
            </Fragment>
        )
    }
}
