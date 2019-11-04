import { 
    FETCH_REQUEST_PENDING,
    FETCH_FORM_FIELDS_COMPLETE,
    FETCH_FORM_STORY_TOOLS_COMPLETE,
    FETCH_FORM_REWARD_FIELDS_COMPLETE,
    FETCH_FORM_TEAM_FIELDS_COMPLETE,
    FETCH_SUB_CATEGORIES_COMPLETE,
    FETCH_FORM_VALUES_COMPLETE,
    //FETCH_STATES_COMPLETE,
    FIELD_SHOW_HIDE,
    SAVE_CAMPAIGN_PENDING,
    SAVE_CAMPAIGN_COMPLETE
} from "../actions";

const initialValues = { basic: {media:[], goal: 1, amount_range: {min: 1, max: 5000000}}, story:[], rewards:[], team:[] };

export default function(state = { postId:0, saveDate:'', initialValues, loading: true, loaded: false, formFields:{}, rewardTypes:{}, rewardFields:{}, saveReq:false }, action ) {
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
            const { types, fields } = action.payload
            return {
                ...state,
                loading: false,
                loaded: true,
                rewardTypes: types,
                rewardFields: fields,
            };

        case FETCH_FORM_TEAM_FIELDS_COMPLETE:
            return {
                ...state,
                loading: false,
                loaded: true,
                teamFields: action.payload,
            };

        case FETCH_SUB_CATEGORIES_COMPLETE:
        //case FETCH_STATES_COMPLETE:
            const res = action.payload;
            let formFields = {...state.formFields};
            formFields[res.section][res.field].options = res.options;
            return {
                ...state,
                formFields,
            };

        case FETCH_FORM_VALUES_COMPLETE:
            return {
                ...state,
                initialValues: action.payload,
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
            const { postId, saveDate } = action.payload;
            return {
                ...state,
                postId,
                saveDate,
            };
        default:
            return state;
    }
}

const multiIndex = (obj, is) => {
    return is.length ? multiIndex(obj[is[0]], is.slice(1)) : obj;
}