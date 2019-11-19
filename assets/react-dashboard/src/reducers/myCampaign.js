import { 
    FETCH_MY_CAMPAIGNS_PENDING,
    FETCH_MY_CAMPAIGNS_COMPLETE,
    FETCH_MY_CAMPAIGNS_ERROR,
    SAVE_CAMPAIGN_UPDATES_PENDING,
    SAVE_CAMPAIGN_UPDATES_COMPLETE,
    SAVE_CAMPAIGN_UPDATES_ERROR,
    DELETE_CAMPAIGN_PENDING,
    DELETE_CAMPAIGN_COMPLETE,
} from "../actions/campaignAction";

export default function(state = { loading: true, loaded: false, saveReq: 'pending', delReq: 'pending', data:[] }, action ) {
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
            let res = action.payload;
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
        case DELETE_CAMPAIGN_PENDING:
            return {
                ...state,
                delReq: 'pending',
            };
        case DELETE_CAMPAIGN_COMPLETE:
            res = action.payload;
            data = [ ...state.data ];
            if( res.success ) {
                const index = data.findIndex(item => item.id == res.campaign_id);
                data.splice(index, 1);
                return {
                    ...state,
                    delReq: 'complete',
                    data
                };
            } else {
                return {
                    ...state,
                    saveReq: 'error',
                    error: res.msg,
                };
            }
        default: 
            return state;
    }
}