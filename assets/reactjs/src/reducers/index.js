import { 
    FETCH_REQUEST_PENDING,
    FETCH_FORM_TAGS_COMPLETE,
    FETCH_FORM_FIELDS_COMPLETE
} from "../actions";

export default function(state = { loading: true, loaded: false, formFields:{} }, action ) {
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
        case FETCH_FORM_TAGS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                formTags: action.payload,
            };
        default: 
            return state;
    }
}