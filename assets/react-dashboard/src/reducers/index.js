import { combineReducers } from 'redux';
import userReducer from './userReducer';
import myCampaign from './myCampaign';
import investedCampaign from './investedCampaign';
import bookmarkCampaign from './bookmarkCampaign';
import pledgeReceived from './pledgeReceived';

const rootReducer = combineReducers({
    user: userReducer,
    myCampaign,
    investedCampaign,
    bookmarkCampaign,
    pledgeReceived,
});

export default rootReducer;