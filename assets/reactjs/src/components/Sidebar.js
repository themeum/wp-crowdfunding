import React, { Component } from 'react'
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { getFormValues } from 'redux-form';
import PreviewMedia from './preview/Media';

class Sidebar extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        const { current, formValues: { basic, rewards } } = this.props;
        return (
            <div className='wpcf-form-sidebar'>
                <div className="preview-title">Preview</div>
                { current == 'basic' &&
                    <PreviewMedia data={basic}/>
                }
            </div>
        )
    }
}

const mapStateToProps = state => ({
    formValues: getFormValues('campaignForm')(state)
});

function mapDispatchToProps(dispatch) {
    return bindActionCreators({
        getFormValues,
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(Sidebar);
