import React, { Fragment } from 'react';
import { decodeEntities } from "../helper";

const Header = props => {
    return (
        <Fragment>
            <div className="wpcf-content-header">
                <div className="wpcf-content-header-left">
                    {props.children}
                </div>
                <a className="wpcf-btn wpcf-btn-round" href={WPCF.create_campaign}>
                    <span className="wpcf-icon fas fa-plus"></span>
                    Create Campaign
                </a>
            </div>
            {props.title && <h3 className="wpcf-content-heading">{decodeEntities(props.title)}</h3>}
            {props.subtitle && <h4 className="wpcf-content-subheading">{props.subtitle}</h4>}
        </Fragment>
    )
}

export default Header;
