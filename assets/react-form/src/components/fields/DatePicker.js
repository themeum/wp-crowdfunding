
import React, {Component, Fragment} from 'react';
class DatePicker extends Component {
    constructor(props) {
        super(props);
        this.inputRef = React.createRef();
    }

    componentDidMount() {
        const { onChange, format } = this.props;
        const inputRef = this.inputRef.current;
        jQuery(inputRef).datepicker({
            dateFormat: format,
            onSelect: value => {
                onChange( value );
            }
        });
    }

    render() {
        const { name, value, placeholder, label } = this.props;
        return (
            <Fragment>
                {label && <label>{label}</label>}
                <input
                    type="text"
                    ref={this.inputRef}
                    name={name}
                    defaultValue={value}
                    placeholder={placeholder || ''}
                />
            </Fragment>
        )
    }
}

export default DatePicker
