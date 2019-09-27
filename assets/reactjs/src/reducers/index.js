import { 
    FETCH_REQUEST_PENDING,
    FETCH_FORM_FIELDS_COMPLETE,
    FETCH_FORM_REWARD_FIELDS_COMPLETE,
    FETCH_SUB_CATEGORIES_COMPLETE,
    FETCH_STATES_COMPLETE,
} from "../actions";

export default function(state = { loading: true, loaded: false, formFields:{}, rewardFields:{} }, action ) {
    switch( action.type ) {
        
        case FETCH_REQUEST_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_FORM_FIELDS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                formFields: action.payload,
            };
        case FETCH_FORM_REWARD_FIELDS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                rewardFields: action.payload,
            };
        case FETCH_SUB_CATEGORIES_COMPLETE:
        case FETCH_STATES_COMPLETE:
            const res = action.payload;
            let formFields = {...state.formFields};
            formFields[res.section][res.field].options = res.options;
            return {
                ...state,
                formFields,
            };
        default: 
            return state;
    }
}