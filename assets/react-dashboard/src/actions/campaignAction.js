import { ExceptionHandler } from '../helper';
const headers = { 'Content-Type': 'application/json', 'WPCF-Nonce': WPCF.nonce };

//CAMPAINGS REPORTS
export const FETCH_CAMPAIGNS_REPORT_PENDING = 'fetch_campaigns_report_pending';
export const FETCH_CAMPAIGNS_REPORT_COMPLETE = 'fetch_campaigns_report_complete';

export const fetchCampaignsReport = (args) => dispatch => {
    dispatch({ type: FETCH_CAMPAIGNS_REPORT_PENDING });
    const fetchURL = `${WPCF.rest_url}/campaigns-report?${args}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: FETCH_CAMPAIGNS_REPORT_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}


//MY CAMPAINGS
export const FETCH_MY_CAMPAIGNS_PENDING = 'fetch_my_campaigns_pending';
export const FETCH_MY_CAMPAIGNS_COMPLETE = 'fetch_my_campaigns_complete';

export const fetchMyCampaigns = () => dispatch => {
    dispatch({ type: FETCH_MY_CAMPAIGNS_PENDING });
    const fetchURL = `${WPCF.rest_url}/my-campaigns`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: FETCH_MY_CAMPAIGNS_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}


//DELETE CAMPAINGS
export const DELETE_CAMPAIGN_PENDING = 'delete_campaign_pending';
export const DELETE_BOOKMARK_CAMPAIGN_PENDING = 'delete_bookmark_campaign_pending';
export const DELETE_CAMPAIGN_COMPLETE = 'delete_campaign_complete';
export const DELETE_BOOKMARK_CAMPAIGN_COMPLETE = 'delete_bookmark_campaign_complete';

export const deleteCampaign = (data) => dispatch => {
    let dispatchPending = DELETE_CAMPAIGN_PENDING
    let dispatchComplete = DELETE_CAMPAIGN_COMPLETE
    if(data.bookmark) {
        dispatchPending = DELETE_BOOKMARK_CAMPAIGN_PENDING
        dispatchComplete = DELETE_BOOKMARK_CAMPAIGN_COMPLETE
    }
    dispatch({ type: dispatchPending });
    const fetchURL = `${WPCF.rest_url}/delete-campaign`;
    const option = { method: 'POST', body: JSON.stringify(data), headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: dispatchComplete, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}


//INVESTED CAMPAIGNS
export const FETCH_INVESTED_CAMPAIGNS_PENDING = 'fetch_invested_campaigns_pending';
export const FETCH_INVESTED_CAMPAIGNS_COMPLETE = 'fetch_invested_campaigns_complete';

export const fetchInvestedCampaigns = () => dispatch => {
    dispatch({ type: FETCH_INVESTED_CAMPAIGNS_PENDING });
    const fetchURL = `${WPCF.rest_url}/invested-campaigns`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: FETCH_INVESTED_CAMPAIGNS_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}


//PLEDGE RECEIVED
export const FETCH_PLEDGE_RECEIVED_PENDING = 'fetch_pledge_received_pending';
export const FETCH_PLEDGE_RECEIVED_COMPLETE = 'fetch_pledge_received_complete';

export const fetchPledgeReceived = () => dispatch => {
    dispatch({ type: FETCH_PLEDGE_RECEIVED_PENDING });
    const fetchURL = `${WPCF.rest_url}/pledge-received`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: FETCH_PLEDGE_RECEIVED_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}


//BOOKMARK CAMPAIGNS
export const FETCH_BOOKMARK_CAMPAIGNS_PENDING = 'fetch_bookmark_campaigns_pending';
export const FETCH_BOOKMARK_CAMPAIGNS_COMPLETE = 'fetch_bookmark_campaigns_complete';

export const fetchBookmarkCampaigns = () => dispatch => {
    dispatch({ type: FETCH_BOOKMARK_CAMPAIGNS_PENDING });
    const fetchURL = `${WPCF.rest_url}/bookmark-campaigns`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: FETCH_BOOKMARK_CAMPAIGNS_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}


//CAMPAIGN REWARDS
export const FETCH_REWARDS_PENDING = 'fetch_rewards_pending';
export const FETCH_REWARDS_COMPLETE = 'fetch_rewards_complete';

export const fetchRewards = () => dispatch => {
    dispatch({ type: FETCH_REWARDS_PENDING });
    const fetchURL = `${WPCF.rest_url}/rewards`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: FETCH_REWARDS_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}


//SAVE USER DATA
export const SAVE_CAMPAIGN_UPDATES_PENDING = 'save_campaign_updates_pending';
export const SAVE_CAMPAIGN_UPDATES_COMPLETE = 'save_campaign_updates_complete';

export const saveCampaignUpdates = ( data ) => dispatch => {
    dispatch({ type: SAVE_CAMPAIGN_UPDATES_PENDING });
    const fetchURL = `${WPCF.rest_url}/save-campaign-updates`;
    const option = { method: 'POST', body: JSON.stringify(data), headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: SAVE_CAMPAIGN_UPDATES_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}