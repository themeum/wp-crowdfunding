import { combineReducers } from 'redux';
import userReducer from './userReducer';
import myCampaign from './myCampaign';
import investedCampaign from './investedCampaign';
import bookmarkCampaign from './bookmarkCampaign';

const rootReducer = combineReducers({
    user: userReducer,
    myCampaign,
    investedCampaign,
    bookmarkCampaign,
});

export default rootReducer;