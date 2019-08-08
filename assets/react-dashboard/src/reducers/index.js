import { combineReducers } from 'redux';
import userReducer from './userReducer';
import campaignReducer from './campaignReducer';

const rootReducer = combineReducers({
    user: userReducer,
    campaign: campaignReducer
});

export default rootReducer;