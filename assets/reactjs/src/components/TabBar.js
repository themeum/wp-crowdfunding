import React, { Component } from "react";

const defData = [
	{ name: "Campaign Basics", value: "basic" },
	{ name: "Story", value: "story" },
	{ name: "Rewards", value: "reward" },
	{ name: "Team", value: "team" }
];

class TabBar extends Component {
	render() {
		const { current, onSet } = this.props;
		return (
            <div className="row">
				<div className="col-md-12">
                    <div className="wpcf-form-tab">
                        {defData.map((val, i) => {
                            return (
                                <div key={i} className={ "wpcf-tab-title " + (current == val.value ? "active" : "") } onClick={() => onSet(val.value)}>{val.name}</div>
                            );
                        })}
                    </div>
                </div>
            </div>
        );
	}
}
export default TabBar;
