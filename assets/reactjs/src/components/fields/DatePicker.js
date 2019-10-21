
import React, { Component } from 'react';

class DatePicker extends Component {

    constructor(props) {
        super(props);
        this.inputRef = React.createRef();
    }
    
    componentDidMount() {
        const { name, onChange, format } = this.props;
        const inputRef = this.inputRef.current;
        $(inputRef).datepicker({
            dateFormat: format,
            onSelect: value => {
                const obj = {
                    target: { name, value}
                }
                onChange( obj );
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