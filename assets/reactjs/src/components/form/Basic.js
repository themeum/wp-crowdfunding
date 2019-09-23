import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { Field, FieldArray, reduxForm, change as changeFieldValue, formValueSelector } from 'redux-form';
import { RenderField, renderVideoLinks } from './RenderField';
import Validate from './Validate'
class Basic extends Component {
    constructor(props) {
        super(props);
        this.state = { activeSection: Object.keys(this.props.fields)[0] };
        this._uploadFile = this._uploadFile.bind(this);
        this._removeFile = this._removeFile.bind(this);
    }

    onSubmit(values) {
        console.log( values );
    }

    sectionName(name) {
        return name.replace('_', ' ');
    }

    _onChangeSection(section) {
        this.setState({
            activeSection: section
        });
    }

    _uploadFile(field) {
        let files = [];
        const type = field;
        wp.media.editor.open({library: {type}});
        wp.media.editor.send.attachment = (props, attachment) => {
            files.push({
                id: attachment.id,
                name: attachment.filename,
                url: attachment.url,
            });
            this.props.changeFieldValue('formBasic', field, [...files]);
        };
    }

    _removeFile(index, field, files) {
        files = [...files];
        files.splice(index, 1);
        this.props.changeFieldValue('formBasic', field, files);
    }

    render() {
        const { activeSection } = this.state;
        const { fields, handleSubmit } =  this.props;
        return (
            <div className='wpcf-accordion-wrapper'>
                <form onSubmit={handleSubmit(this.onSubmit.bind(this))}>
                    {Object.keys(fields).map( section =>
                        <div key={section} className='wpcf-accordion'>
                            <div className={`wpcf-accordion-title ${section == activeSection ? 'active' : ''}`} onClick={ () => this._onChangeSection(section) }>{this.sectionName(section)}</div>
                            <div className='wpcf-accordion-details' style={ section == activeSection ? { display: 'block' } : { display: 'none' } } >
                                {Object.keys(fields[section]).map( field =>
                                    <div key={field} className='wpcf-form-field'>
                                        <div className='wpcf-field-title'>{fields[section][field].title}</div>
                                        <div className='wpcf-field-desc'>{fields[section][field].desc}</div>

                                        { fields[section][field].type == 'video_link' ?
                                            <FieldArray 
                                                name={field} 
                                                item={fields[section][field]}
                                                component={renderVideoLinks} />
                                            :
                                            <Field
                                                name={field}
                                                item={fields[section][field]}
                                                uploadFile={this._uploadFile}
                                                removeFile={this._removeFile}
                                                component={RenderField}/>
                                        }
                                            
                                    </div>
                                )}
                            </div>
                        </div>
                    )}
                    <button type="submit">Show form data</button>
                </form>
            </div>
        )
    }
}

const mapStateToProps = state => ({
    fields: state.data.formFields,
    initialValues: { amount_range: {min: 0, max: 5000000}, video_link: [{url:''}] },
});

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ changeFieldValue, formValueSelector }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
    form: 'formBasic'
})(Basic));