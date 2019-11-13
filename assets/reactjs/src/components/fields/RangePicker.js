import React, {Component} from 'react';
class RangePicker extends Component{
    constructor(props){
        super(props);
        this.rangeRef = React.createRef();
    }

    componentDidMount() {
        const {min, max, step, value, onChange} = this.props;
        const rangeRef = this.rangeRef.current;
        const option = { step, min, max };
        if(typeof value == 'object') {
            option.range = true;
            option.values = [value.min, value.max];
        } else {
            option.range = 'min';
            option.value = value;
        }
        jQuery(rangeRef).slider({ ...option,
            slide: (e, ui) => {
                onChange(ui.values ? {min:ui.values[0], max:ui.values[1]} : ui.value);
            }
        });
    }

    componentDidUpdate() {
        const { max, value } = this.props;
        let option = 'value';
        let rangeValue = value;
        let maxValue = value;
        if(typeof value == 'object') {
            option = 'values';
            rangeValue = [value.min, value.max];
            maxValue = value.max;
        }
        maxValue = (maxValue > max) ? maxValue : max;
        jQuery(this.rangeRef.current).slider( "option", 'max', maxValue );
        jQuery(this.rangeRef.current).slider( "option", option, rangeValue );
    }

    render() {
        return (
            <div className="wpcf-range-picker" ref={this.rangeRef}></div>
        )
    }
}

export default RangePicker;
