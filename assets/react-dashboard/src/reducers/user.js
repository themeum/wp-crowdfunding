import { FETCH_USER_PENDING, FETCH_USER_COMPLETE, FETCH_USER_ERROR, SAVE_USER_DATA_PENDING, SAVE_USER_DATA_COMPLETE, SAVE_USER_DATA_ERROR } from "../actions/userAction";

export default function(state = { loading: true, loaded: false, saveReq: 'pending', data:{} }, action ) {
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
        case SAVE_USER_DATA_PENDING:
            return {
                ...state,
                saveReq: 'pending',
            };
        case SAVE_USER_DATA_COMPLETE:
            const res = action.payload;
            if( res.success ) {
                const data = Object.assign( {}, state.data, res.data );
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
        case SAVE_USER_DATA_ERROR:
            return {
                ...state,
                saveReq: 'error',
                error: action.payload,
            };
        default: 
            return state;
    }
}