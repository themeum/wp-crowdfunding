import React, { Component } from "react";

const defData = [
  { name: "Basic", value: "basic" },
  { name: "Story", value: "story" },
  { name: "Reward", value: "reward" },
  { name: "Team", value: "team" }
];

class TopBar extends Component {
  render() {
    const { current, onSet } = this.props;
    console.log(this.props);

    return (
      <div className="wpcf-form-tab">
        <div className="wpcf-form-edit-panel">
          <span>Setup New Campaign</span>
          <span>Last Edit was on 01 july</span>
          <button>Save</button>
          <button>Submit</button>
        </div>
        <div className="wpcf-form-tab">
          {defData.map((val,i) => {
            return (
              <div
                className={
                  "wpcf-tab-title " + (current == val.value ? "active" : "")
                }
                onClick={() => onSet(val.value)}
                key={i}
              >
                {val.name}
              </div>
            );
          })}
          {/* <div className={'wpcf-tab-title '+(current.selectForm == 'basic' ? 'active':'')}>Basic</div>
          <div className={'wpcf-tab-title '+(current.selectForm == 'story' ? 'active':'')}>Story</div>
          <div className={'wpcf-tab-title '+(current.selectForm == 'reward' ? 'active':'')}>Reward</div>
          <div className={'wpcf-tab-title '+(current.selectForm == 'team' ? 'active':'')}>Team</div> */}
        </div>
      </div>
    );
  }
}
export default TopBar;
