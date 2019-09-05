
import React, { Component } from 'react';

class LineGraph extends Component {

    constructor(props) {
        super(props);
        this.chartRef = React.createRef();
      }
    
    componentDidMount() {
        const chartRef = this.chartRef.current.getContext("2d");
        
        new Chart(chartRef, {
            type: "line",
            data: {
                labels: [ ...this.props.label ],
                datasets: [{
                    label: new Date().getFullYear(),
                    fill: false,
                    data: [ ...this.props.format ],
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#1adc68',
                    pointHoverBorderColor: '#fff',
                    backgroundColor: '#1adc68',
                    borderColor: '#DCDCE9',
                    borderWidth: 3,
                    pointStyle: 'circle',
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        stacked: true
                    }]
                },
                elements: {
                    line: {
                        tension: 0,
                    }
                },
                legend: {
                    display: false,
                }
            }
        });
    }
    render() {
        return (
            <div className="">
                <canvas
                    id="lineChart"
                    ref={this.chartRef}
                />
            </div>
        )
    }
}

export default LineGraph