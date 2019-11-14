import React from 'react';

const PreviewReward = (props) => {
    const { months, rewards, selectedItem } = props;
    const getValue = key => {
        return rewards.length > 0 && rewards[selectedItem] ? rewards[selectedItem][key] : '';
    };
    const getMonthName = key => {
        const month = months.filter( item => item.value == key );
        return (month.length > 0) ? month[0].label : '';
    }
    return (
        <div className="wpcf-preview-reward">
            <div className="reward-thumb">
                { getValue('image') &&
                  rewards[selectedItem].image.length > 0 
                ?
                    <div className="reward-img">
                        <img src={rewards[selectedItem].image[0].src}/>
                    </div>
                :
                    <div className="reward-img">
                        <span>No Image</span>
                    </div>
                }
                <div className="reward-content">
                    <h3>{getValue('title')}</h3>
                    <p>{getValue('description')}</p>
                    <p>Estimate Delivery Date</p>
                    <p>{getMonthName(getValue('end_month'))} {getValue('end_year')}</p>
                    <span>
                        <p>Backers</p>
                        <p>0</p>
                    </span>
                    <span>
                        <p>Remain</p>
                        <p>Unlimited</p>
                    </span>
                </div>
            </div>
        </div>
    )
}

export default PreviewReward;