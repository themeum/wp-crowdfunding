const headers = { 
    'Content-Type': 'application/json',
    'WP-Nonce': WPCF.nonce
}

//CAMPAINGS REPORTS
export const FETCH_FORM_FIELDS_PENDING = 'fetch_form_fields_pending';
export const FETCH_FORM_FIELDS_COMPLETE = 'fetch_form_fields_complete';
export const FETCH_FORM_FIELDS_ERROR = 'fetch_form_fields_error';

export const fetchFormFields = (args) => dispatch => {
    dispatch({ type: FETCH_FORM_FIELDS_PENDING });
    const fetchURL = `${WPCF.rest_url}/form-fields`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_FORM_FIELDS_COMPLETE, payload} ) )
    .catch( payload => dispatch( {type: FETCH_FORM_FIELDS_ERROR, payload} ) );
}