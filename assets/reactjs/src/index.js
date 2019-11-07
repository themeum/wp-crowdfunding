import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { createStore, combineReducers, compose, applyMiddleware } from 'redux';
import { reducer as reduxFormReducer } from 'redux-form';
import reduxThunk from 'redux-thunk';
import reducers from './reducers';
import { setPostId } from './actions';
import App from './App';
import './styles/style.scss';

const rootReducer = combineReducers({
	data: reducers,
	form: reduxFormReducer
});

const store = createStore( rootReducer, compose(
    applyMiddleware( reduxThunk ),
    window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__()
));

const formNode = document.getElementById('wpcf-campaign-builder');
const postId = parseInt( formNode.getAttribute('postId') );

ReactDOM.render(
    <Provider store={store}>
        <App editPostId={postId}/>
    </Provider>, formNode
);
