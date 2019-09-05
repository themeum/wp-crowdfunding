
import React, { Component } from 'react';

class LineGraph extends Component {
    chartRef = React.createRef();
    
    componentDidMount() {
        const chartRef = this.chartRef.current.getContext("2d");
        
        new Chart(chartRef, {
            data: {
                labels: this.props.labels,
                datasets: [{
                    label: this.props.label,
                    fill: false,
                    data: this.props.data,
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