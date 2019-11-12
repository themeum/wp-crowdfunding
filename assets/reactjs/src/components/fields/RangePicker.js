import React, {Component} from 'react';
class RangePicker extends Component{
    constructor(props){
        super(props);
        this.rangeRef = React.createRef();
    }

    componentDidMount() {
        const {min, max, step, value, onChange} = this.props;
        const rangeRef = this.rangeRef.current;
        const option = { step, min, max, orientation: 'horizontal' };
        (typeof value=='object') ? option.values = [value.min, value.max] : option.value = value;

        jQuery(rangeRef).slider({ ...option,
            slide: (e, ui) => {
                onChange(ui.values ? {min:ui.values[0], max:ui.values[1]} : ui.value);
            }
        });
    }

    render() {
        return (
            <div className="wpcf-range-picker" ref={this.rangeRef}></div>
        )
    }
}

export default RangePicker;
