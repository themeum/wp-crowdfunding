import React, { Component } from 'react'

export default class contentHeader extends Component {
    render() {
        return (
            <div>
                <div className="wpcf-content-header">
                    <div className="wpcf-content-header-left">
                        {this.props.children}
                    </div>
                    <a className="wpcf-btn wpcf-round" href="#">
                        <span className="wpcf-icon fas fa-plus"></span>
                        Create Campaign
                    </a>
                </div>
                <h3 className="wpcf-content-heading">{this.props.title}</h3>
            </div>
        )
    }
}
