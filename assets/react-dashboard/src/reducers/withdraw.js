import { FETCH_WITHDRAWS_PENDING, FETCH_WITHDRAWS_COMPLETE, FETCH_WITHDRAWS_ERROR } from "../actions/orderAction";

export default function(state = { loading: true, loaded: false, data:[] }, action ) {
    switch( action.type ) {
        
        case FETCH_WITHDRAWS_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_WITHDRAWS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                data: action.payload,
            };
        case FETCH_WITHDRAWS_ERROR:
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