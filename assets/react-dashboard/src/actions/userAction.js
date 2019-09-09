const headers = { 
    'Content-Type': 'application/json',
    'WP-Nonce': WPCF.nonce
}

//FETCH COUNTRIES
export const FETCH_COUNTRIES_PENDING = 'fetch_countries_pending';
export const FETCH_COUNTRIES_COMPLETE = 'fetch_countries_complete';
export const FETCH_COUNTRIES_ERROR = 'fetch_countries_error';

export const fetchCountries = () => dispatch => {
    dispatch({ type: FETCH_COUNTRIES_PENDING });
    const fetchURL = `${WPCF.rest_url}/wc-countries`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_COUNTRIES_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_COUNTRIES_ERROR, payload} ) );
}

//FETCH USER DATA
export const FETCH_USER_PENDING = 'fetch_user_pending';
export const FETCH_USER_COMPLETE = 'fetch_user_complete';
export const FETCH_USER_ERROR = 'fetch_user_error';

export const fetchUser = () => dispatch => {
    dispatch({ type: FETCH_USER_PENDING });
    const fetchURL = `${WPCF.rest_url}/user-profile`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_USER_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_USER_ERROR, payload} ) );
}

//SAVE USER DATA
export const SAVE_USER_DATA_PENDING = 'save_user_data_pending';
export const SAVE_USER_DATA_COMPLETE = 'save_user_data_complete';
export const SAVE_USER_DATA_ERROR = 'save_user_data_error';

export const saveUserData = ( data ) => dispatch => {
    dispatch({ type: SAVE_USER_DATA_PENDING });
    const fetchURL = `${WPCF.rest_url}/save-userdata`;
    const option = { method: 'POST', body: JSON.stringify(data), headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: SAVE_USER_DATA_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: SAVE_USER_DATA_ERROR, payload} ) );
}