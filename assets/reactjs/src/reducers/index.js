import { 
    FETCH_REQUEST_PENDING,
    FETCH_FORM_FIELDS_COMPLETE,
    FETCH_SUB_CATEGORIES_COMPLETE,
    FETCH_FORM_VALUES_PENDING,
    FETCH_FORM_VALUES_COMPLETE,
    //FETCH_STATES_COMPLETE,
    FIELD_SHOW_HIDE,
    SAVE_CAMPAIGN_PENDING,
    SAVE_CAMPAIGN_COMPLETE
} from "../actions";

const initialValues = { basic: {media:[], funding_goal: 1, pledge_amount: {min: 1, max: 5000000}}, story:[], rewards:[], team:[] };

export default function(state = { postId:0, saveDate:'', initialValues, loading: true, loaded: false, valReceived:true, saveReq:false, submit:false, submitData:{} }, action ) {
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
                ...action.payload,
                loading: false,
                loaded: true,
            }

        case FETCH_SUB_CATEGORIES_COMPLETE:
        //case FETCH_STATES_COMPLETE:
            const res = action.payload;
            let basic_fields = {...state.basic_fields};
            basic_fields[res.section][res.field].options = res.options;
            return {
                ...state,
                basic_fields,
            };

        case FETCH_FORM_VALUES_PENDING:
            return {
                ...state,
                valReceived: false
            };

        case FETCH_FORM_VALUES_COMPLETE:
            return {
                ...state,
                valReceived: true,
                postId: action.payload.postId,
                saveDate: action.payload.saveDate,
                initialValues: action.payload.values
            };

        case FIELD_SHOW_HIDE:
            basic_fields = {...state.basic_fields};
            let { field, show } = action.payload;
            field = multiIndex(basic_fields, field);
            field.show = show;
            return {
                ...state,
                basic_fields,
            };

        case SAVE_CAMPAIGN_PENDING:
            return {
                ...state,
                saveReq:true,
            };
            
        case SAVE_CAMPAIGN_COMPLETE:
            const { submit } = action.payload;
            if(submit) {
                const submitData = {
                    message: action.payload.message,
                    redirect: action.payload.redirect,
                }
                return {
                    ...state,
                    submit:true,
                    submitData,
                };
            } else {
                return {
                    ...state,
                    postId: action.payload.postId,
                    saveDate: action.payload.saveDate,
                };
            }
        default:
            return state;
    }
}

const multiIndex = (obj, is) => {
    return is.length ? multiIndex(obj[is[0]], is.slice(1)) : obj;
}