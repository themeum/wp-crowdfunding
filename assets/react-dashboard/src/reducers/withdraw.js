import {
    FETCH_WITHDRAWS_PENDING,
    FETCH_WITHDRAWS_COMPLETE,
    FETCH_WITHDRAWS_ERROR,
    POST_WITHDRAW_REQUEST_PENDING,
    POST_WITHDRAW_REQUEST_COMPLETE,
    POST_WITHDRAW_REQUEST_ERROR
} from "../actions/withdrawAction";

export default function(state = { loading: true, loaded: false, reqStatus: 'pending', data:[] }, action ) {
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
        case POST_WITHDRAW_REQUEST_PENDING:
            return {
                ...state,
                reqStatus: 'pending',
            };
        case POST_WITHDRAW_REQUEST_COMPLETE:
            const res = action.payload;
            let data = [ ...state.data ];
            if( res.success ) {
                const index = data.findIndex(item => item.campaign_id == res.data.campaign_id);
                data[index]['withdraw'] = res.data.withdraw;
                return { 
                    ...state, 
                    reqStatus: 'complete',
                    data 
                };
            } else {
                return {
                    ...state,
                    reqStatus: 'error',
                    error: res.msg,
                };
            }
        case POST_WITHDRAW_REQUEST_ERROR:
            return {
                ...state,
                reqStatus: 'error',
                error: action.payload,
            };
            
        default: 
            return state;
    }
}