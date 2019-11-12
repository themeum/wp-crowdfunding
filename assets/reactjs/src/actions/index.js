const headers = { 
    'Content-Type': 'application/json',
    'WP-Nonce': WPCF.nonce
}

//COMMON STATE
export const FETCH_REQUEST_PENDING = 'FETCH_REQUEST_PENDING';

//FETCH FORM BASIC FIELDS
export const FETCH_FORM_FIELDS_COMPLETE = 'FETCH_FORM_FIELDS_COMPLETE';
export const fetchFormFields = () => dispatch => {
    dispatch({ type: FETCH_REQUEST_PENDING });
    const fetchURL = `${WPCF.rest_url}/form-fields`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_FORM_FIELDS_COMPLETE, payload}))
    .catch( error => console.log(error) );
}

//FETCH SUB CATEGORIES BY CATEGORY
export const FETCH_SUB_CATEGORIES_COMPLETE = 'FETCH_SUB_CATEGORIES_COMPLETE';
export const fetchSubCategories = (id) => dispatch => {
    const fetchURL = `${WPCF.rest_url}/sub-categories?id=${id}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: FETCH_SUB_CATEGORIES_COMPLETE, payload} ))
    .catch( error => console.log(error) );
}

//FETCH STATES BY CODE
/* export const FETCH_STATES_COMPLETE = 'fetch_states_complete';
export const fetchStates = (code) => dispatch => {
    const fetchURL = `${WPCF.rest_url}/states?code=${code}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_STATES_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
} */

//FETCH FORM VALUES BY CAMPAIGN ID
export const FETCH_FORM_VALUES_PENDING = 'FETCH_FORM_VALUES_PENDING';
export const FETCH_FORM_VALUES_COMPLETE = 'FETCH_FORM_VALUES_COMPLETE';
export const fetchFormValues = (id) => dispatch => {
    dispatch({ type: FETCH_FORM_VALUES_PENDING });
    const fetchURL = `${WPCF.rest_url}/form-values?id=${id}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  {
        const sub_categories = payload.sub_categories;
        if(sub_categories) {
            dispatch( {type: FETCH_SUB_CATEGORIES_COMPLETE, payload: sub_categories} );
        }
        dispatch( {type: FETCH_FORM_VALUES_COMPLETE, payload} );
    })
    .catch( error => console.log(error) );
}


//FORM FIELD SHOW HIDE
export const FIELD_SHOW_HIDE = 'FIELD_SHOW_HIDE';
export const fieldShowHide = (field, show) => dispatch => {
    field = field.split('.');
    dispatch({ type: FIELD_SHOW_HIDE, payload:{field, show} });
}

//FETCH REGISTERD USER BY EMAIL
export const fetchUser = (email) => dispatch => {
    const fetchURL = `${WPCF.rest_url}/get-user`;
    const option = { method: 'POST', body: JSON.stringify({email}), headers };
    const user = fetch( fetchURL, option ).then( response => response.json());
    return user;
}

//SAVE CAMPAIGN
export const SAVE_CAMPAIGN_PENDING = 'SAVE_CAMPAIGN_PENDING';
export const SAVE_CAMPAIGN_COMPLETE = 'SAVE_CAMPAIGN_COMPLETE';

export const saveCampaign = ( data ) => dispatch => {
    dispatch({ type: SAVE_CAMPAIGN_PENDING });
    const fetchURL = `${WPCF.rest_url}/save-campaign`;
    const option = { method: 'POST', body: JSON.stringify(data), headers };
    fetch( fetchURL, option )
    .then( response => response.json() )
    .then( payload => dispatch( {type: SAVE_CAMPAIGN_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}