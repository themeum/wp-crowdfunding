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
export const FETCH_FORM_TAGS_COMPLETE = 'fetch_form_tags_complete';
export const fetchFormTags = (args) => dispatch => {
    dispatch({ type: FETCH_REQUEST_PENDING });
    const fetchURL = `${WPCF.rest_url}/form-tags`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_FORM_TAGS_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}