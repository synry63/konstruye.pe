$.noty.themes.defaultTheme = {
    name    : 'defaultTheme',
    helpers : {
        borderFix: function() {
            if(this.options.dismissQueue) {
                var selector = this.options.layout.container.selector + ' ' + this.options.layout.parent.selector;
                switch(this.options.layout.name) {
                    case 'top':
                        $(selector).css({borderRadius: '0px 0px 0px 0px'});
                        $(selector).last().css({borderRadius: '0px 0px 5px 5px'});
                        break;
                    case 'topCenter':
                    case 'topLeft':
                    case 'topRight':
                    case 'bottomCenter':
                    case 'bottomLeft':
                    case 'bottomRight':
                    case 'center':
                    case 'centerLeft':
                    case 'centerRight':
                    case 'inline':
                        $(selector).css({borderRadius: '0px 0px 0px 0px'});
                        $(selector).first().css({'border-top-left-radius': '5px', 'border-top-right-radius': '5px'});
                        $(selector).last().css({'border-bottom-left-radius': '5px', 'border-bottom-right-radius': '5px'});
                        break;
                    case 'bottom':
                        $(selector).css({borderRadius: '0px 0px 0px 0px'});
                        $(selector).first().css({borderRadius: '5px 5px 0px 0px'});
                        break;
                    default:
                        break;
                }
            }
        }
    },
    modal   : {
        css: {
            position       : 'fixed',
            width          : '100%',
            height         : '100%',
            backgroundColor: '#000',
            zIndex         : 10000,
            opacity        : 0.6,
            display        : 'none',
            left           : 0,
            top            : 0
        }
    },
    style   : function() {

        this.$bar.css({
            overflow  : 'hidden',
            background: "url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAoCAQAAAClM0ndAAAAhklEQVR4AdXO0QrCMBBE0bttkk38/w8WRERpdyjzVOc+HxhIHqJGMQcFFkpYRQotLLSw0IJ5aBdovruMYDA/kT8plF9ZKLFQcgF18hDj1SbQOMlCA4kao0iiXmah7qBWPdxpohsgVZyj7e5I9KcID+EhiDI5gxBYKLBQYKHAQoGFAoEks/YEGHYKB7hFxf0AAAAASUVORK5CYII=') repeat-x scroll left top #fff"
        });

        this.$message.css({
            fontSize  : '13px',
            lineHeight: '16px',
            textAlign : 'center',
            padding   : '8px 10px 9px',
            width     : 'auto',
            position  : 'relative'
        });

        this.$closeButton.css({
            position  : 'absolute',
            top       : 4, right: 4,
            width     : 10, height: 10,
            background: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAQAAAAnOwc2AAAAxUlEQVR4AR3MPUoDURSA0e++uSkkOxC3IAOWNtaCIDaChfgXBMEZbQRByxCwk+BasgQRZLSYoLgDQbARxry8nyumPcVRKDfd0Aa8AsgDv1zp6pYd5jWOwhvebRTbzNNEw5BSsIpsj/kurQBnmk7sIFcCF5yyZPDRG6trQhujXYosaFoc+2f1MJ89uc76IND6F9BvlXUdpb6xwD2+4q3me3bysiHvtLYrUJto7PD/ve7LNHxSg/woN2kSz4txasBdhyiz3ugPGetTjm3XRokAAAAASUVORK5CYII=)",
            display   : 'none',
            cursor    : 'pointer'
        });

        this.$buttons.css({
            padding        : 5,
            textAlign      : 'right',
            borderTop      : '1px solid #ccc',
            backgroundColor: '#fff'
        });

        this.$buttons.find('button').css({
            marginLeft: 5
        });

        this.$buttons.find('button:first').css({
            marginLeft: 0
        });

        this.$bar.on({
            mouseenter: function() {
                $(this).find('.noty_close').stop().fadeTo('normal', 1);
            },
            mouseleave: function() {
                $(this).find('.noty_close').stop().fadeTo('normal', 0);
            }
        });

        switch(this.options.layout.name) {
            case 'top':
                this.$bar.css({
                    borderRadius: '0px 0px 5px 5px',
                    borderBottom: '2px solid #eee',
                    borderLeft  : '2px solid #eee',
                    borderRight : '2px solid #eee',
                    boxShadow   : "0 2px 4px rgba(0, 0, 0, 0.1)"
                });
                break;
            case 'topCenter':
            case 'center':
            case 'bottomCenter':
            case 'inline':
                this.$bar.css({
                    borderRadius: '5px',
                    border      : '1px solid #eee',
                    boxShadow   : "0 2px 4px rgba(0, 0, 0, 0.1)"
                });
                this.$message.css({fontSize: '13px', textAlign: 'center'});
                break;
            case 'topLeft':
            case 'topRight':
            case 'bottomLeft':
            case 'bottomRight':
            case 'centerLeft':
            case 'centerRight':
                this.$bar.css({
                    borderRadius: '5px',
                    border      : '1px solid #eee',
                    boxShadow   : "0 2px 4px rgba(0, 0, 0, 0.1)"
                });
                this.$message.css({fontSize: '13px', textAlign: 'left'});
                break;
            case 'bottom':
                this.$bar.css({
                    borderRadius: '5px 5px 0px 0px',
                    borderTop   : '2px solid #eee',
                    borderLeft  : '2px solid #eee',
                    borderRight : '2px solid #eee',
                    boxShadow   : "0 -2px 4px rgba(0, 0, 0, 0.1)"
                });
                break;
            default:
                this.$bar.css({
                    border   : '2px solid #eee',
                    boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
                });
                break;
        }

        switch(this.options.type) {
            case 'alert':
            case 'kids':
                this.$bar.css({backgroundColor: '#A70C77', borderColor: '#A70C77', color: '#fff'});
                this.$buttons.css({borderTop: '1px solid #A70C77'});
                break;
            case 'party':
                this.$bar.css({backgroundColor: '#cad538', borderColor: '#cad538', color: '#fff'});
                this.$buttons.css({borderTop: '1px solid #cad538'});
                break;
            case 'dinner':
                this.$bar.css({backgroundColor: '#0e6970', borderColor: '#0e6970', color: '#FFF'});
                this.$buttons.css({borderTop: '1px solid #0e6970'});
                break;
            case 'alerta':
                this.$bar.css({backgroundColor: '#ff0000', borderColor: '#ff0000', color: '#FFF'});
                this.$buttons.css({borderTop: '1px solid #ff0000'});
                break;
            case 'konstruye':
                this.$bar.css({backgroundColor: '#1C9CD9', borderColor: '#1C9CD9', color: '#fff'});
                this.$buttons.css({borderTop: '1px solid #1C9CD9'});
                break;
            default:
                this.$bar.css({backgroundColor: '#FFF', borderColor: '#CCC', color: '#444'});
                break;
        }
    },
    callback: {
        onShow : function() {
            $.noty.themes.defaultTheme.helpers.borderFix.apply(this);
        },
        onClose: function() {
            $.noty.themes.defaultTheme.helpers.borderFix.apply(this);
        }
    }
};
