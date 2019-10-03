import React, { Component } from 'react'
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { getYotubeVideoID } from '../../../Helper'
import { getFormValues } from 'redux-form';

class PreviewBasic extends Component {
    constructor(props) {
        super(props);
        this.state = {
            index: 0
        }
    }

    componentDidMount() {
        this.genPreview();
    }

    getAllItems() {
        let items = [];
        const { formValues: {basic} } = this.props;
        const video_link = basic.video_link || [];
        const video = basic.video || [];
        const image = basic.image || [];
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

    genPreview(items, index) {
        let mainViewItem;
        if( (items && items.length > 0) ) {
            mainViewItem = (typeof items[index] !== 'undefined') ? items[index] : items[0];
        } else {
            return '';
        }
        if(mainViewItem.type == 'youtube') {
            return (
                <iframe width="100%" height="300" src={`//www.youtube.com/embed/${getYotubeVideoID(mainViewItem.src)}`}/>
            );
        } else if(mainViewItem.type == 'video') {
            return (
                <video controls><source src={mainViewItem.src} type={mainViewItem.mime}/>Your browser does not support the video tag</video>
            );
        } else if(mainViewItem.type == 'image') {
            return (
                <img src={mainViewItem.src}/>
            );
        }
    }

    render() {
        const items = this.getAllItems();
        return (
            <div className="preview-media">
                <div className="main-view">
                    {this.genPreview(items, this.state.index)}
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
const mapStateToProps = state => ({
    formValues: getFormValues('campaignForm')(state)
});

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ 
        getFormValues,
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(PreviewBasic);