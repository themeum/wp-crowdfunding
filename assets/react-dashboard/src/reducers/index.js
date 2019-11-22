import {
    FETCH_USER_PENDING,
    FETCH_USER_COMPLETE,
    SAVE_USER_DATA_PENDING,
    SAVE_USER_DATA_COMPLETE,
    FETCH_COUNTRIES_PENDING,
    FETCH_COUNTRIES_COMPLETE,
} from "../actions/userAction";

import {
    FETCH_CAMPAIGNS_REPORT_PENDING,
    FETCH_CAMPAIGNS_REPORT_COMPLETE,

    FETCH_MY_CAMPAIGNS_PENDING,
    FETCH_MY_CAMPAIGNS_COMPLETE,
    SAVE_CAMPAIGN_UPDATES_PENDING,
    SAVE_CAMPAIGN_UPDATES_COMPLETE,
    DELETE_CAMPAIGN_PENDING,
    DELETE_CAMPAIGN_COMPLETE,

    FETCH_INVESTED_CAMPAIGNS_PENDING,
    FETCH_INVESTED_CAMPAIGNS_COMPLETE,
    FETCH_BOOKMARK_CAMPAIGNS_PENDING,
    FETCH_BOOKMARK_CAMPAIGNS_COMPLETE,
    DELETE_BOOKMARK_CAMPAIGN_PENDING,
    DELETE_BOOKMARK_CAMPAIGN_COMPLETE,

    FETCH_PLEDGE_RECEIVED_PENDING,
    FETCH_PLEDGE_RECEIVED_COMPLETE,
    FETCH_REWARDS_PENDING,
    FETCH_REWARDS_COMPLETE,
} from "../actions/campaignAction";

import {
    FETCH_ORDERS_PENDING,
    FETCH_ORDERS_COMPLETE
} from "../actions/orderAction";

import {
    FETCH_WITHDRAWS_PENDING,
    FETCH_WITHDRAWS_COMPLETE,
    POST_WITHDRAW_REQUEST_PENDING,
    POST_WITHDRAW_REQUEST_COMPLETE,
    FETCH_WITHDRAW_METHODS_PENDING,
    FETCH_WITHDRAW_METHODS_COMPLETE,
    SAVE_WITHDRAW_ACCOUNT_PENDING,
    SAVE_WITHDRAW_ACCOUNT_COMPLETE,
} from "../actions/withdrawAction";

const defaultState = {
    user: { loading:true, loaded:false, data:{}, saveReq:'pending' },
    campaignsReport: { loading:true, loaded:false, data:[] },
    myCampaign: { loading:true, loaded:false, saveReq:'pending', delReq:'pending', data:[] },
    investedCampaign: { loading:true, loaded:false, data:[] },
    bookmarkCampaign: { loading:true, loaded:false, delReq:'pending', data:[] },
    pledgeReceived: { loading:true, loaded:false, data:[] },
    order: { loading:true, loaded:false, data:[] },
    withdraw: { loading:true, loaded:false, reqStatus:'pending', data:[] },
    withdrawMethod: { loading:true, loaded:false, saveReq:'pending', data:{} },
    countries: { loading:true, loaded:false, data:[] },
    reward: { loading:true, loaded:false, data:[] },
}

const reducer = (state = defaultState, action) => {
    switch( action.type ) {
        //USER
        case FETCH_USER_PENDING:
            let user = { ...state.user };
            user.loading = true;
            user.loaded = false;
            return { ...state, user };
            
        case FETCH_USER_COMPLETE:
            user = { ...state.user };
            user.loading = false;
            user.loaded = true;
            user.data = action.payload;
            return { ...state, user };

        case SAVE_USER_DATA_PENDING:
            user = { ...state.user };
            user.saveReq = 'pending';
            return { ...state, user };

        case SAVE_USER_DATA_COMPLETE:
            user = { ...state.user };
            user.data = Object.assign( {}, user.data, action.payload );
            user.saveReq = 'complete';
            return { ...state, user };

        //CAMPAIGNS REPORT
        case FETCH_CAMPAIGNS_REPORT_PENDING:
            let campaignsReport = { ...state.campaignsReport };
            campaignsReport.loading = true;
            campaignsReport.loaded = false;
            return { ...state, campaignsReport };

        case FETCH_CAMPAIGNS_REPORT_COMPLETE:
            campaignsReport = { ...state.campaignsReport };
            campaignsReport.loading = false;
            campaignsReport.loaded = true;
            campaignsReport.data = action.payload;
            return { ...state, campaignsReport };

        //MY CAMPAIGNS
        case FETCH_MY_CAMPAIGNS_PENDING:
            let myCampaign = { ...state.myCampaign };
            myCampaign.loading = true;
            myCampaign.loaded = false;
            return { ...state, myCampaign };

        case FETCH_MY_CAMPAIGNS_COMPLETE:
            myCampaign = { ...state.myCampaign };
            myCampaign.loading = false;
            myCampaign.loaded = true;
            myCampaign.data = action.payload;
            return { ...state, myCampaign };
            
        case SAVE_CAMPAIGN_UPDATES_PENDING:
            myCampaign = { ...state.myCampaign };
            myCampaign.saveReq = 'pending';
            return { ...state, myCampaign };
            
        case SAVE_CAMPAIGN_UPDATES_COMPLETE:
            myCampaign = { ...state.myCampaign };
            let campaignData = [ ...myCampaign.data ];
            let index = campaignData.findIndex(item => item.id == action.payload.id);
            campaignData[index]['updates'] = action.payload.updates;
            myCampaign.data = campaignData;
            myCampaign.saveReq = 'complete';
            return { ...state, myCampaign };
            
        case DELETE_CAMPAIGN_PENDING:
            myCampaign = { ...state.myCampaign };
            myCampaign.delReq = 'pending';
            return { ...state, myCampaign };

        case DELETE_CAMPAIGN_COMPLETE:
            myCampaign = { ...state.myCampaign };
            campaignData = [ ...myCampaign.data ];
            index = campaignData.findIndex(item => item.id == action.payload);
            campaignData.splice(index, 1);
            myCampaign.data = campaignData;
            myCampaign.delReq = 'complete';
            return { ...state, myCampaign };

        //INVESTED CAMPAIGNS
        case FETCH_INVESTED_CAMPAIGNS_PENDING:
            let investedCampaign = { ...state.investedCampaign };
            investedCampaign.loading = true;
            investedCampaign.loaded = false;
            return { ...state, investedCampaign };

        case FETCH_INVESTED_CAMPAIGNS_COMPLETE:
            investedCampaign = { ...state.investedCampaign };
            investedCampaign.loading = false;
            investedCampaign.loaded = true;
            investedCampaign.data = action.payload;
            return { ...state, investedCampaign };

        //BOOKMARK CAMPAIGNS
        case FETCH_BOOKMARK_CAMPAIGNS_PENDING:
            let bookmarkCampaign = { ...state.bookmarkCampaign };
            bookmarkCampaign.loading = true;
            bookmarkCampaign.loaded = false;
            return { ...state, bookmarkCampaign };

        case FETCH_BOOKMARK_CAMPAIGNS_COMPLETE:
            bookmarkCampaign = { ...state.bookmarkCampaign };
            bookmarkCampaign.loading = false;
            bookmarkCampaign.loaded = true;
            bookmarkCampaign.data = action.payload;
            return { ...state, bookmarkCampaign };

        case DELETE_BOOKMARK_CAMPAIGN_PENDING:
            bookmarkCampaign = { ...state.bookmarkCampaign };
            bookmarkCampaign.delReq = 'pending';
            return { ...state, bookmarkCampaign };
            
        case DELETE_BOOKMARK_CAMPAIGN_COMPLETE:
            bookmarkCampaign = { ...state.bookmarkCampaign };
            campaignData = [ ...bookmarkCampaign.data ];
            index = campaignData.findIndex(item => item.id == action.payload);
            campaignData.splice(index, 1);
            bookmarkCampaign.data = campaignData;
            bookmarkCampaign.delReq = 'complete';
            return { ...state, bookmarkCampaign };

        //PLEDGE RECEIVED
        case FETCH_PLEDGE_RECEIVED_PENDING:
            let pledgeReceived = { ...state.pledgeReceived };
            pledgeReceived.loading = true;
            pledgeReceived.loaded = false;
            return { ...state, pledgeReceived };

        case FETCH_PLEDGE_RECEIVED_COMPLETE:
            pledgeReceived = { ...state.pledgeReceived };
            pledgeReceived.loading = false;
            pledgeReceived.loaded = true;
            pledgeReceived.data = action.payload;
            return { ...state, pledgeReceived };
            
        //ORDERS
        case FETCH_ORDERS_PENDING:
            let order = { ...state.order };
            order.loading = true;
            order.loaded = false;
            return { ...state, order };

        case FETCH_ORDERS_COMPLETE:
            order = { ...state.order };
            order.loading = false;
            order.loaded = true;
            order.data = action.payload;
            return { ...state, order };

        //WITHDRAWS
        case FETCH_WITHDRAWS_PENDING:
            let withdraw = { ...state.withdraw };
            withdraw.loading = true;
            withdraw.loaded = false;
            return { ...state, withdraw };

        case FETCH_WITHDRAWS_COMPLETE:
            withdraw = { ...state.withdraw };
            withdraw.loading = false;
            withdraw.loaded = true;
            withdraw.data = action.payload;
            return { ...state, withdraw };

        case POST_WITHDRAW_REQUEST_PENDING:
            withdraw = { ...state.withdraw };
            withdraw.reqStatus = 'pending';
            return { ...state, withdraw };

        case POST_WITHDRAW_REQUEST_COMPLETE:
            withdraw = { ...state.withdraw };
            let withdrawData = [ ...withdraw.data ];
            if( action.payload.success ) {
                const index = withdrawData.findIndex(item => item.campaign_id == action.payload.data.campaign_id);
                withdrawData[index]['withdraw'] = action.payload.data.withdraw;
                withdraw.data = withdrawData;
                withdraw.reqStatus = 'complete';
            } else {
                withdraw.reqStatus = 'error';
                withdraw.error = action.payload.msg;
            }
            return {  ...state,  withdraw };

        //WITHDRAW METHODS
        case FETCH_WITHDRAW_METHODS_PENDING:
            let withdrawMethod = { ...state.withdrawMethod };
            withdrawMethod.loading = true;
            withdrawMethod.loaded = false;
            return { ...state, withdrawMethod };

        case FETCH_WITHDRAW_METHODS_COMPLETE:
            withdrawMethod = { ...state.withdrawMethod };
            withdrawMethod.loading = false;
            withdrawMethod.loaded = true;
            withdrawMethod.data = action.payload;
            return { ...state, withdrawMethod };

        case SAVE_WITHDRAW_ACCOUNT_PENDING:
            withdrawMethod = { ...state.withdrawMethod };
            withdrawMethod.saveReq = 'pending';
            return { ...state, withdrawMethod };

        case SAVE_WITHDRAW_ACCOUNT_COMPLETE:
            withdrawMethod = { ...state.withdrawMethod };
            withdrawMethod.saveReq = 'pending';
            let wMethodData = { ...withdrawMethod.data };
            if( action.payload.success ) {
                wMethodData['selected_method'] = action.payload.data;
                withdrawMethod.saveReq = 'complete';
                withdrawMethod.data = wMethodData;
            } else {
                withdrawMethod.saveReq = 'error';
                withdrawMethod.error = action.payload.msg;
            }
            return { ...state, withdrawMethod };

        //COUNTRIES
        case FETCH_COUNTRIES_PENDING:
            let countries = { ...state.countries };
            countries.loading = true;
            countries.loaded = false;
            return { ...state, countries };

        case FETCH_COUNTRIES_COMPLETE:
            countries = { ...state.countries };
            countries.loading = false;
            countries.loaded = true;
            countries.data = action.payload;
            return { ...state, countries };

        //REWARDS
        case FETCH_REWARDS_PENDING:
            let reward = { ...state.reward };
            reward.loading = true;
            reward.loaded = false;
            return { ...state, reward };

        case FETCH_REWARDS_COMPLETE:
            reward = { ...state.reward };
            reward.loading = false;
            reward.loaded = true;
            reward.data = action.payload;
            return { ...state, reward };

        default: 
            return state;
    }
}

export default reducer;