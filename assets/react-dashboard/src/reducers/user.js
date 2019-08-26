import { FETCH_USER_PENDING, FETCH_USER_COMPLETE, FETCH_USER_ERROR } from "../actions/userAction";

export default function(state = { loading: true, loaded: false, data:{} }, action ) {
    switch( action.type ) {
        case FETCH_USER_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_USER_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                data: action.payload,
            };
        case FETCH_USER_ERROR:
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