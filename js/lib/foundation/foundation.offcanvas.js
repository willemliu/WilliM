!function(e){Foundation.libs.offcanvas={name:"offcanvas",version:"5.5.1",settings:{open_method:"move",close_on_click:!1},init:function(e,t,a){this.bindings(t,a)},events:function(){var t=this,a=t.S,s="",n="",o="";"move"===this.settings.open_method?(s="move-",n="right",o="left"):"overlap_single"===this.settings.open_method?(s="offcanvas-overlap-",n="right",o="left"):"overlap"===this.settings.open_method&&(s="offcanvas-overlap"),a(this.scope).off(".offcanvas").on("click.fndtn.offcanvas",".left-off-canvas-toggle",function(o){t.click_toggle_class(o,s+n),"overlap"!==t.settings.open_method&&a(".left-submenu").removeClass(s+n),e(".left-off-canvas-toggle").attr("aria-expanded","true")}).on("click.fndtn.offcanvas",".left-off-canvas-menu a",function(o){var i=t.get_settings(o),r=a(this).parent();!i.close_on_click||r.hasClass("has-submenu")||r.hasClass("back")?a(this).parent().hasClass("has-submenu")?(o.preventDefault(),a(this).siblings(".left-submenu").toggleClass(s+n)):r.hasClass("back")&&(o.preventDefault(),r.parent().removeClass(s+n)):(t.hide.call(t,s+n,t.get_wrapper(o)),r.parent().removeClass(s+n)),e(".left-off-canvas-toggle").attr("aria-expanded","true")}).on("click.fndtn.offcanvas",".right-off-canvas-toggle",function(n){t.click_toggle_class(n,s+o),"overlap"!==t.settings.open_method&&a(".right-submenu").removeClass(s+o),e(".right-off-canvas-toggle").attr("aria-expanded","true")}).on("click.fndtn.offcanvas",".right-off-canvas-menu a",function(n){var i=t.get_settings(n),r=a(this).parent();!i.close_on_click||r.hasClass("has-submenu")||r.hasClass("back")?a(this).parent().hasClass("has-submenu")?(n.preventDefault(),a(this).siblings(".right-submenu").toggleClass(s+o)):r.hasClass("back")&&(n.preventDefault(),r.parent().removeClass(s+o)):(t.hide.call(t,s+o,t.get_wrapper(n)),r.parent().removeClass(s+o)),e(".right-off-canvas-toggle").attr("aria-expanded","true")}).on("click.fndtn.offcanvas",".exit-off-canvas",function(i){t.click_remove_class(i,s+o),a(".right-submenu").removeClass(s+o),n&&(t.click_remove_class(i,s+n),a(".left-submenu").removeClass(s+o)),e(".right-off-canvas-toggle").attr("aria-expanded","true")}).on("click.fndtn.offcanvas",".exit-off-canvas",function(a){t.click_remove_class(a,s+o),e(".left-off-canvas-toggle").attr("aria-expanded","false"),n&&(t.click_remove_class(a,s+n),e(".right-off-canvas-toggle").attr("aria-expanded","false"))})},toggle:function(e,t){t=t||this.get_wrapper(),t.is("."+e)?this.hide(e,t):this.show(e,t)},show:function(e,t){t=t||this.get_wrapper(),t.trigger("open").trigger("open.fndtn.offcanvas"),t.addClass(e)},hide:function(e,t){t=t||this.get_wrapper(),t.trigger("close").trigger("close.fndtn.offcanvas"),t.removeClass(e)},click_toggle_class:function(e,t){e.preventDefault();var a=this.get_wrapper(e);this.toggle(t,a)},click_remove_class:function(e,t){e.preventDefault();var a=this.get_wrapper(e);this.hide(t,a)},get_settings:function(e){var t=this.S(e.target).closest("["+this.attr_name()+"]");return t.data(this.attr_name(!0)+"-init")||this.settings},get_wrapper:function(e){var t=this.S(e?e.target:this.scope).closest(".off-canvas-wrap");return 0===t.length&&(t=this.S(".off-canvas-wrap")),t},reflow:function(){}}}(jQuery,window,window.document);