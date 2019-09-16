import { 
    FETCH_FORM_FIELDS_PENDING,
    FETCH_FORM_FIELDS_COMPLETE,
    FETCH_FORM_FIELDS_ERROR,
} from "../actions";

export default function(state = { loading: true, loaded: false }, action ) {
    switch( action.type ) {
        
        case FETCH_FORM_FIELDS_PENDING:
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
        case FETCH_FORM_FIELDS_ERROR:
            return {
                    ...state,
                    loading: false,
                    loaded: false,
                    error: action.payload,
            };
        default: 
            return state;
    }
}