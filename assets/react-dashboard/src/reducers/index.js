import { combineReducers } from 'redux';
import userReducer from './userReducer';
import myCampaign from './myCampaign';
import investedCampaign from './investedCampaign';

const rootReducer = combineReducers({
    user: userReducer,
    myCampaign,
    investedCampaign,
});

export default rootReducer;