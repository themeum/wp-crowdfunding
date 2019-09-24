const headers = { 
    'Content-Type': 'application/json',
    'WP-Nonce': WPCF.nonce
}

//COMMON STATE
export const FETCH_REQUEST_PENDING = 'fetch_request_pending';


//FETCH FORM FIELDS
export const FETCH_FORM_FIELDS_COMPLETE = 'fetch_form_fields_complete';
export const fetchFormFields = () => dispatch => {
    dispatch({ type: FETCH_REQUEST_PENDING });
    const fetchURL = `${WPCF.rest_url}/form-fields`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_FORM_FIELDS_COMPLETE, payload} ) )
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