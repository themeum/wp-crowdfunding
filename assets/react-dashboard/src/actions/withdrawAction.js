
//FETCH USER WITHDRAWS
export const FETCH_WITHDRAWS_PENDING = 'fetch_withdraws_pending';
export const FETCH_WITHDRAWS_COMPLETE = 'fetch_withdraws_complete';
export const FETCH_WITHDRAWS_ERROR = 'fetch_withdraws_error';

export const fetchWithdraws = () => dispatch => {
    dispatch({ type: FETCH_WITHDRAWS_PENDING });
    const fetchURL = `${WPCF.rest_url}/withdraws`;
    const option = { method: 'GET', headers:{ 'Content-Type': 'application/json' } };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_WITHDRAWS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_WITHDRAWS_ERROR, payload} ) );
}

//POST WITHDRAW REQUEST
export const POST_WITHDRAW_REQUEST_PENDING = 'post_withdraw_request_pending';
export const POST_WITHDRAW_REQUEST_COMPLETE = 'post_withdraw_request_complete';
export const POST_WITHDRAW_REQUEST_ERROR = 'post_withdraw_request_error';

export const postWithdrawRequest = ( data ) => dispatch => {
    dispatch({ type: POST_WITHDRAW_REQUEST_PENDING });
    const fetchURL = `${WPCF.rest_url}/withdraw-request`;
    const option = { method: 'POST', body: JSON.stringify(data), headers:{ 'Content-Type': 'application/json' } };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: POST_WITHDRAW_REQUEST_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: POST_WITHDRAW_REQUEST_ERROR, payload} ) );
}

//FETCH WITHDRAW METHODS
export const FETCH_WITHDRAW_METHODS_PENDING = 'fetch_withdraw_methods_pending';
export const FETCH_WITHDRAW_METHODS_COMPLETE = 'fetch_withdraw_methods_complete';
export const FETCH_WITHDRAW_METHODS_ERROR = 'fetch_withdraw_methods_error';

export const fetchWithdrawMethods = () => dispatch => {
    dispatch({ type: FETCH_WITHDRAW_METHODS_PENDING });
    const fetchURL = `${WPCF.rest_url}/withdraw-methods`;
    const option = { method: 'GET', headers:{ 'Content-Type': 'application/json' } };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_WITHDRAW_METHODS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_WITHDRAW_METHODS_ERROR, payload} ) );
}

//SAVE WITHDRAW ACCOUNT
export const SAVE_WITHDRAW_ACCOUNT_PENDING = 'save_withdraw_account_pending';
export const SAVE_WITHDRAW_ACCOUNT_COMPLETE = 'save_withdraw_account_complete';
export const SAVE_WITHDRAW_ACCOUNT_ERROR = 'save_withdraw_account_error';

export const saveWithdrawAccount = ( data ) => dispatch => {
    dispatch({ type: SAVE_WITHDRAW_ACCOUNT_PENDING });
    const fetchURL = `${WPCF.rest_url}/save-withdraw-account`;
    const option = { method: 'POST', body: JSON.stringify(data), headers:{ 'Content-Type': 'application/json' } };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: SAVE_WITHDRAW_ACCOUNT_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: SAVE_WITHDRAW_ACCOUNT_ERROR, payload} ) );
}

