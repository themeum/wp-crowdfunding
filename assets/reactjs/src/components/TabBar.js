import React, { Component } from "react";

const defData = [
	{ name: "Basic", value: "basic" },
	{ name: "Story", value: "story" },
	{ name: "Reward", value: "reward" },
	{ name: "Team", value: "team" }
];

class TabBar extends Component {
	render() {
		const { current, onSet } = this.props;
		return (
            <div className="row">
				<div className="col-md-12">
                    <div className="wpcf-form-tab" style={{display: 'flex', borderBottom: '1px solid #eee'}}>
                        {defData.map((val, i) => {
                            return (
                                <div
                                    style={{margin: '10px'}}
                                    className={ "wpcf-tab-title " + (current == val.value ? "active" : "") }
                                    onClick={() => onSet(val.value)}
                                    key={i} >
                                    {val.name}
                                </div>
                            );
                        })}
                    </div>
                </div>
            </div>
        );
	}
}
export default TabBar;
