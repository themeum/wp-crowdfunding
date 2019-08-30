export const FETCH_USER_PENDING = 'fetch_user_pending';
export const FETCH_USER_COMPLETE = 'fetch_user_complete';
export const FETCH_USER_ERROR = 'fetch_user_error';


export const fetchUser = () => dispatch => {
    dispatch({ type: FETCH_USER_PENDING });
    const fetchURL = `${WPCF.rest_url}/user-profile`;
    const option = { method: 'GET', headers:{ 'Content-Type': 'application/json' } };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_USER_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_USER_ERROR, payload} ) );
}