import {
    FETCH_CAMPAIGNS_REPORT_PENDING,
    FETCH_CAMPAIGNS_REPORT_COMPLETE,
    FETCH_CAMPAIGNS_REPORT_ERROR
} from "../actions/campaignAction";

export default function(state = { loading: true, loaded: false, data:[] }, action ) {
    switch( action.type ) {
        
        case FETCH_CAMPAIGNS_REPORT_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_CAMPAIGNS_REPORT_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                data: action.payload,
            };
        case FETCH_CAMPAIGNS_REPORT_ERROR:
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