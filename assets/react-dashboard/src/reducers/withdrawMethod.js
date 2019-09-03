import { FETCH_WITHDRAW_METHODS_PENDING, FETCH_WITHDRAW_METHODS_COMPLETE, FETCH_WITHDRAW_METHODS_ERROR, SAVE_WITHDRAW_ACCOUNT_PENDING, SAVE_WITHDRAW_ACCOUNT_COMPLETE, SAVE_WITHDRAW_ACCOUNT_ERROR } from "../actions/withdrawAction";

export default function(state = { loading: true, loaded: false, saveReq: 'pending', data:{} }, action ) {
    switch( action.type ) {
        
        case FETCH_WITHDRAW_METHODS_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_WITHDRAW_METHODS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                data: action.payload,
            };
        case FETCH_WITHDRAW_METHODS_ERROR:
            return {
                ...state,
                loading: false,
                loaded: false,
                error: action.payload,
            };
        case SAVE_WITHDRAW_ACCOUNT_PENDING:
            return {
                ...state,
                saveReq: 'pending',
            };
        case SAVE_WITHDRAW_ACCOUNT_COMPLETE:
            const res = action.payload;
            let data = { ...state.data };
            if( res.success ) {
                data['selected_method'] = res.data;
                return { 
                    ...state, 
                    saveReq: 'complete',
                    data 
                };
            } else {
                return {
                    ...state,
                    saveReq: 'error',
                    error: res.msg,
                };
            }
        case SAVE_WITHDRAW_ACCOUNT_ERROR:
            return {
                ...state,
                saveReq: 'error',
                error: action.payload,
            };
        default: 
            return state;
    }
}