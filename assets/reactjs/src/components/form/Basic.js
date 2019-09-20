import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Field, reduxForm } from 'redux-form';
import RenderField from './RenderField';
import Validate from './Validate'
class Basic extends Component {
    constructor(props) {
        super(props);
    }

    onSubmit(values) {
        console.log( values );
    }

    render() {
        const { fields, handleSubmit } =  this.props;
        return (
            <div className='wpcf-accordion-wrapper'>
                <form onSubmit={handleSubmit(this.onSubmit.bind(this))}>
                    {Object.keys(fields).map( section =>
                        <div key={section} className='wpcf-accordion'>
                            <div className='wpcf-accordion-title'>{section}</div>
                            <div className='wpcf-accordion-details'>
                                {Object.keys(fields[section]).map( field =>
                                    <div key={field} className='wpcf-form-field'>
                                        <div className='wpcf-field-title'>{fields[section][field].title}</div>
                                        <div className='wpcf-field-desc'>{fields[section][field].desc}</div>
                                        <Field name={field} item={fields[section][field]} component={RenderField}/>
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
    fields: state.data.formFields
});

export default connect(mapStateToProps)(reduxForm({
    form: 'formBasic'
})(Basic));