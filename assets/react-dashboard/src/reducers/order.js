import { FETCH_ORDERS_PENDING, FETCH_ORDERS_COMPLETE, FETCH_ORDERS_ERROR } from "../actions/orderAction";

export default function(state = { loading: true, loaded: false, data:[] }, action ) {
    switch( action.type ) {
        
        case FETCH_ORDERS_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_ORDERS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                data: action.payload,
            };
        case FETCH_ORDERS_ERROR:
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