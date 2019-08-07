
//EXPORT ALL ACTION TYPES
export const FETCH_USER = 'fetch_user';


export const fetchUser = () => dispatch => {
    const fetchURL = WPCF.ajax_url+"?action=wpcf_dashbord_profile";
    fetch( fetchURL, {
        method: 'POST',
        headers:{
            'Content-Type': 'application/json'
        }
    })
    .then( response => {
        return response.json();
    })
    .then( response => {
        dispatch({
            type: FETCH_USER,
            payload: response.data
        })
    })
    .catch(error => console.error('Error:', error));
}