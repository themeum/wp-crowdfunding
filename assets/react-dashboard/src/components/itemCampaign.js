import React from 'react';

function createMarkup( html ) {
    return { __html: html };
}

export default (props) => {
    const { data } = props;
    return (
        <div className="wpneo-listings-dashboard wpneo-shadow wpneo-padding15 wpneo-clearfix">
            <div className="wpneo-listing-img">
                <a href="#" title={ data.title } dangerouslySetInnerHTML={ createMarkup(data.thumbnail) } />
                <div className="overlay">
                    <div>
                        <div>
                            <a className="wp-crowd-btn wp-crowd-btn-primary" href={ data.permalink }>View</a>
                        </div>
                    </div>
                </div>
            </div>

            <div className="wpneo-listing-content clearfix">
                <div className="wpneo-admin-title float-left">
                    <h4><a href="<?php  echo get_permalink(); ?> ">{ data.title }</a></h4>
                    <p className="wpneo-author">by <a href="javascript:void(0)">{ data.author_name }</a> </p>
                    <div className="wpneo-location">
                        <i className="wpneo-icon wpneo-icon-location"></i>
                        <div className="wpneo-meta-desc">{ data.location }</div>
                    </div>
                </div>
                <div className="wpneo-admin-location float-right">
                    <span><a href="#">Update</a></span>
                    <span><a href="#" className="wp-crowd-btn wp-crowd-btn-primary">Edit</a></span>
                </div>
                <div className="wpneo-clearfix"></div>
                <div className="wpneo-percent-rund-wrap">
                    <div className="crowdfound-pie-chart" data-size="60" data-percent={ data.raised_percent }>
                        <div className="sppb-chart-percent"><span>{ data.raised_percent }</span></div>
                    </div>
                    <div className="crowdfound-fund-raised">
                        <div className="wpneo-meta-desc" dangerouslySetInnerHTML={ createMarkup(data.total_raised) } />
                        <div className="wpneo-meta-name">Fund Raised</div>
                    </div>
                    <div className="crowdfound-funding-goal">
                        <div className="wpneo-meta-desc" dangerouslySetInnerHTML={ createMarkup(data.funding_goal) } />
                        <div className="wpneo-meta-name">Funding Goal</div>
                    </div>

                    { ( data.end_method !== 'never_end' ) &&
                        <div className="crowdfound-time-remaining">
                            <div className="wpneo-meta-desc">{ ( data.is_started ) ? data.days_remaining :  data.days_until_launch }</div>
                            <div className="wpneo-meta-name">Days { ( data.is_started ) ? "to go" :  "Until Launch" }</div>
                        </div>
                    }

                    { props.pledge &&
                        <div className="">
                            <span><a href="#">Pledge More</a></span>
                        </div>
                    }
                </div>
            </div>
        </div>
    )
};