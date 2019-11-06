import React, { Component, Fragment } from 'react'
import decodeEntities from "../helpers/decodeEntities"

export default class header extends Component {
    render() {
        return (
            <Fragment>
                <div className="wpcf-content-header">
                    <div className="wpcf-content-header-left">
                        {this.props.children}
                    </div>
                    <a className="wpcf-btn wpcf-btn-round" href={WPCF.create_campaign_url}>
                        <span className="wpcf-icon fas fa-plus"></span>
                        Create Campaign
                    </a>
                </div>
                {this.props.title && <h3 className="wpcf-content-heading">{decodeEntities(this.props.title)}</h3>}
                {this.props.subtitle && <h4 className="wpcf-content-subheading">{this.props.subtitle}</h4>}
            </Fragment>
        )
    }
}