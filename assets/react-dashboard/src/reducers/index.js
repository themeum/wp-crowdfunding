import { combineReducers } from 'redux';
import userReducer from './reducer_user';

const rootReducer = combineReducers({
    user: userReducer
});

export default rootReducer;