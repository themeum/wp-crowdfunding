
import React, { Component } from 'react';

class DatePicker extends Component {

    constructor(props) {
        super(props);
        this.inputRef = React.createRef();
    }
    
    componentDidMount() {
        const { onChange, format } = this.props;
        const inputRef = this.inputRef.current;
        $(inputRef).datepicker({
            dateFormat: format,
            onSelect: value => {
                onChange( value );
            }
        });
    }
    render() {
        const { name, value, placeholder } = this.props;
        return (
            <input 
                ref={this.inputRef}
                name={name}
                defaultValue={value} 
                placeholder={placeholder || ''}/>
        )
    }
}

export default DatePicker