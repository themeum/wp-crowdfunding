import {
    FETCH_PLEDGE_RECEIVED_PENDING,
    FETCH_PLEDGE_RECEIVED_COMPLETE,
    FETCH_PLEDGE_RECEIVED_ERROR
} from "../actions/campaignAction";

export default function(state = { loading: true, loaded: false, data:[] }, action ) {
    switch( action.type ) {
        
        case FETCH_PLEDGE_RECEIVED_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_PLEDGE_RECEIVED_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                data: action.payload,
            };
        case FETCH_PLEDGE_RECEIVED_ERROR:
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