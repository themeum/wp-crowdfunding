import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchFormFields } from '../../actions';

class Basic extends Component {
    constructor(props) {
        super(props);
        this.state = {};
    }

    componentDidMount() {
        this.props.fetchFormFields();
    }

    renderField() {
        switch(type) {
            case 'text': 
                return '';
            default:
                return '';
        }
    }

    render() {

        console.log(this.props);
        return (
            <div className='wpcf-accordion-wrapper'>
                <div className='wpcf-accordion active'>
                    <div className='wpcf-accordion-title'>Campaign Information</div>
                    <div className='wpcf-accordion-details'>
                        <div className='wpcf-form-field'>
                            <div className='wpcf-field-title'>Category</div>
                            <div className='wpcf-field-subtitle'>Choose the Category That Most closely aligns with your project</div>
                            <div className='wpcf-field-input'>
                                <select defaultValue={'saab'}>
                                    <option value='volvo'>Cat 1</option>
                                    <option value='saab'>Cat 2</option>
                                    <option value='vw'>Cat 3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div className='wpcf-accordion'>
                    <div className='wpcf-accordion-title'>Details</div>
                    <div className='wpcf-accordion-details'>
                        <div className='wpcf-form-field'>
                            <div className='wpcf-field-title'>Campaign Title *</div>
                            <div className='wpcf-field-subtitle'>Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.</div>
                            <div className='wpcf-field-input'>
                                <input type='text' defaultValue='Sample Title' />
                            </div>
                        </div>
                        <div className='wpcf-form-field'>
                            <div className='wpcf-field-title'>Tags</div>
                            <div className='wpcf-field-subtitle'>Reach a more specific community by also choosing right Tags. Max Tag : 20</div>
                            <div className='wpcf-field-input'>
                                <input type='text' defaultValue='Sample Sub Title' />
                            </div>
                            <div className='wpcf-field-preview'>
                                <span>+ Mobile</span>
                                <span>+ Application</span>
                                <span>+ Development</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div className='wpcf-accordion'>
                    <div className='wpcf-accordion-title'>Media</div>
                    <div className='wpcf-accordion-media'>
                        <div className='wpcf-form-field'>
                            <div className='wpcf-field-title'>Video</div>
                            <div className='wpcf-field-subtitle'>Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.</div>
                            <div className='wpcf-field-input'>
                                <button>Upload Video</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div className='wpcf-accordion'>
                    <div className='wpcf-accordion-title'>Contributor</div>
                    <div className='wpcf-accordion-contributor'>
                        <div className='wpcf-form-field'>
                            <div className='wpcf-field-title'>Contributor Table</div>
                            <div className='wpcf-field-subtitle'>You can make contributors table</div>
                            <div className='wpcf-field-input'>
                                <input type='checkbox' value='show' /> Show contributor table on campaign single page
                            </div>
                        </div>
                        <div className='wpcf-form-field'>
                            <div className='wpcf-field-title'>Contributor Anonymity</div>
                            <div className='wpcf-field-subtitle'>You can make contributors anonymus visitors will not see the backers</div>
                            <div className='wpcf-field-input'>
                                <input type='checkbox' value='show' />Make contributors anonymous on the contributor table
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}


const mapStateToProps = state => ({
    fields: state.formFields
})

export default connect( mapStateToProps, { fetchFormFields } )(Basic);
