const { __ } = wp.i18n;
const { InspectorControls, RichText } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody,SelectControl } = wp.components;

const {
    BorderRadius,
    Toggle,
    Range,
    Typography,
    Color,
    Tabs,
    Tab,
    Border,
    CssGenerator: { CssGenerator },
    gloalSettings: { globalSettingsPanel, animationSettings, interactionSettings }, 
} = wp.qubelyComponents
 
  
class Edit extends Component {
    constructor(props) {
        super(props)
        this.state = { device: 'md', selector: true, spacer: true, openPanelSetting: '' };
    }
    componentDidMount() {
        const { setAttributes, clientId, attributes: { uniqueId } } = this.props
        const _client = clientId.substr(0, 6)
        if (!uniqueId) {
            setAttributes({ uniqueId: _client });
        } else if (uniqueId && uniqueId != _client) {
            setAttributes({ uniqueId: _client });
        }
    }

    handlePanelOpenings = (panelName) => {
        this.setState({ ...this.state, openPanelSetting: panelName })
    }

    render() {
        const {
            setAttributes,
            attributes: {
                uniqueId,
                layout,
                enableTitle,
                searchtitle,
                inputTypography,
                inputBg,
                inputColor,
                placeholderColor,
                inputBgFocus,
                inputBorderColorFocus,
                border,
                borderRadius,
                btnTypography, 
                btnBorder, 
                btnBorderRadius, 
                buttonBgColor, 
                btnTextColor, 
                btnBgHoverColor, 
                btnTextHoverColor,
                SearchTypography,
                searchTextColor,
                titleSpacing,

                animation,
                enablePosition,
				selectPosition,
				positionXaxis,
                positionYaxis,
                globalZindex,
				hideTablet,
				hideMobile,
				globalCss,
				interaction
            },
        } = this.props

        const { device } = this.state

        if (uniqueId) { CssGenerator(this.props.attributes, 'upskill-core-tutor-course-search', uniqueId) }

        return (
            <Fragment>
                <InspectorControls key="inspector">
                    <PanelBody title='' initialOpen={true}>
                        <SelectControl
							label={__("Select Layout")}
							value={layout}
							options={[
								{ label: __('Layout 1'), value: 1 },
								{ label: __('Layout 2'), value: 2 },
							]}
							onChange={value => setAttributes({ layout: value })}
						/>
					</PanelBody>
                    <PanelBody title={__('Search Title')} initialOpen={false}>
                        <Toggle 
                            label={__('Disable Title')} 
                            value={enableTitle} 
                            onChange={value => setAttributes({ enableTitle: value })} 
                        />
                        { enableTitle &&
                            <Fragment>
                                <Typography
                                    label={__('Typography')}
                                    value={SearchTypography}
                                    onChange={value => setAttributes({ SearchTypography: value })} 
                                />
                                <Color 
                                    label={__('Text Color')} 
                                    value={searchTextColor} 
                                    onChange={value => setAttributes({ searchTextColor: value })} 
                                />
                                <Range 
                                    label={__('Spacing')} 
                                    value={titleSpacing} 
                                    onChange={val => setAttributes({ titleSpacing: val })} 
                                    min={0} max={200} unit={['px', 'em', '%']} 
                                    responsive device={device} 
                                    onDeviceChange={value => this.setState({ device: value })} 
                                />
                            </Fragment>
                        }
                    </PanelBody>
                    <PanelBody title={__('Input')} initialOpen={false}>
                        <Typography
                            label={__('Typography')}
                            value={inputTypography}
                            onChange={value => setAttributes({ inputTypography: value })} 
                        />
                        {layout == 2 &&
                            <Fragment>
                                <Border 
                                    label={__('Border')} 
                                    value={border} 
                                    onChange={val => setAttributes({ border: val })} 
                                    min={0} max={10} unit={['px', 'em', '%']} 
                                    responsive device={device} onDeviceChange={value => this.setState({ device: value })} 
                                />
                                <BorderRadius 
                                    min={0} max={100} 
                                    responsive device={device} 
                                    label={__('Border Radius')} 
                                    value={borderRadius} unit={['px', 'em', '%']} 
                                    onChange={value => setAttributes({ borderRadius: value })} 
                                    onDeviceChange={value => this.setState({ device: value })} 
                                />
                            </Fragment>
                        }	
                        <Tabs>
                            <Tab tabTitle={__('Normal')}>
                                { layout == 2 &&
                                    <Color label={__('Background Color')} value={inputBg} onChange={value => setAttributes( { inputBg: value }) } />
                                }
                                <Color label={__('Input Text Color')} value={inputColor} onChange={value => setAttributes({ inputColor: value })} />
                                <Color label={__('Placeholder Color')} value={placeholderColor} onChange={value => setAttributes({ placeholderColor: value })} />
                            </Tab>
                            <Tab tabTitle={__('Focus')}>
                                { layout == 2 &&
                                    <Color label={__('Background Color')} value={inputBgFocus} onChange={val => setAttributes({ inputBgFocus: val })} />
                                }
                                <Color label={__('Border Color')} value={inputBorderColorFocus} onChange={(value) => setAttributes({ inputBorderColorFocus: value })} />
                            </Tab>
                        </Tabs>
                    </PanelBody>

                    {layout == 2 &&
                        <PanelBody title={__('Button Settings')} initialOpen={false}>
                            <Typography
                                label={__('Typography')}
                                value={btnTypography}
                                onChange={value => setAttributes({ btnTypography: value })} 
                            />
                            <Border 
                                label={__('Border')} 
                                value={btnBorder} 
                                onChange={val => setAttributes({ btnBorder: val })} 
                                min={0} max={10} unit={['px', 'em', '%']} 
                                responsive device={device} onDeviceChange={value => this.setState({ device: value })} 
                            />
                            <BorderRadius 
                                min={0} max={100} 
                                responsive device={device} 
                                label={__('Border Radius')} 
                                value={btnBorderRadius} unit={['px', 'em', '%']} 
                                onChange={value => setAttributes({ btnBorderRadius: value })} 
                                onDeviceChange={value => this.setState({ device: value })} 
                            />
                            <Tabs>
                                <Tab tabTitle={__('Normal COlor')}>
                                    <Color label={__('Background Color')} value={buttonBgColor} onChange={value => setAttributes( { buttonBgColor: value }) } />
                                    <Color label={__('Text Color')} value={btnTextColor} onChange={value => setAttributes({ btnTextColor: value })} />
                                </Tab>

                                <Tab tabTitle={__('Hover Color')}>
                                    <Color label={__('Background Hover Color')} value={btnBgHoverColor} onChange={val => setAttributes({ btnBgHoverColor: val })} />
                                    <Color label={__('Text Hover Color')} value={btnTextHoverColor} onChange={value => setAttributes({ btnTextHoverColor: value })} />
                                </Tab>
                            </Tabs>
                        </PanelBody>
                    }

                    {animationSettings(uniqueId, animation, setAttributes)}
                    {interactionSettings(uniqueId, interaction, setAttributes)}

                </InspectorControls>

                {globalSettingsPanel(enablePosition, selectPosition, positionXaxis, positionYaxis, globalZindex, hideTablet, hideMobile, globalCss, setAttributes)}

                <div className={`qubely-block-${uniqueId}`}>
                    <div className={`crowdfunding-form-search-wrapper layout-${layout}`}>
                        { enableTitle &&
                            <div className={`crowdfunding-title-inner`}>
                                <div onClick={() => this.handlePanelOpenings('Title')}>
                                    <RichText
                                        key="editable"
                                        tagName="span"
                                        className="crowdfunding-search-title"
                                        keepPlaceholderOnFocus
                                        placeholder={__('Add Text...')}
                                        onChange={value => setAttributes({ searchtitle: value })}
                                        value={searchtitle} />
                                </div>
                            </div>
                        }

                        <form className={`qubely-form`} role="search" method="get" id="searchform" action="">
                            <div class="form-inlines">
                                { layout == 2 ? 
                                <div className="crowdfunding-search-wrapper search">
                                    <div class="crowdfunding-course-search-icons">
                                        <img src={thm_option.plugin+'assets/img/search.svg'} />
                                    </div>
                                    <input type="text" className="crowdfunding-coursesearch-input" placeholder="Search your courses" name="s" />
                                    <button type="submit">Search</button>
                                </div>
                                : 
                                <div className={`crowdfunding-search-wrapper search search-layout-${layout}`}>
                                    <input type="text" className="crowdfunding-coursesearch-input" placeholder="Search your courses" name="s" />
                                    <div class="crowdfunding-course-search-icons"></div>
                                    <button type="submit"> <img src={thm_option.plugin+'assets/img/search1.svg'} /></button>
                                </div>
                                }
                            </div>
                        </form>
                    </div>
                </div>
            </Fragment>
        )
    }
}
export default Edit
