import { FETCH_WITHDRAWS_PENDING, FETCH_WITHDRAWS_COMPLETE, FETCH_WITHDRAWS_ERROR, POST_WITHDRAW_REQUEST_COMPLETE } from "../actions/orderAction";

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
        case POST_WITHDRAW_REQUEST_COMPLETE:
            const res = action.payload;
            let data = [ ...state.data ];
            console.log(res);
            if( res.success ) {
                index = data.findIndex(item => item.campaign_id === res.data.campaign_id);
                data[index]['withdraw'] = res.data.withdraw;
                data = JSON.parse( JSON.stringify(data) );
                return { ...state, data }
            }
            return {
                ...state,
                error: action.payload,
            };
        default: 
            return state;
    }
}