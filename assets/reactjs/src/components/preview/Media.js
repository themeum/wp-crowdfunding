import React, { Component } from 'react'
import { getYotubeVideoID, generateBasicPreview } from '../../Helper'

class PreviewMedia extends Component {
    constructor(props) {
        super(props);
        this.state = {
            index: 0
        }
    }

    componentDidMount() {
        generateBasicPreview();
    }

    getAllItems() {
        let items = [];
        const { data } = this.props;
        const video_link = data.video_link || [];
        const video = data.video || [];
        const image = data.image || [];
        if(video_link.length > 0) {
            video_link.map( item => {
                if(item.src) {
                    const videoId = getYotubeVideoID(item.src);
                    items.push({
                        id: videoId,
                        type: 'youtube',
                        src: item.src,
                        thumb: `https://img.youtube.com/vi/${videoId}/default.jpg`,
                    });
                }
            });
        }
        return items.concat(video).concat(image);
    }

    render() {
        const { } = this.state;
        const items = this.getAllItems();
        return (
            <div className="preview-media">
                <div className="main-view">
                    {generateBasicPreview(items, this.state.index)}
                </div>
                <div className="thumbnails-view">
                    {items && items.map( (item, index) =>
                        <img key={index} src={item.thumb} onClick={() => this.setState({ index })} alt="Thumbnail"/>
                    )}
                </div>
            </div>
        )
    }
}

export default PreviewMedia;