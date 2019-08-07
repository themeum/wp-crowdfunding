import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { createStore, compose, applyMiddleware } from 'redux';
import reduxThunk from 'redux-thunk';
import App from './containers/app';
import rootReducer from './reducers';

const store = createStore( rootReducer, compose( 
    applyMiddleware( reduxThunk ), 
    window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__() 
));

ReactDOM.render(
    <Provider store={ store }>
        <App />
    </Provider>, document.getElementById('wpcf-dashboard')
);
