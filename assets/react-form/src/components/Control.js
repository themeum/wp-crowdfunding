import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { validateRecaptcha as validateRecaptchaAction } from '../actions';
import ReCaptcha from './fields/ReCaptcha';
import Icon from './Icon';

class Control extends Component {
    constructor(props) {
        super(props);
        this.callback = this.callback.bind(this);
        this.verifyCallback = this.verifyCallback.bind(this);
    }

    callback() {
        console.log('Done!!!!');
    };
    
    verifyCallback(response) {
        this.props.validateRecaptchaAction(response);
    };

    expiredCallback() {
        this.props.validateRecaptchaAction(false);
    };

    render() {
        const { current, prevStep, lastStep, recaptcha, validateRecaptcha } = this.props;

       

        return (
            <div>
                {recaptcha && lastStep &&
                    <ReCaptcha
                        sitekey={recaptcha.siteKey}
                        verifyCallback={this.verifyCallback}
                        expiredCallback={this.expiredCallback}
                        onloadCallback={this.callback}
                    />
                }
                <div id="wpcf-page-control" className="wpcf-form-next-prev">
                    <button type="button" onClick={prevStep} disabled={current==0}><Icon name="arrowLeft" className="wpcf-icon"/> <span>Previous</span></button>
                    <button type="submit" disabled={recaptcha && !validateRecaptcha && lastStep}><span>{lastStep ? 'Submit' : 'Next'}</span> <Icon name="arrowRight" className="wpcf-icon"/></button>
                </div>
            </div>
        )
    }
}


const mapStateToProps = state => ({
    recaptcha: state.data.recaptcha,
    validateRecaptcha: state.data.validateRecaptcha,
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({
        validateRecaptchaAction
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(Control);
