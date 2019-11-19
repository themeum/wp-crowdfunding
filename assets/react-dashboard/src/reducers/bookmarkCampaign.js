import { 
    FETCH_BOOKMARK_CAMPAIGNS_PENDING,
    FETCH_BOOKMARK_CAMPAIGNS_COMPLETE,
    FETCH_BOOKMARK_CAMPAIGNS_ERROR,
    DELETE_BOOKMARK_CAMPAIGN_PENDING,
    DELETE_BOOKMARK_CAMPAIGN_COMPLETE,
} from "../actions/campaignAction";

export default function(state = { loading: true, loaded: false, delReq: 'pending', data:[] }, action ) {
    switch( action.type ) {
        
        case FETCH_BOOKMARK_CAMPAIGNS_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };
        case FETCH_BOOKMARK_CAMPAIGNS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                data: action.payload,
            };
        case FETCH_BOOKMARK_CAMPAIGNS_ERROR:
            return {
                    ...state,
                    loading: false,
                    loaded: false,
                    error: action.payload,
            };
        case DELETE_BOOKMARK_CAMPAIGN_PENDING:
                return {
                    ...state,
                    delReq: 'pending',
                };
        case DELETE_BOOKMARK_CAMPAIGN_COMPLETE:
            const res = action.payload;
            let data = [ ...state.data ];
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