import React, { Component } from 'react';

class WithdrawMethodForm extends Component {
	state = this.getDefaultState();

    getDefaultState() {
        let { methods, selected_method } = this.props.data; 
        const first_method = Object.keys(methods)[0];
        if( selected_method == null ) { 
            const method_name = methods[first_method].method_name;
            selected_method = { key: first_method, data: {[first_method]: {method_name}} }; // Default data
        } else if( !methods.hasOwnProperty(selected_method.key) ) { //if Method disable from admin
            selected_method.key = first_method;
        }
        return { methods, selected_method };
    }

    getDataValue = (item_name) => {
        const { key, data } = this.state.selected_method;
        let itemValue = "";
        if( data.hasOwnProperty(key) ) {
            itemValue = data[key][item_name] || "";
        }
        return itemValue;
    }

    setDataValue = (name, val) => {
        let { selected_method, selected_method: {key, data } } = this.state;
        selected_method.data = Object.assign({}, data, { [key]: Object.assign({}, data[key], {[name]: val}) });
        this.setState({ selected_method });
    }

    onClickMethod = (key) => {
        let { methods, selected_method } = this.state;
        selected_method.key = key;
        this.setState({ selected_method });
        this.setDataValue( 'method_name', methods[key].method_name );
    }

    onChangeInput = (e) => {
        this.setDataValue( e.target.name, e.target.value );
    }

    onSubmit = (e) => {
        e.preventDefault();
        this.props.onClickSaveData(this.state.selected_method);
    }

	render() {
        const { methods, selected_method, selected_method: {key} } = this.state;
        const formFields = methods[key].form_fields;
        return (
            <div id="wpcf-withdraw-account-set-form">
                <div className="withdraw-method-select-wrap">
                    { Object.keys( methods ).map( (key) =>
                        <div  key={ key } className="withdraw-method-select" onClick={ () => this.onClickMethod(key) }>
                            <label className={ selected_method.key == key ? 'active' : '' }>
                                <p>{methods[key].method_name}</p>
                                <span dangerouslySetInnerHTML={{__html:methods[key].desc}}/>
                            </label>
                        </div>
                    )}
                </div>
                <div className="withdraw-method-forms-wrap">
                    <form className="withdraw-method-form" onSubmit={ this.onSubmit } style={{display: 'flex'}}>
                        { formFields.map( (item, index) =>
                            <div key={index} className="withdraw-method-field-wrap">
                                <label htmlFor={ `field_${key}_${index}` }>{ item.label }</label>
                                <input id={ `field_${key}_${index}` } type={ item.type } name={ item.name } value={ this.getDataValue( item.name ) } onChange={ this.onChangeInput } required/>
                                { item.desc && 
                                    <p className="withdraw-field-desc">{ item.desc }</p>
                                }
                            </div>
                        )}
                        <div className="withdraw-account-save-btn-wrap">
                            <button type="submit" className="wpcf-btn">Save Withdraw Account</button>
                        </div>
                    </form>
                </div>
            </div>
        )
	}
}

export default WithdrawMethodForm;