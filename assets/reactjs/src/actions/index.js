const headers = { 
    'Content-Type': 'application/json',
    'WP-Nonce': WPCF.nonce
}

//COMMON STATE
export const FETCH_REQUEST_PENDING = 'FETCH_REQUEST_PENDING';


//FETCH FORM FIELDS
export const FETCH_FORM_FIELDS_COMPLETE = 'FETCH_FORM_FIELDS_COMPLETE';
export const fetchFormFields = () => dispatch => {
    dispatch({ type: FETCH_REQUEST_PENDING });
    const fetchURL = `${WPCF.rest_url}/form-fields`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_FORM_FIELDS_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}

//FETCH FORM FIELDS
export const FETCH_FORM_REWARD_TYPES_COMPLETE = 'FETCH_FORM_REWARD_TYPES_COMPLETE';
export const fetchRewardTypes = () => dispatch => {
    dispatch({ type: FETCH_REQUEST_PENDING });
    const fetchURL = `${WPCF.rest_url}/reward-types`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_FORM_REWARD_TYPES_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}

//FETCH FORM FIELDS
export const FETCH_FORM_REWARD_FIELDS_COMPLETE = 'FETCH_FORM_REWARD_FIELDS_COMPLETE';
export const fetchRewardFields = () => dispatch => {
    dispatch({ type: FETCH_REQUEST_PENDING });
    const fetchURL = `${WPCF.rest_url}/reward-fields`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_FORM_REWARD_FIELDS_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}


//FETCH FORM TAGS
export const FETCH_SUB_CATEGORIES_COMPLETE = 'fetch_sub_categories_complete';
export const fetchSubCategories = (id) => dispatch => {
    const fetchURL = `${WPCF.rest_url}/sub-categories?id=${id}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_SUB_CATEGORIES_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}


//FETCH FORM TAGS
export const FETCH_STATES_COMPLETE = 'fetch_states_complete';
export const fetchStates = (code) => dispatch => {
    const fetchURL = `${WPCF.rest_url}/states?code=${code}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_STATES_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}