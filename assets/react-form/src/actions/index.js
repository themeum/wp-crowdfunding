import { ExceptionHandler } from '../Helper';
const headers = { 'Content-Type': 'application/json', 'WPCF-Nonce': WPCF.nonce };

//COMMON STATE
export const FETCH_REQUEST_PENDING = 'FETCH_REQUEST_PENDING';

//FETCH FORM BASIC FIELDS
export const FETCH_FORM_FIELDS_COMPLETE = 'FETCH_FORM_FIELDS_COMPLETE';
export const fetchFormFields = () => dispatch => {
    dispatch({ type: FETCH_REQUEST_PENDING });
    const fetchURL = `${WPCF.rest_url}/form-fields`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: FETCH_FORM_FIELDS_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}

//FETCH SUB CATEGORIES BY CATEGORY
export const FETCH_SUB_CATEGORIES_COMPLETE = 'FETCH_SUB_CATEGORIES_COMPLETE';
export const fetchSubCategories = (id) => dispatch => {
    const fetchURL = `${WPCF.rest_url}/sub-categories?id=${id}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: FETCH_SUB_CATEGORIES_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}

//FETCH STATES BY CODE
/* export const FETCH_STATES_COMPLETE = 'fetch_states_complete';
export const fetchStates = (code) => dispatch => {
    const fetchURL = `${WPCF.rest_url}/states?code=${code}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: FETCH_STATES_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
} */

//FETCH FORM VALUES BY CAMPAIGN ID
export const FETCH_FORM_VALUES_PENDING = 'FETCH_FORM_VALUES_PENDING';
export const FETCH_FORM_VALUES_COMPLETE = 'FETCH_FORM_VALUES_COMPLETE';
export const fetchFormValues = (id) => dispatch => {
    dispatch({ type: FETCH_FORM_VALUES_PENDING });
    const fetchURL = `${WPCF.rest_url}/form-values?id=${id}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: FETCH_FORM_VALUES_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}


//FORM FIELD SHOW HIDE
export const FIELD_SHOW_HIDE = 'FIELD_SHOW_HIDE';
export const fieldShowHide = (field, show) => dispatch => {
    field = field.split('.');
    dispatch({ type: FIELD_SHOW_HIDE, payload:{field, show} });
}


//FORM FIELD SHOW HIDE
export const VALIDATE_RECAPTCHA = 'VALIDATE_RECAPTCHA';
export const validateRecaptcha = (response_key) => dispatch => {
    if(!response_key) {
        dispatch( {type: VALIDATE_RECAPTCHA, payload: false});
        return false;
    }
    const data = { response_key };
    const fetchURL = `${WPCF.rest_url}/check-recaptcha`;
    const option = { method: 'POST', body: JSON.stringify(data), headers };
    fetch( fetchURL, option )
    .then( response => {
        if(response.status==200) {
            response.json().then( payload =>
                dispatch( {type: VALIDATE_RECAPTCHA, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}

//FETCH REGISTERD USER BY EMAIL
export const fetchUser = (email) => dispatch => {
    const fetchURL = `${WPCF.rest_url}/get-user`;
    const option = { method: 'POST', body: JSON.stringify({email}), headers };
    const user = fetch( fetchURL, option ).then( response => {
        if(response.status==200) {
            return response.json();
        } else {
            ExceptionHandler( response )
        }
    });
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
    .then( response => {
        if(response.status==200) {
            response.json().then( payload => 
                dispatch( {type: SAVE_CAMPAIGN_COMPLETE, payload})
            );
        } else {
            ExceptionHandler( response )
        }
    });
}