import { FETCH_MY_CAMPAIGNS_PENDING, FETCH_MY_CAMPAIGNS_COMPLETE, FETCH_MY_CAMPAIGNS_ERROR, SAVE_CAMPAIGN_UPDATES_PENDING, SAVE_CAMPAIGN_UPDATES_COMPLETE, SAVE_CAMPAIGN_UPDATES_ERROR } from "../actions/campaignAction";

export default function(state = { loading: true, loaded: false, saveReq: 'pending', data:[] }, action ) {
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
                data: action.payload,
            };
        case FETCH_MY_CAMPAIGNS_ERROR:
            return {
                    ...state,
                    loading: false,
                    loaded: false,
                    error: action.payload,
            };
        case SAVE_CAMPAIGN_UPDATES_PENDING:
            return {
                ...state,
                saveReq: 'pending',
            };
        case SAVE_CAMPAIGN_UPDATES_COMPLETE:
            const res = action.payload;
            let data = [ ...state.data ];
            if( res.success ) {
                const index = data.findIndex(item => item.id == res.id);
                data[index]['updates'] = res.updates;
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
        case SAVE_CAMPAIGN_UPDATES_ERROR:
            return {
                ...state,
                saveReq: 'error',
                error: action.payload,
            };
        default: 
            return state;
    }
}