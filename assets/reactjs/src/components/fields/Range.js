import React, {Component} from 'react';

class Range extends Component{

    constructor(props){
        super(props);
        this.inputRef = React.createRef();
    }

    componentDidMount() {
        const {onChange, min = 0, max = 100, value = 30, values = [20, 40], step = 1} = this.props;
        const inputRef = this.inputRef.current;
        jQuery(inputRef).slider({
            step,
            min,
            max,
            ...(values.length ? {values, range: true} : {value}),
            slide: function (event, ui) {
                onChange(ui.values.length ? ui.values.length : value)
            }
        });
    }

    render() {
        const {name} = this.props
        return (
            <div ref={this.inputRef}></div>
        )
    }
}

export default Range;
