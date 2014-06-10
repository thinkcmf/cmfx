/*
 * PHPWind util Library
 * @Copyright 	: Copyright 2011, phpwind.com
 * @Descript	: colorPicker 对话框组件
 * @Author		: chaoren1641@gmail.com
 * @Depend		: core.js、jquery.js(1.7 or later)
 * $Id: colorPicker.js 20032 2012-10-23 01:46:13Z chris.chencq $			:
 */
;(function ( $, window, document, undefined ) {
    var pluginName = 'colorPicker';
    var defaults = {
    	event			: 'click',
        callback		: $.noop,
        zIndex			: 5,
        default_color   : '#ffffff'
    };
    var pre = '<strong style="background-color:#',
        suf = ';"><span></span></strong>';
    var htmlGen = [pre, 'ffffff,000000,eeece1,1f497d,4f81bd,c0504d,9bbb59,8064a2,4bacc6,f79646'.split(',').join(suf + pre), suf].join('');

    var htmlList = [pre, 'f2f2f2,7f7f7f,ddd9c3,c6d9f0,dbe5f1,f2dcdb,ebf1dd,e5e0ec,dbeef3,fdeada,d8d8d8,595959,c4bd97,8db3e2,b8cce4,e5b9b7,d7e3bc,ccc1d9,b7dde8,fbd5b5,bfbfbf,3f3f3f,938953,548dd4,95b3d7,d99694,c3d69b,b2a2c7,92cddc,fac08f,a5a5a5,262626,494429,17365d,366092,953734,76923c,5f497a,31859b,e36c09,7f7f7f,0c0c0c,1d1b10,0f243e,244061,632423,4f6128,3f3151,205867,974806'.split(',').join(suf + pre), suf].join('');

    var htmlStandard = [pre, 'c00000,ff0000,ffc000,ffff00,92d050,00b050,00b0f0,0070c0,002060,7030a0'.split(',').join(suf + pre), suf].join('');

    var htmlGeneralPanel = ['<div class="wind_color_general" style="display:none"><div class="color_general">', htmlGen, '</div><div class="color_list">', htmlList, '</div><div class="color_standard">', htmlStandard, '</div><div class="set_general"><a href="javascript:;" class="J_set_general" title="点击打开高级颜色选择面板">高级颜色</a><a class="J_default_color no_color" href="javascript:;">恢复默认</a></div></div>'].join('');

    var htmlSeniorPanel = '<div class="wind_color_senior" style="display:none">\
		    <div class="color_panel"><span></span></div>\
		    <div class="lightness_panel"><strong></strong>' + new Array(63).join('<span></span>') + '</div>\
		    <div class="set_senior">\
		    	<a href="javascript:;" class="J_set_senior" title="点击打开常规颜色选择面板"></a>\
		    </div>\
		    <div class="color_value">\
		    <span class="current_color"></span>#<input type="text" value="" maxlength="6"/>\
		    	<button title="确认"><span>确认</span></button>\
		    </div>\
    	</div>';

    var rColor = /^#?(?=(?:[a-f\d]{3}|[a-f\d]{6})$)([a-f\d]{1,2})([a-f\d]{1,2})([a-f\d]{1,2})$/i,
        rSharp = /^#/,
        rFloatTag = /^(?:a|span|button|input)$/i;

    function Plugin( element, options ) {
		this.element = element;
		this.options = $.extend( {}, defaults, options) ;
        this.container = $('<div class="colorPicker" id="colorPicker" style="display:none" />').html(htmlGeneralPanel + htmlSeniorPanel).appendTo('body');
        this.init();
}

    Plugin.prototype.init = function () {
    	var me = this;
    	var element = me.element,
	    	options = me.options,
            default_color = options.default_color,
			container = me.container;
	    var	pos, h, s, l, clrTimer, lightTimer, colorX, colorY, lightY,
        	divClrPanel = container.find('.color_panel'),
            spCursor = container.find('.color_panel span'),
        	divLightPanel = container.find('.lightness_panel'),
            spLights = divLightPanel.find('span'),
            stLightCursor = container.find('.lightness_panel strong'),
        	iptColorVal = container.find('.color_value input'),
            spColorDemo = container.find('.color_value span'),
            btnOk = container.find('.color_value button');

		container.find('.wind_color_general').show();
		//事件触发时显示取色器

        //ie6下用iframe遮盖
        var _self = this;
        if($.browser.msie && $.browser.version < 7 ) {
            Wind.use('bgiframe',function() {
                _self.container.bgiframe();
            });
        }

        var positionElement = function() {
            var position = 'absolute',
                zIndex = options.zIndex,
                follow_elem_offset = element.offset(),
                follow_elem_height = element.outerHeight(),
                //如果是跟随某元素显示，那么计算元素的位置，并不能超过显示窗口的区域
                top = follow_elem_offset.top + follow_elem_height;
                left = follow_elem_offset.left;
            container.css( {position:position, zIndex:zIndex, left:left + 'px', top:top + 'px'} );
        }

		element.on(options.event,function() {
			positionElement();
            container.show();
		});

		//add Event
		container.find('.wind_color_general').on('click','strong',function(e) {
			var color = uniform($(this).css('backgroundColor'));
			if(options.callback && $.isFunction(options.callback)) {
				options.callback.call(element[0],color);
			}
			me.hide();
		});

		//色块鼠标指示
		container.find('.wind_color_general').on('mouseenter','strong',function(e) {
			$(this).addClass('selector');
		}).on('mouseleave','strong',function(){
			$(this).removeClass('selector');
		});

		//点击切换高级面板
		container.find('.J_set_general').on('click',function(e) {
            e.preventDefault();
			$('.wind_color_general').hide();
			$('.wind_color_senior').show();
		});

		//点击切换到普通模式
		container.find('.J_set_senior').on('click',function(e) {
            e.preventDefault();
			$('.wind_color_senior').hide();
			$('.wind_color_general').show();
		});

        container.find('.J_default_color').on('click',function(e) {
            e.preventDefault();
            $.isFunction(me.options.callback) && me.options.callback.call(element[0],me.options.default_color);
            me.hide();
        });
		//色板鼠标选择事件
		divClrPanel.on('mousedown',function (evt) {
            pos = divClrPanel.offset();
            pickColor(evt);
            capture(this);
            var el = this.setCapture ? divClrPanel : document;
            $(el).on('mousemove',pickColor).on('mouseup',function (evt) {
                release(this);
                $(el).unbind('mousemove').unbind('mouseup');
            });

            function pickColor(evt) {
                var x = evt.pageX - pos.left,
                    y = evt.pageY - pos.top;
                if (x != colorX || y != colorY) {
                    colorX = x, colorY = y;
                    x = Math.max(Math.min(x, 142), 0), y = Math.max(Math.min(y, 123), 0);
                    spCursor.css({
                        left: x - 4,
                        top: y - 4
                    });
                    h = x / 142, s = 1 - y / 123;
                    clearTimeout(clrTimer);
                    clrTimer = setTimeout(function () {
                        me.refreshSeniorPanel(hslToRgb(h, s, l = 0.5), 6);
                        $.isFunction(options.callback) && options.callback.call(element[0],me.color = me.currColor);
                    });
                }
            }
        });

        //下拉饱和度操作
        divLightPanel.on('mousedown',function (e) {
        	e.preventDefault();
            pos = divLightPanel.offset();
            pickLight(e);
            capture(this);
            var el = this.setCapture ? this: document;
            $(el).on('mousemove',pickLight).on('mouseup',function (evt) {
                release(this);
                $(el).unbind('mousemove').unbind('mouseup');
            });

            function pickLight(evt) {
                var y = evt.pageY - pos.top;
                if (y != colorY) {
                    colorY = y;
                    y = Math.max(Math.min(y, 123), 0);
                    stLightCursor.css({
                        top: y - 3
                    });
                    l = 1 - y / 123;
                    clearTimeout(lightTimer);
                    lightTimer = setTimeout(function () {
                        me.refreshSeniorPanel(hslToRgb(h, s, l), 4);
                        $.isFunction(me.options.callback) && me.options.callback.call(element[0],me.color = me.currColor);
                    });
                }
            }
        });

        //点击OK按钮的事件
        btnOk.on('click',function () {
            me.color = me.currColor;
            $.isFunction(me.options.callback) && me.options.callback.call(element[0],me.color);
            me.hide();
        });

        //点击其它地方隐藏选色器
        $(document.body).on('mousedown',function(e) {
        	if( !$.contains(container[0],e.target) ) {
        		me.hide();
        	}
        });

        //文本框监听
        iptColorVal.on('keyup',function (evt) {
            var code = evt.keyCode || evt.charcode,
                color = this.value;
            if (color.length == 6 || (color.length == 3 && code == 13)) {
                if (me.refreshSeniorPanel(me.currColor = '#' + color.toLowerCase(), 3)) {
                    $.isFunction(me.onColorChange) && me.onColorChange(me.color = me.currColor);
                } else {
                    alert('您输入的颜色值不正确。');
                }
            }
        });

        this.refreshSeniorPanel = function (color, except) {
            except = except == undefined ? 7 : except;
            if (rColor.test(color)) {
                me.currColor = color;
                var arHsl = rgbToHsl(color),
                    colors;
                h = arHsl[0], s = arHsl[1], l = arHsl[2];
                if (except & 1) {
                    spCursor.css({left:h * 142 - 4, top:(1 - s) * 123 - 4});
                }
                if (except & 2) {
                    colors = getLightPanelHtml(color);
                    divLightPanel.hide();
                    spLights.each(function (i, el) {
                        this.style.backgroundColor = colors[i];
                    });
                    stLightCursor.css({
                        top: 123 * (1 - l) - 3
                    });
                    divLightPanel.show();
                }
                if (except & 4) {
                    iptColorVal.val(color.replace(rSharp, ''));
                }
                spColorDemo.css({
                    'backgroundColor': color
                });
                return color;
            }
            return null;
        };
        this.currColor = this.color = '#cccccc';
        this.refreshSeniorPanel(this.color);

        //当前窗口内捕获鼠标操作
        function capture(elem) {
	        elem.setCapture ? elem.setCapture() : window.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP);
	    }

	    function release(elem) {
	        elem.releaseCapture ? elem.releaseCapture() : window.releaseEvents(Event.MOUSEMOVE | Event.MOUSEUP);
	    }

	    //右边色差饱和度HTML计算
	    function getLightPanelHtml(color) {
	        var ar = rgbToHsl(color),
	            h = ar[0],
	            s = ar[1],
	            num, i = num = 62;
	        ar.length = 0;
	        while (i) {
	            ar.push(hslToRgb(h, s, --i / num));
	        }
	        return ar;
	    }

		//转换rgb为数组形式
		function convertHexColor(color) {
            color = String(color || '');
            color.charAt(0) == '#' && (color = color.substring(1));
            color.length == 3 && (color = color.replace(/([0-9a-f])/ig, '$1$1'));
            return color.length == 6 ? [parseInt(color.substr(0, 2), 16), parseInt(color.substr(2, 2), 16), parseInt(color.substr(4, 2), 16)] : [0, 0, 0];
        }

        //转换为HEX码
	    function uniform(color) {
	        if (String(color).slice(0, 3) == 'rgb') {
	            var ar = color.slice(4, -1).split(','),
	                r = parseInt(ar[0]),
	                g = parseInt(ar[1]),
	                b = parseInt(ar[2]);
	            return ['#', r < 16 ? '0' : '', r.toString(16), g < 16 ? '0' : '', g.toString(16), b < 16 ? '0' : '', b.toString(16)].join('');
	        }
	        return color;
	    }

		//hsl转换为rgb
	    function hslToRgb(h, s, l) {
	        var r, g, b;
	        if (s == 0) {
	            r = g = b = l;
	        } else {
	            var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
	            var p = 2 * l - q;
	            r = hue2rgb(p, q, h + 1 / 3);
	            g = hue2rgb(p, q, h);
	            b = hue2rgb(p, q, h - 1 / 3);
	        }
	        return uniform(['rgb(', Math.round(r * 255), ', ', Math.round(g * 255), ', ', Math.round(b * 255), ')'].join(''));
	    }

		//rgb转换为hsl
	    function rgbToHsl(r, g, b) {
	        if (typeof r === 'string') {
	            return arguments.callee.apply(null, convertHexColor(r));
	        }
	        r /= 255, g /= 255, b /= 255;
	        var max = Math.max(r, g, b),
	            min = Math.min(r, g, b),
	            h = 0,
	            s = 0,
	            l = (max + min) / 2,
	            d, sum = max + min;
	        if (d = max - min) {
	            s = l > 0.5 ? d / (2 - sum) : d / sum;
	            h = (max == r ? ((g - b) / d + (g < b ? 6 : 0)) : max == g ? ((b - r) / d + 2) : ((r - g) / d + 4)) / 6;
	        }
	        return [h, s, l];
	    }

	    function hue2rgb(p, q, t) {
	        if (t < 0) t += 1;
	        if (t > 1) t -= 1;
	        if (t < 1 / 6) return p + (q - p) * 6 * t;
	        if (t < 1 / 2) return q;
	        if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
	        return p;
	    }
    };

    Plugin.prototype.show = function (color) {
		this.container.show();
    };

    Plugin.prototype.hide = function () {
        this.container.hide()
    };

    $.fn[pluginName] = Wind[pluginName]= function ( options ) {
    	Wind.css('colorPicker');
         return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin( $(this) ,options ));
            }
        });
    }

})( jQuery, window ,document);
