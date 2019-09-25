import React, { Component } from 'react'
import { renderVideoLinks } from '../form/RenderField';

class PreviewMedia extends Component {
    constructor(props) {
        super(props);
        this.state = {
            mainView: ''
        }
    }

    componentDidMount() {
        this.generateMainView();
    }

    generateMainView(mainView=null) {
        const { video_link, video, image } = this.props;
        if(mainView) {
            return mainView;
        }
        if(video_link.length > 0 && video_link[0].url) {
            return `<iframe width="100%" height="300" src="${video_link[0].url}" />`;
        } else if(image && image.length > 0) {
            return `<img src="${image[0].url}" />`;
        }
    }

    _onClickThumb(type, item) {
        let mainView = '';
        if(type=='img') {
            mainView = `<img src="${item.url}" />`;
        }
        this.setState({ mainView });
    }

    render() {
        const { mainView } = this.state;
        const { video, image } = this.props;
        return (
            <div className="preview-media">
                <div className="main-view">
                    <span dangerouslySetInnerHTML={{__html: this.generateMainView()}}/>
                </div>
                <div className="thumbnails-view">
                    {video && video.map( (item, index) =>
                        <div key={index}></div>
                    )}
                    {image && image.map( (item, index) =>
                        <img key={index} src={item.url} onClick={() => this._onClickThumb('img', item)}/>
                    )}
                </div>
            </div>
        )
    }
}

export default PreviewMedia;