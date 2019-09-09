const headers = { 
    'Content-Type': 'application/json',
    'WP-Nonce': WPCF.nonce
}

//ORDERS LIST
export const FETCH_ORDERS_PENDING = 'fetch_orders_pending';
export const FETCH_ORDERS_COMPLETE = 'fetch_orders_complete';
export const FETCH_ORDERS_ERROR = 'fetch_orders_error';

export const fetchOrders = () => dispatch => {
    dispatch({ type: FETCH_ORDERS_PENDING });
    const fetchURL = `${WPCF.rest_url}/orders`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_ORDERS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_ORDERS_ERROR, payload} ) );
}