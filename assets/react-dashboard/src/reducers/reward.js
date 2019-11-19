import {
    FETCH_REWARDS_PENDING,
    FETCH_REWARDS_COMPLETE,
    FETCH_REWARDS_ERROR
} from "../actions/campaignAction";

export default function(state = { loading: true, loaded: false, data:[] }, action ) {
    switch( action.type ) {
        
        case FETCH_REWARDS_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_REWARDS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                data: action.payload,
            };
        case FETCH_REWARDS_ERROR:
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