import {
    FETCH_COUNTRIES_PENDING,
    FETCH_COUNTRIES_COMPLETE,
    FETCH_COUNTRIES_ERROR
} from "../actions/userAction";

export default function(state = { loading: true, loaded: false, data:[] }, action ) {
    switch( action.type ) {
        case FETCH_COUNTRIES_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_COUNTRIES_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                data: action.payload,
            };
        case FETCH_COUNTRIES_ERROR:
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