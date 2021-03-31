import React from 'react';

const CollapseVideo = ({ data }) => {
	if (data.value && data.value.length > 0) {
		const videoName = data.value[0].name;
		return (
			<div className='wpcf-story-item-image'>
				<i className='fas fa-video'></i>
				{videoName}
			</div>
		);
	}
	return (
		<div className='wpcf-story-item-image '>
			<i className='fas fa-video'></i>
		</div>
	);
};

const CollapseImage = ({ data }) => {
	if (data.value && data.value.length > 0) {
		const imageName = data.value[0].name;
		return (
			<div className='wpcf-story-item-image'>
				<i className='fas fa-image'></i>
				{imageName}
			</div>
		);
	}
	return (
		<div className='wpcf-story-item-image '>
			<i className='fas fa-image'></i>
		</div>
	);
};

const extractTextFromHTML = (value) => {
	var div = document.createElement('div');
	div.innerHTML = value;
	var text = div.textContent;
	if (text.length > 30) {
		var str = text.slice(0, 20) + '...';
	}
	return str || text;
};

const RenderCollapseStory = ({ data }) => {
	switch (data.type) {
		case 'video':
			return <CollapseVideo data={data} />;
		case 'image':
			return <CollapseImage data={data} />;
		case 'embeded':
		case 'text':
			return <div>{extractTextFromHTML(data.value)}</div>;
		default:
			return null;
	}
};

export default RenderCollapseStory;
