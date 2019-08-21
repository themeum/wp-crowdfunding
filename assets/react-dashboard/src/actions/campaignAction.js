
//MY CAMPAINGS
export const FETCH_MY_CAMPAIGNS_PENDING = 'fetch_my_campaigns_pending';
export const FETCH_MY_CAMPAIGNS_COMPLETE = 'fetch_my_campaigns_complete';
export const FETCH_MY_CAMPAIGNS_ERROR = 'fetch_my_campaigns_error';

export const fetchMyCampaigns = () => dispatch => {
    dispatch({ type: FETCH_MY_CAMPAIGNS_PENDING });
    const fetchURL = WPCF.ajax_url+"?action=wpcf_dashbord_my_campaigns";
    const option = { method: 'POST', headers:{ 'Content-Type': 'application/json' } };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_MY_CAMPAIGNS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_MY_CAMPAIGNS_ERROR, payload} ) );
}


//INVESTED CAMPAIGNS
export const FETCH_INVESTED_CAMPAIGNS_PENDING = 'fetch_invested_campaigns_pending';
export const FETCH_INVESTED_CAMPAIGNS_COMPLETE = 'fetch_invested_campaigns_complete';
export const FETCH_INVESTED_CAMPAIGNS_ERROR = 'fetch_invested_campaigns_error';

export const fetchInvestedCampaigns = () => dispatch => {
    dispatch({ type: FETCH_INVESTED_CAMPAIGNS_PENDING });
    const fetchURL = WPCF.ajax_url+"?action=wpcf_dashbord_invested_campaigns";
    const option = { method: 'POST', headers:{ 'Content-Type': 'application/json' } };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_INVESTED_CAMPAIGNS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_INVESTED_CAMPAIGNS_ERROR, payload} ) );
}