import { combineReducers } from 'redux';
import user from './user';
import myCampaign from './myCampaign';
import campaignsReport from './campaignsReport';
import investedCampaign from './investedCampaign';
import bookmarkCampaign from './bookmarkCampaign';
import pledgeReceived from './pledgeReceived';
import order from './order';
import withdraw from './withdraw';
import withdrawMethod from './withdrawMethod';
import countries from './countries';
import reward from './reward';

const rootReducer = combineReducers({
    user,
    myCampaign,
    campaignsReport,
    investedCampaign,
    bookmarkCampaign,
    pledgeReceived,
    order,
    withdraw,
    withdrawMethod,
    countries,
    reward
});

export default rootReducer;