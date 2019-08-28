//ORDERS LIST
export const FETCH_ORDERS_PENDING = 'fetch_orders_pending';
export const FETCH_ORDERS_COMPLETE = 'fetch_orders_complete';
export const FETCH_ORDERS_ERROR = 'fetch_orders_error';

export const fetchOrders = () => dispatch => {
    dispatch({ type: FETCH_ORDERS_PENDING });
    const fetchURL = `${WPCF.rest_url}/orders`;
    const option = { method: 'GET', headers:{ 'Content-Type': 'application/json' } };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_ORDERS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_ORDERS_ERROR, payload} ) );
}


//WITHDRAWS
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


export const POST_WITHDRAW_REQUEST_COMPLETE = 'post_withdraw_request_complete';
export const POST_WITHDRAW_REQUEST_ERROR = 'post_withdraw_request_error';

export const postWithdrawRequest = ( data ) => dispatch => {
    const fetchURL = `${WPCF.rest_url}/withdraw-request`;
    const option = { method: 'POST', body: JSON.stringify(data), headers:{ 'Content-Type': 'application/json' } };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: POST_WITHDRAW_REQUEST_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: POST_WITHDRAW_REQUEST_ERROR, payload} ) );
}