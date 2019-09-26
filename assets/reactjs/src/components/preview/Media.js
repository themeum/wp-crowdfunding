import React, { Component } from 'react'
import { renderVideoLinks } from '../form/RenderField';

class PreviewMedia extends Component {
    constructor(props) {
        super(props);
        this.state = {
            mainView: 0
        }
    }

    componentDidMount() {
        this.generateMainView();
    }

    getYtVideoID(src) {
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        var match = src.match(regExp);
        if (match && match[2].length == 11) {
            return match[2];
        }
        return false;
    }

    getAllItems() {
        let items = [];
        const { video_link, video, image } = this.props;
        if(video_link.length > 0) {
            video_link.map( item => {
                if(item.src) {
                    const videoId = this.getYtVideoID(item.src);
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

    generateMainView(items) {
        let mainViewItem;
        const { mainView } = this.state;
        if( (items && items.length > 0) ) {
            mainViewItem = (typeof items[mainView] !== 'undefined') ? items[mainView] : items[0];
        } else {
            return '';
        }
        if(mainViewItem.type == 'youtube') {
            return (
                <iframe width="100%" height="300" src={`//www.youtube.com/embed/${this.getYtVideoID(mainViewItem.src)}`}/>
            );
        }
        if(mainViewItem.type == 'video') {
            return (
                <video controls><source src={mainViewItem.src} type={mainViewItem.mime}/>Your browser does not support the video tag</video>
            );
        }
        if(mainViewItem.type == 'image') {
            return (
                <img src={mainViewItem.src}/>
            );
        }
    }

    render() {
        const { } = this.state;
        const items = this.getAllItems();
        return (
            <div className="preview-media">
                <div className="main-view">
                    {this.generateMainView(items)}
                </div>
                <div className="thumbnails-view">
                    {items && items.map( (item, index) =>
                        <img key={index} src={item.thumb} onClick={() => this.setState({ mainView: index })} alt="Thumbnail"/>
                    )}
                </div>
            </div>
        )
    }
}

export default PreviewMedia;