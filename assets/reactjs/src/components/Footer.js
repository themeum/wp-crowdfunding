import React, { Component } from "react";

const defData = ["basic", "story", "reward", "team"];

class Footer extends Component {
	render() {
		const { current, onSet } = this.props;
		const currentIndex = defData.indexOf(current);
		return (
			<div className="row">
				<div className="col-md-12">
					{ (currentIndex > 0) &&
						<button onClick={() => onSet(defData[currentIndex-1])}>Previous</button>
					}
					{ (currentIndex+1 < defData.length) &&
						<button onClick={() => onSet(defData[currentIndex+1])}>Next</button>
					}
					{ (currentIndex+1 == defData.length) &&
						<button onClick={() => onSet(defData[currentIndex+1])}>Submit</button>
					}
				</div>
			</div>
		);
	}
}

export default Footer;
