import React from 'react';

const CircleProgress = (props) => {
    const {fill, emptyFill, size} = props;
    const thickness = Number(props.thickness);
    const percent = props.percent > 100 ? 100 : props.percent;
    const ratio = 44;
    const circumference = 2 * Math.PI * ((ratio - thickness) / 2);
    const progress = circumference - ((circumference / 100) * percent);

    const style = {
        display: 'block',
        width: size.toString().replace('px', '') + 'px',
        height: size.toString().replace('px', '') + 'px',
        transform: "rotate(-90deg)"
    }

    return (
        <svg viewBox={`${ratio / 2} ${ratio / 2} ${ratio} ${ratio}`} style={style}>
            <circle
                cx={ratio}
                cy={ratio}
                r={(ratio - thickness) / 2}
                fill="none"
                stroke={emptyFill}
                strokeWidth="1"
            />
            <circle
                cx={ratio}
                cy={ratio}
                r={(ratio - thickness) / 2}
                fill="none"
                stroke={fill}
                strokeWidth={thickness}
                strokeDasharray={circumference}
                strokeDashoffset={progress}
                strokeLinecap="round"
            />
        </svg>
    )
}

CircleProgress.defaultProps = {
    fill: "#00A92F",
    emptyFill: "#ADAECF",
    size: 16,
    thickness: 6,
    percent: 0,
}

export default CircleProgress;
