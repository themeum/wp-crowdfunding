import React, { Component } from 'react';

class WithdrawMethodForm extends Component {
	constructor (props) {
        super(props);
        this.state = { ...this.props.data }
        this.onChangeData = this.onChangeData.bind(this);
        this.onChangeInput = this.onChangeInput.bind(this);
    }

    onChangeData( key, value ) {
        const selected_method = { ...this.state.selected_method, [key]: value };
        this.setState({ selected_method });
    }

    onChangeInput(e) {
        let { key, data } = this.state.selected_method;
        data = Object.assign({}, data, { [key]: Object.assign({}, data[key], { [e.target.name]: e.target.value } ) } );
        this.onChangeData( 'data', data );
    }

	render() {
        console.log( this.state );
        const { methods, selected_method, selected_method: {key, data} } = this.state;
        const formFields = methods[key].form_fields;

        return (
            <div id="wpcf-withdraw-account-set-form">
                <div className="withdraw-method-select-wrap">
                    { Object.keys( methods ).map( (key) =>
                        <div  key={ key } className="withdraw-method-select" onClick={ () => this.onChangeData('key', key) }>
                            <label className={ selected_method.key == key ? 'active' : '' }>
                                <p>{methods[key].method_name}</p>
                                <span dangerouslySetInnerHTML={{__html:methods[key].desc}}/>
                            </label>
                        </div>
                    )}
                </div>
                <div className="withdraw-method-forms-wrap">
                    <div className="withdraw-method-form" style={{display: 'flex'}}>
                        { formFields.map( (item, index) =>
                            <div key={index} className="withdraw-method-field-wrap">
                                <label htmlFor={ `field_${key}_${index}` }>{ item.label }</label>
                                <input id={ `field_${key}_${index}` } type={ item.type } name={ item.name } value={ (data.hasOwnProperty(key)) ? data[key][item.name] : '' } onChange={ this.onChangeInput } />
                                { item.desc && 
                                    <p className="withdraw-field-desc">{ item.desc }</p>
                                }
                            </div>
                        )}
                        <input 
                            type="button" 
                            className="wpcf-btn"
                            onClick={ () => this.props.onClickSaveData( selected_method ) }
                            value="Save Withdraw Account" />
                    </div>
                </div>
            </div>
        )
	}
}

export default WithdrawMethodForm;