import { 
    FETCH_REQUEST_PENDING,
    FETCH_FORM_FIELDS_COMPLETE,
    FETCH_FORM_STORY_TOOLS_COMPLETE,
    FETCH_FORM_REWARD_FIELDS_COMPLETE,
    FETCH_FORM_TEAM_FIELDS_COMPLETE,
    FETCH_SUB_CATEGORIES_COMPLETE,
    FETCH_STATES_COMPLETE,
    FIELD_SHOW_HIDE,
    SAVE_CAMPAIGN_PENDING,
    SAVE_CAMPAIGN_COMPLETE
} from "../actions";

export default function(state = { loading: true, loaded: false, formFields:{}, rewardTypes:{}, rewardFields:{}, saveReq:false, saveDate:{} }, action ) {
    switch( action.type ) {
        
        case FETCH_REQUEST_PENDING:
            return {
                ...state,
                loading: true,
                loaded: false,
            };

        case FETCH_FORM_FIELDS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                formFields: action.payload,
            };

        case FETCH_FORM_STORY_TOOLS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                storyTools: action.payload,
            };

        case FETCH_FORM_REWARD_FIELDS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                rewardTypes: action.payload.types,
                rewardFields: action.payload.fields,
            };

        case FETCH_FORM_TEAM_FIELDS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                teamFields: action.payload,
            };

        case FETCH_SUB_CATEGORIES_COMPLETE:
        case FETCH_STATES_COMPLETE:
            const res = action.payload;
            let formFields = {...state.formFields};
            formFields[res.section][res.field].options = res.options;
            return {
                ...state,
                formFields,
            };

        case FIELD_SHOW_HIDE:
            const fFields = {...state.formFields};
            let { field, show } = action.payload;
            field = multiIndex(fFields, field);
            field.show = show;
            return {
                ...state,
                formFields: fFields,
            };

        case SAVE_CAMPAIGN_PENDING:
            return {
                ...state,
                saveReq:true,
            };
            
        case SAVE_CAMPAIGN_COMPLETE:
            return {
                ...state,
                saveDate: action.payload,
            };
        default:
            return state;
    }
}

const multiIndex = (obj, is) => {
    return is.length ? multiIndex(obj[is[0]], is.slice(1)) : obj;
}