const headers = { 
    'Content-Type': 'application/json',
    'WP-Nonce': WPCF.nonce
}

//CAMPAINGS REPORTS
export const FETCH_CAMPAIGNS_REPORT_PENDING = 'fetch_campaigns_report_pending';
export const FETCH_CAMPAIGNS_REPORT_COMPLETE = 'fetch_campaigns_report_complete';
export const FETCH_CAMPAIGNS_REPORT_ERROR = 'fetch_campaigns_report_error';

export const fetchCampaignsReport = (args) => dispatch => {
    dispatch({ type: FETCH_CAMPAIGNS_REPORT_PENDING });
    const fetchURL = `${WPCF.rest_url}/campaigns-report?${args}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_CAMPAIGNS_REPORT_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_CAMPAIGNS_REPORT_ERROR, payload} ) );
}


//MY CAMPAINGS
export const FETCH_MY_CAMPAIGNS_PENDING = 'fetch_my_campaigns_pending';
export const FETCH_MY_CAMPAIGNS_COMPLETE = 'fetch_my_campaigns_complete';
export const FETCH_MY_CAMPAIGNS_ERROR = 'fetch_my_campaigns_error';

export const fetchMyCampaigns = () => dispatch => {
    dispatch({ type: FETCH_MY_CAMPAIGNS_PENDING });
    const fetchURL = `${WPCF.rest_url}/my-campaigns`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_MY_CAMPAIGNS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_MY_CAMPAIGNS_ERROR, payload} ) );
}


//DELETE CAMPAINGS
export const DELETE_CAMPAIGN_PENDING = 'delete_campaign_pending';
export const DELETE_CAMPAIGN_COMPLETE = 'delete_campaign_complete';
export const DELETE_CAMPAIGN_ERROR = 'delete_campaign_error';

export const deleteCampaign = (data) => dispatch => {
    dispatch({ type: DELETE_CAMPAIGN_PENDING });
    const fetchURL = `${WPCF.rest_url}/delete-campaign`;
    const option = { method: 'POST', body: JSON.stringify(data), headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: DELETE_CAMPAIGN_PENDING, payload} ) )
    .catch( payload => dispatch( {type: DELETE_CAMPAIGN_ERROR, payload} ) );
}


//INVESTED CAMPAIGNS
export const FETCH_INVESTED_CAMPAIGNS_PENDING = 'fetch_invested_campaigns_pending';
export const FETCH_INVESTED_CAMPAIGNS_COMPLETE = 'fetch_invested_campaigns_complete';
export const FETCH_INVESTED_CAMPAIGNS_ERROR = 'fetch_invested_campaigns_error';

export const fetchInvestedCampaigns = () => dispatch => {
    dispatch({ type: FETCH_INVESTED_CAMPAIGNS_PENDING });
    const fetchURL = `${WPCF.rest_url}/invested-campaigns`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_INVESTED_CAMPAIGNS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_INVESTED_CAMPAIGNS_ERROR, payload} ) );
}


//PLEDGE RECEIVED
export const FETCH_PLEDGE_RECEIVED_PENDING = 'fetch_pledge_received_pending';
export const FETCH_PLEDGE_RECEIVED_COMPLETE = 'fetch_pledge_received_complete';
export const FETCH_PLEDGE_RECEIVED_ERROR = 'fetch_pledge_received_error';

export const fetchPledgeReceived = () => dispatch => {
    dispatch({ type: FETCH_PLEDGE_RECEIVED_PENDING });
    const fetchURL = `${WPCF.rest_url}/pledge-received`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_PLEDGE_RECEIVED_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_PLEDGE_RECEIVED_ERROR, payload} ) );
}


//BOOKMARK CAMPAIGNS
export const FETCH_BOOKMARK_CAMPAIGNS_PENDING = 'fetch_bookmark_campaigns_pending';
export const FETCH_BOOKMARK_CAMPAIGNS_COMPLETE = 'fetch_bookmark_campaigns_complete';
export const FETCH_BOOKMARK_CAMPAIGNS_ERROR = 'fetch_bookmark_campaigns_error';

export const fetchBookmarkCampaigns = () => dispatch => {
    dispatch({ type: FETCH_BOOKMARK_CAMPAIGNS_PENDING });
    const fetchURL = `${WPCF.rest_url}/bookmark-campaigns`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_BOOKMARK_CAMPAIGNS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_BOOKMARK_CAMPAIGNS_ERROR, payload} ) );
}


//CAMPAIGN REWARDS
export const FETCH_REWARDS_PENDING = 'fetch_rewards_pending';
export const FETCH_REWARDS_COMPLETE = 'fetch_rewards_complete';
export const FETCH_REWARDS_ERROR = 'fetch_rewards_error';

export const fetchRewards = () => dispatch => {
    dispatch({ type: FETCH_REWARDS_PENDING });
    const fetchURL = `${WPCF.rest_url}/rewards`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_REWARDS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_REWARDS_ERROR, payload} ) );
}


//SAVE USER DATA
export const SAVE_CAMPAIGN_UPDATES_PENDING = 'save_campaign_updates_pending';
export const SAVE_CAMPAIGN_UPDATES_COMPLETE = 'save_campaign_updates_complete';
export const SAVE_CAMPAIGN_UPDATES_ERROR = 'save_campaign_updates_error';

export const saveCampaignUpdates = ( data ) => dispatch => {
    dispatch({ type: SAVE_CAMPAIGN_UPDATES_PENDING });
    const fetchURL = `${WPCF.rest_url}/save-campaign-updates`;
    const option = { method: 'POST', body: JSON.stringify(data), headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: SAVE_CAMPAIGN_UPDATES_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: SAVE_CAMPAIGN_UPDATES_ERROR, payload} ) );
}