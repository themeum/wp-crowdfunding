import { FETCH_MY_CAMPAIGNS_PENDING, FETCH_MY_CAMPAIGNS_COMPLETE, FETCH_MY_CAMPAIGNS_ERROR } from "../actions/campaignAction";

export default function(state = { loading: true, loaded: false, data:[] }, action ) {
    switch( action.type ) {
        
        case FETCH_MY_CAMPAIGNS_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_MY_CAMPAIGNS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                data: action.payload.data,
            };
        case FETCH_MY_CAMPAIGNS_ERROR:
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