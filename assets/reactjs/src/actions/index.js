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
    .then( payload =>  dispatch( {type: FETCH_FORM_FIELDS_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}

//FETCH FORM STORY TOOLS
export const FETCH_FORM_STORY_TOOLS_COMPLETE = 'FETCH_FORM_STORY_TOOLS_COMPLETE';
export const fetchFormStoryTools = () => dispatch => {
    dispatch({ type: FETCH_REQUEST_PENDING });
    const fetchURL = `${WPCF.rest_url}/story-tools`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_FORM_STORY_TOOLS_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}

//FETCH FORM REWARD FIELDS
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

//FETCH FORM TEAM FIELDS
export const FETCH_FORM_TEAM_FIELDS_COMPLETE = 'FETCH_FORM_TEAM_FIELDS_COMPLETE';
export const fetchTeamFields = () => dispatch => {
    dispatch({ type: FETCH_REQUEST_PENDING });
    const fetchURL = `${WPCF.rest_url}/team-fields`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_FORM_TEAM_FIELDS_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}

//FETCH SUB CATEGORIES BY CATEGORY
export const FETCH_SUB_CATEGORIES_COMPLETE = 'fetch_sub_categories_complete';
export const fetchSubCategories = (id) => dispatch => {
    const fetchURL = `${WPCF.rest_url}/sub-categories?id=${id}`;
    const option = { method: 'GET', headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload =>  dispatch( {type: FETCH_SUB_CATEGORIES_COMPLETE, payload} ) )
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


//FORM FIELD SHOW HIDE
export const FIELD_SHOW_HIDE = 'field_show_hide';
export const fieldShowHide = (field, show) => dispatch => {
    field = field.split('.');
    dispatch({ type: FIELD_SHOW_HIDE, payload:{field, show} });
}


//SAVE CAMPAIGN
export const SAVE_CAMPAIGN_PENDING = 'save_campaign_pending';
export const SAVE_CAMPAIGN_COMPLETE = 'save_campaign_complete';

export const saveCampaign = ( data ) => dispatch => {
    dispatch({ type: SAVE_CAMPAIGN_PENDING });
    const fetchURL = `${WPCF.rest_url}/save-campaign`;
    const option = { method: 'POST', body: JSON.stringify(data), headers };
    fetch( fetchURL, option )
    .then( response =>  response.json() )
    .then( payload => dispatch( {type: SAVE_CAMPAIGN_COMPLETE, payload} ) )
    .catch( error => console.log(error) );
}