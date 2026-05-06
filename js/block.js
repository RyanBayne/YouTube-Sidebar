( function( blocks, element, components, i18n, blockEditor ) {
    var el = element.createElement;
    var InspectorControls = blockEditor.InspectorControls;
    var TextControl = components.TextControl;
    var SelectControl = components.SelectControl;
    var ToggleControl = components.ToggleControl;
    var PanelBody = components.PanelBody;
    var __ = i18n.__;

    blocks.registerBlockType( 'youtubesidebar/display', {
        title: __( 'YouTube Display', 'youtubesidebar' ),
        icon: 'video-alt3',
        category: 'embed',
        attributes: {
            id: { type: 'string', default: '' },
            url: { type: 'string', default: '' },
            type: { type: 'string', default: 'video' },
            layout: { type: 'string', default: 'featured' },
            privacy: { type: 'boolean', default: true },
            autoplay: { type: 'boolean', default: false },
            mute: { type: 'boolean', default: false },
            captions: { type: 'boolean', default: false },
            ratio: { type: 'string', default: '16:9' },
            title: { type: 'string', default: '' }
        },
        edit: function( props ) {
            var attrs = props.attributes;
            var set = function( key ) {
                return function( value ) {
                    var next = {};
                    next[ key ] = value;
                    props.setAttributes( next );
                };
            };

            return [
                el( InspectorControls, { key: 'inspector' },
                    el( PanelBody, { title: __( 'Display', 'youtubesidebar' ) },
                        el( SelectControl, {
                            label: __( 'Source type', 'youtubesidebar' ),
                            value: attrs.type,
                            options: [
                                { label: __( 'Video', 'youtubesidebar' ), value: 'video' },
                                { label: __( 'Playlist', 'youtubesidebar' ), value: 'playlist' },
                                { label: __( 'Channel', 'youtubesidebar' ), value: 'channel' },
                                { label: __( 'Live', 'youtubesidebar' ), value: 'live' }
                            ],
                            onChange: set( 'type' )
                        } ),
                        el( SelectControl, {
                            label: __( 'Layout', 'youtubesidebar' ),
                            value: attrs.layout,
                            options: [
                                { label: __( 'Featured', 'youtubesidebar' ), value: 'featured' },
                                { label: __( 'Sidebar', 'youtubesidebar' ), value: 'sidebar' },
                                { label: __( 'Gallery', 'youtubesidebar' ), value: 'gallery' },
                                { label: __( 'Carousel', 'youtubesidebar' ), value: 'carousel' },
                                { label: __( 'Accordion', 'youtubesidebar' ), value: 'accordion' },
                                { label: __( 'Playlist strip', 'youtubesidebar' ), value: 'playlist-strip' },
                                { label: __( 'Live now', 'youtubesidebar' ), value: 'live-now' }
                            ],
                            onChange: set( 'layout' )
                        } ),
                        el( TextControl, { label: __( 'Aspect ratio', 'youtubesidebar' ), value: attrs.ratio, onChange: set( 'ratio' ) } ),
                        el( ToggleControl, { label: __( 'Privacy-enhanced embed', 'youtubesidebar' ), checked: attrs.privacy, onChange: set( 'privacy' ) } ),
                        el( ToggleControl, { label: __( 'Autoplay', 'youtubesidebar' ), checked: attrs.autoplay, onChange: set( 'autoplay' ) } ),
                        el( ToggleControl, { label: __( 'Muted', 'youtubesidebar' ), checked: attrs.mute, onChange: set( 'mute' ) } ),
                        el( ToggleControl, { label: __( 'Captions', 'youtubesidebar' ), checked: attrs.captions, onChange: set( 'captions' ) } )
                    )
                ),
                el( 'div', { key: 'preview', className: 'youtubesidebar-block-placeholder' },
                    el( TextControl, {
                        label: __( 'YouTube URL or ID', 'youtubesidebar' ),
                        value: attrs.url || attrs.id,
                        onChange: function( value ) {
                            props.setAttributes( { url: value, id: '' } );
                        }
                    } ),
                    el( TextControl, { label: __( 'Title', 'youtubesidebar' ), value: attrs.title, onChange: set( 'title' ) } )
                )
            ];
        },
        save: function() {
            return null;
        }
    } );
} )( window.wp.blocks, window.wp.element, window.wp.components, window.wp.i18n, window.wp.blockEditor || window.wp.editor );
