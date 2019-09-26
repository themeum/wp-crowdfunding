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
        const { current, basicValues: { video_link, video, image } } = this.props;
        return (
            <div className='wpcf-form-sidebar'>
                <div className="preview-title">Preview</div>
                { current == 'basic' } {
                    <PreviewMedia 
                        video={video || []}
                        image={image || []}
                        video_link={video_link}/>
                }
            </div>
        )
    }
}

const mapStateToProps = state => ({
    basicValues: getFormValues('formBasic')(state)
});

function mapDispatchToProps(dispatch) {
    return bindActionCreators({
        getFormValues,
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(Sidebar);
