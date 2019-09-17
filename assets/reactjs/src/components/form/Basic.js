import React, { Component } from 'react';
import { connect } from 'react-redux';
import RenderField from './RenderField';
class Basic extends Component {
    constructor(props) {
        super(props);
        this.state = { inputData: {} };
        this.onChange = this.onChange.bind(this);
    }

    onChange() {

    }

    render() {
        const { fields } =  this.props;
        return (
            <div className='wpcf-accordion-wrapper'>

                {Object.keys(fields).map( section =>
                    <div key={section} className='wpcf-accordion'>
                        <div className='wpcf-accordion-title'>{section}</div>
                        <div className='wpcf-accordion-details'>
                            {Object.keys(fields[section]).map( field =>
                                <div key={field} className='wpcf-form-field'>
                                    <div className='wpcf-field-title'>{fields[section][field].title}</div>
                                    <div className='wpcf-field-desc'>{fields[section][field].desc}</div>
                                    <RenderField
                                        key={field}
                                        item={fields[section][field]}
                                        onChange={this.onChange}/>
                                </div>
                            )}
                        </div>
                    </div>
                )}
            </div>
        )
    }
}


const mapStateToProps = state => ({
    fields: state.formFields
})

export default connect( mapStateToProps, { } )(Basic);
