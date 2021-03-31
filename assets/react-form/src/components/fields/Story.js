import React from 'react';
import Editor from './Editor';

//FIELD TYPES
const Image = (props) => {
	const { name, data, upload } = props;
	let imageVal = [];
	if (data.value && data.value.length > 0) {
		const imageSrc = data.value[0].src;
		imageVal = data.value;
		return (
			<div
				className='wpcf-story-item-image'
				onClick={() => upload(name, 'image', imageVal)}
			>
				<img src={imageSrc} />
			</div>
		);
	}
	return (
		<div
			className='wpcf-story-item-image wpcf-empty-image'
			onClick={() => upload(name, 'image', imageVal)}
		>
			<span className='fas fa-image'></span>
		</div>
	);
};

const Video = (props) => {
	const { name, data, upload } = props;
	return (
		<div
			className='story-item-image'
			onClick={() => upload(name, 'video', [])}
		>
			{data.value && data.value.length > 0 ? (
				<video controls>
					<source src={data.value[0].src} type={data.value[0].mime} />
					Your browser does not support the video tag
				</video>
			) : (
				<div className='wpcf-story-item-image wpcf-empty-image'>
					<span className='fas fa-video'></span>
				</div>
			)}
		</div>
	);
};

const EmbededFile = (props) => {
	const { name, data, edit } = props;
	return (
		<div className='story-item-embeded_file'>
			{data.value ? (
				<div dangerouslySetInnerHTML={{ __html: data.value }} />
			) : (
				<textarea
					value={data.value}
					onChange={(e) => edit(name, e.target.value)}
				/>
			)}
		</div>
	);
};

const TextEditor = (props) => {
	const { name, data, edit } = props;
	return (
		<Editor
			value={data.value}
			className='wpcf-story-texteditor'
			onChange={(value) => edit(name, value)}
		/>
	);
};

const RenderStoryItem = (props) => {
	switch (props.data.type) {
		case 'image':
			return <Image {...props} />;
		case 'video':
			return <Video {...props} />;
		case 'embeded':
			return <EmbededFile {...props} />;
		case 'text':
			return <TextEditor {...props} />;
		default:
			return null;
	}
};

export default RenderStoryItem;
