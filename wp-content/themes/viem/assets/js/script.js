/* When the window scrolls, check if module is visible */
;(function($) {
  'use strict';
  /**
   * Copyright 2012, Digital Fusion
   * Licensed under the MIT license.
   * http://teamdf.com/jquery-plugins/license/
   *
   * @author Sam Sehnert
   * @desc A small plugin that checks whether elements are within
   *     the user visible viewport of a web browser.
   *     only accounts for vertical position, not horizontal.
   */

  $.fn.visible = function(partial) {
    
      var $t            = $(this),
          $w            = $(window),
          viewTop       = $w.scrollTop(),
          viewBottom    = viewTop + $w.height(),
          _top          = $t.offset().top,
          _bottom       = _top + $t.height(),
          compareTop    = partial === true ? _bottom : _top,
          compareBottom = partial === true ? _top : _bottom;
    
    return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

  };
    
})(jQuery);

;(function($){
  'use strict';
  var body = $( 'body' ),
    $win = $( window );
  
  var dtLoadmore = function(element, options,callback){
     this.$element    = $(element);
     this.callback = callback;
     this.options = $.extend({},dtLoadmore.defaults, options);
     this.contentSelector = this.options.contentSelector || this.$element.find('.loadmore-wrap');
     this.options.contentSelector = this.contentSelector;
     this.init();
  }
  dtLoadmore.defaults = {
      contentSelector: null,
      nextSelector: "div.navigation a:first",
      navSelector: "div.navigation",
      itemSelector: "div.post",
      dataType: 'html',
      finishedMsg: "<em>Congratulations, you've reached the end of the internet.</em>",
      maxPage: undefined,
      loading:{
        speed:0,
        start: undefined
      },
      state: {
            isDuringAjax: false,
            isInvalidPage: false,
            isDestroyed: false,
            isDone: false, // For when it goes all the way through the archive.
          isPaused: false,
          isBeyondMaxPage: false,
          currPage: 1
      }
  };
  dtLoadmore.prototype.init = function(){
    this.create();
  }
  dtLoadmore.prototype.create = function(){
   var self       = this, 
    $this       = this.$element,
    contentSelector = this.contentSelector,
    action      = this.action,
    btn       = this.btn,
    loading     = this.loading,
    options     = this.options;
    
    var _determinepath = function(path){
      if (path.match(/^(.*?)\b2\b(.*?$)/)) {
               path = path.match(/^(.*?)\b2\b(.*?$)/).slice(1);
           } else if (path.match(/^(.*?)2(.*?$)/)) {
               if (path.match(/^(.*?page=)2(\/.*|$)/)) {
                   path = path.match(/^(.*?page=)2(\/.*|$)/).slice(1);
                   return path;
               }
               path = path.match(/^(.*?)2(.*?$)/).slice(1);

           } else {
               if (path.match(/^(.*?page=)1(\/.*|$)/)) {
                   path = path.match(/^(.*?page=)1(\/.*|$)/).slice(1);
                   return path;
               } else {
                options.state.isInvalidPage = true;
               }
           }
      return path;
    }
    if(!$(options.nextSelector).length){
      return;
    }
    
    // callback loading
    options.callback = function(data, url) {
           if (self.callback) {
            self.callback.call($(options.contentSelector)[0], data, options, url);
           }
       };
       
       options.loading.start = function($btn) {
        if(options.state.isBeyondMaxPage)
          return;
          $btn.hide();
               $(options.navSelector).hide();
               $btn.closest('.loadmore-action').find('.loadmore-loading').show(options.loading.speed, $.proxy(function() {
                loadAjax(options,$btn);
               }, self));
        };
    
    var loadAjax = function(options,$btn){
      var path = $(options.nextSelector).attr('href');
        path = _determinepath(path);
      
      var callback=options.callback,
        desturl,frag,box,children,data;
      
      options.state.currPage++;
      options.maxPage = $(options.contentSelector).data('maxpage') || options.maxPage;
      // Manually control maximum page
           if ( options.maxPage !== undefined && options.state.currPage > options.maxPage ){
            options.state.isBeyondMaxPage = true;
               return;
           }
           desturl = path.join(options.state.currPage);
           box = $('<div/>');
           box.load(desturl + ' ' + options.itemSelector,undefined,function(responseText){
            children = box.children();
            if (children.length === 0) {
              $btn.closest('.loadmore-action').find('.loadmore-loading').hide(options.loading.speed,function(){
                options.state.isBeyondMaxPage = true;
                $btn.html(options.finishedMsg).show();
              });
                   return ;
               }
            frag = document.createDocumentFragment();
               while (box[0].firstChild) {
                   frag.appendChild(box[0].firstChild);
               }
               $(options.contentSelector)[0].appendChild(frag);
               data = children.get();
               $btn.closest('.loadmore-action').find('.loadmore-loading').hide();
               if(options.maxPage !== undefined && options.maxPage == options.state.currPage ){
                options.state.isBeyondMaxPage = true;
                $btn.html(options.finishedMsg);
               }
               $btn.show(options.loading.speed);
               options.callback(data);
              
           });
    }
    
    $(document).on('click','[data-paginate="loadmore"] .btn-loadmore',function(e){
       e.stopPropagation();
       e.preventDefault();
       options.loading.start.call($(options.contentSelector)[0],$(this));
    });
  }
  
  
  dtLoadmore.prototype.update = function(key){
    if ($.isPlainObject(key)) {
           this.options = $.extend(true,this.options,key);
       }
  }
  $.fn.dtLoadmore = function(options,callback){
    var thisCall = typeof options;
    switch (thisCall) {
           // method
           case 'string':
               var args = Array.prototype.slice.call(arguments, 1);
               this.each(function () {
                   var instance = $.data(this, 'dtloadmore');
                   if (!instance) {
                       return false;
                   }
                   if (!$.isFunction(instance[options]) || options.charAt(0) === '_') {
                       return false;
                   }
                   instance[options].apply(instance, args);
               });
  
           break;
  
           case 'object':
               this.each(function () {
                 var instance = $.data(this, 'dtloadmore');
                 if (instance) {
                     instance.update(options);
                 } else {
                     instance = new dtLoadmore(this, options, callback);
                     $.data(this, 'dtloadmore', instance);
                 }
             });
  
           break;
  
       }

    return this;
  };
}(window.jQuery));
(function($) {
  'use strict';
  $.fn.dt_mediaelementplayer = function(options){
    var defaults = {};
        options = $.extend(defaults, options);
        return this.each(function() {
            var el = $(this);
            el.attr('width', '100%').attr('height', '100%');
            $(el).closest('.video-embed-wrap').each(function() {
                var aspectRatio = $(this).height() / $(this).width();
                $(this).attr('data-aspectRatio', aspectRatio).css({
                    'height': $(this).width() * aspectRatio + 'px',
                    'width': '100%'
                });
            });
            el.mediaelementplayer({
                mode: 'auto',
                defaultVideoWidth: '100%',
                defaultVideoHeight: '100%',
                videoWidth: '100%',
                videoHeight: '100%',
                audioWidth: "100%",
                audioHeight: 30,
                startVolume: 0.8,
                loop: false,
                enableAutosize: true,
                features: ['playpause', 'progress', 'duration', 'volume', 'fullscreen'],
                alwaysShowControls: false,
                iPadUseNativeControls: false,
                iPhoneUseNativeControls: false,
                AndroidUseNativeControls: false,
                alwaysShowHours: false,
                showTimecodeFrameCount: false,
                framesPerSecond: 25,
                enableKeyboard: true,
                pauseOtherPlayers: true,
                keyActions: [],
            });
            window.setTimeout(function() {
                $(el).closest('.video-embed-wrap').css({
                    'height': '100%',
                    'width': '100%'
                });
            }, 1000);
            $(el).closest('.mejs-container').css({
                'height': '100%',
                'width': '100%'
            });
        });
  };
}(window.jQuery));
(function() {
    'use strict';
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] || window[vendors[x] + 'CancelRequestAnimationFrame'];
    }
    if (!window.requestAnimationFrame) window.requestAnimationFrame = function(callback) {
        var currTime = new Date().getTime();
        var timeToCall = Math.max(0, 16 - (currTime - lastTime));
        var id = window.setTimeout(function() {
            callback(currTime + timeToCall);
        }, timeToCall);
        lastTime = currTime + timeToCall;
        return id;
    };
    if (!window.cancelAnimationFrame) window.cancelAnimationFrame = function(id) {
        clearTimeout(id);
    };
}());
/*!
 * dtfitvids 1.1
 *
 * Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
 * Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
 * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
 *
 */
(function($) {
    'use strict';
    $.fn.dtfitvids = function(options) {
        var settings = {
            customSelector: null,
            ignore: null
        };
        if (!document.getElementById('fit-vids-style')) {
            // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
            var head = document.head || document.getElementsByTagName('head')[0];
            var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
            var div = document.createElement("div");
            div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
            head.appendChild(div.childNodes[1]);
        }
        if (options) {
            $.extend(settings, options);
        }
        return this.each(function() {
            var selectors = ['iframe[src*="player.vimeo.com"]', 'iframe[src*="youtube.com"]', 'iframe[src*="youtube-nocookie.com"]', 'iframe[src*="kickstarter.com"][src*="video.html"]', 'object', 'embed'];
            if (settings.customSelector) {
                selectors.push(settings.customSelector);
            }
            var ignoreList = '.fitvidsignore';
            if (settings.ignore) {
                ignoreList = ignoreList + ', ' + settings.ignore;
            }
            var $allVideos = $(this).find(selectors.join(','));
            $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
            $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.
            $allVideos.each(function() {
                var $this = $(this);
                if (!$this.hasClass('dtfitvids-init')) {
                    if ($this.parents(ignoreList).length > 0) {
                        return; // Disable FitVids on this video.
                    }
                    if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) {
                        return;
                    }
                    if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width')))) {
                        $this.attr('height', 9);
                        $this.attr('width', 16);
                    }
                    var height = (this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10)))) ? parseInt($this.attr('height'), 10) : $this.height(),
                        width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
                        aspectRatio = 0.5656;// height / width;
                    //          if(!$this.attr('id')){
                    //            var videoID = 'fitvid' + count;
                    //            $this.attr('id', videoID);
                    //          }
                    $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100) + '%');
                    var dtfitvids_element = $this.closest('.dtfitvids');
                    if (dtfitvids_element.length && !dtfitvids_element.hasClass('dtfitvidsed')) {
                        setTimeout(function() {
                            dtfitvids_element.data('width', width).data('height', height);
                            //dtfitvids_element.css({'width':dtfitvids_element.width(),'height':dtfitvids_element.height()});
                            dtfitvids_element.addClass('dtfitvidsed');
                        }, 200);
                    }
                    $this.removeAttr('height').removeAttr('width');
                    $this.addClass('dtfitvids-init');
                }
            });
        });
    };
    // Works with either jQuery or Zepto
})(window.jQuery || window.Zepto);
(function($){
  var DawnThemes = window.DawnThemes || {};
  'use strict';
  var body = $( 'body' ),
    $win = $( window );
  DawnThemes.getNavbarFixedHeight = function() {
        return parseInt(DawnThemesL10n.navbarFixedHeight);
    };
    DawnThemes.userAgent = {
        isIE7: false,
        isIE8: false,
        isLowIE: false,
        isAndroid: false,
        isTouch: false,
        isIOS: false,
        init: function() {
            var appVersion = navigator.appVersion,
                userAgent = navigator.userAgent;
            DawnThemes.userAgent.isIE7 = appVersion.indexOf("MSIE 7.") !== -1;
            DawnThemes.userAgent.isIE8 = appVersion.indexOf("MSIE 8.") !== -1;
            DawnThemes.userAgent.isLowIE = DawnThemes.userAgent.isIE7 || DawnThemes.userAgent.isIE8;
            DawnThemes.userAgent.isAndroid = (/android/gi).test(appVersion);
            DawnThemes.userAgent.isIOS = (/iphone|ipad|ipod/gi).test(appVersion);
            DawnThemes.userAgent.isTouch = !!('ontouchstart' in window) || (!!('onmsgesturechange' in window) && !!window.navigator.maxTouchPoints);
            if (/(iPad|iPhone|iPod)/g.test(userAgent)) {
                $(document.documentElement).addClass('dt-os-ios');
            } else if (userAgent.toLowerCase().indexOf("android") > -1) {
                $(document.documentElement).addClass('dt-os-android');
            } else if (-1 !== userAgent.indexOf('Mac OS X')) {
                $(document.documentElement).addClass('dt-os-mac');
            } else if (-1 !== appVersion.indexOf("Win")) {
                $(document.documentElement).addClass('dt-os-win');
            }
            if (/chrom(e|ium)/.test(userAgent.toLowerCase())) {
                $(document.documentElement).addClass('dt-browser-chrome');
            } else if (-1 !== userAgent.indexOf('Firefox')) {
                $(document.documentElement).addClass('dt-browser-firefox');
            } else if (-1 !== userAgent.indexOf('Safari') && -1 === userAgent.indexOf('Chrome')) {
                $(document.documentElement).addClass('dt-browser-safari');
            } else if (userAgent.indexOf('MSIE') !== -1 || userAgent.indexOf('Trident/') > 0) {
                $(document.documentElement).addClass('dt-browser-ie');
            }
        }
    };
  DawnThemes.Event = {
        scrollTop: 0,
        runScrollEvent: false,
        runResizeEvent: false,
        windowpageYOffset: window.pageYOffset,
        windowHeight: window.innerHeight,
        windowWidth: window.innerWidth,
        init: function(){

          // remove DT preload
          $('.dt-post-category, .dt-posts-slider').removeClass('viem-preload');

          // Smart Content Box shortcode
          if( $('.dt-smart-content-box').length ){
            var $window = $( window );
            var $windowW = $window.width();

            var DTSmartContentBox = function($windowW, isResize){
              if($windowW <= 1200){
                var $blockBig = $('.dt-smart-content-box .smart-content-box__wrap .dt-smcb-block1 .dt-module-thumb');
                var $blockBigW = $blockBig.width();
                var $blockBigH = Math.round($blockBigW * 0.8052);
                $('.dt-smart-content-box .smart-content-box__wrap .dt-smcb-block1 .dt-module-thumb').css("height", $blockBigH);

                var $blockList = $('.dt-smart-content-box .smart-content-box__wrap .dt-smcb-block2 .dt-module-thumb');
                var $blockListW = $blockList.width();
                var $blockListH = Math.round($blockListW * 0.568);
                $('.dt-smart-content-box .smart-content-box__wrap .dt-smcb-block2 .dt-module-thumb, .dt-smart-content-box .smart-content-box__wrap .dt-smcb-block3 .dt-module-thumb').css("height", $blockListH);
              }else{
                $('.dt-smart-content-box .smart-content-box__wrap .dt-smcb-block1 .dt-module-thumb').attr("style", '');
                $('.dt-smart-content-box .smart-content-box__wrap .dt-smcb-block2 .dt-module-thumb, .dt-smart-content-box .smart-content-box__wrap .dt-smcb-block3 .dt-module-thumb').attr("style", '');
              }
            }
            DTSmartContentBox($windowW, true );
            $window.resize(function(){
              $windowW = $window.width();
              DTSmartContentBox($windowW, true);
            });
          }
          DawnThemes.menu();
          DawnThemes.menu_toggle();
          DawnThemes.scrollToTOp();
          DawnThemes.toggle_social();
          
          //Responsive embed iframe
          DawnThemes.responsiveEmbedIframe();

          $(window).resize(function(){
            DawnThemes.menu();
            DawnThemes.responsiveEmbedIframe();
          });
          
          $(window).resize(function(){
            $('[data-layout="masonry"]').each(function(){
              var $this = $(this),
                container = $this.find('.masonry-wrap');
                container.isotope( 'layout' );
            });
          });

          // Ajax Tab Loadmore
          DawnThemes.tab_loadmore();
          
          // ajax load next-prev content
          DawnThemes.ajax_nav_content();

          DawnThemes.userAgent.init();
          DawnThemes.SmartSidebar.init();
          
          $(window).on('load', function() {
            // Preload
            DawnThemes.preloading();

            DawnThemes.helperInit();
            
             if ($('.smartsidebar').length) DawnThemes.SmartSidebar.addItem({
                     content: $('.main-content'),
                     sidebar: $('.smartsidebar')
                 });
             if ($('.vc-as-smartsidebar').length) {
                     $('.vc-as-smartsidebar').each(function() {
                         var _this = $(this),
                             sidebar = _this.children('.wpb_wrapper'),
                             content = _this.closest('.vc_row').children('.vc_col-sm-8, .vc_col-sm-9').find('.wpb_wrapper');
                         DawnThemes.SmartSidebar.addItem({
                             content: content,
                             sidebar: sidebar
                         });
                     });
                 }
            
            DawnThemes.SmartSidebar.onResize();
            
            $(document).trigger('dawnthemes_on_load');
            
          });
        	$().ready(function() {
            //DawnThemes.AutoNextVideo.init();
        		DawnThemes.VideoPlayLists.init();
        	});
	      	$(window).on('scroll', function() {
	            DawnThemes.Event.scrollTop = $(window).scrollTop();
	            DawnThemes.Event.runScrollEvent = true;
	            DawnThemes.Event.windowpageYOffset = window.pageYOffset;
	            DawnThemes.SmartSidebar.onScroll(DawnThemes.Event.scrollTop);
	        });
	        $(window).on('resize', function() {
	          DawnThemes.Event.runResizeEvent = true;
	          DawnThemes.Event.windowHeight = window.innerHeight;
	          DawnThemes.Event.windowWidth = window.innerWidth;
	          $(document).trigger('dawnthemes_on_resize');
	        });
            setInterval(function() {
                if (DawnThemes.Event.runScrollEvent) {
                  DawnThemes.Event.runScrollEvent = false;
                }
                if (DawnThemes.Event.runResizeEvent) {
                  DawnThemes.Event.runResizeEvent = false;
                  DawnThemes.SmartSidebar.onResize();
                }
            }, 100);

        }
  };

  DawnThemes.preloading = function(){
    if( $('#dawnthems-preload').length){
        $("#dawnthems-preload").fadeOut(1500);
    }
  }

  DawnThemes.menu_toggle = function(){
    //Off Canvas menu
    $('.menu-toggle').on('click',function(e){
      e.preventDefault();
      if($('body').hasClass('open-offcanvas')){
        $('body').removeClass('open-offcanvas').addClass('close-offcanvas');
        $('html').removeClass('dt-html-overflow-y');
      }else{
        $('body').removeClass('close-offcanvas').addClass('open-offcanvas');
        $('html').addClass('dt-html-overflow-y');
      }
    });
    
    // mobile-menu-toggle
    $('.offcanvas .mobile-menu-toggle').on('click',function(e){
      e.preventDefault();
      $('html').removeClass('dt-html-overflow-y');
      $('body').removeClass('open-offcanvas');
    });
    
    //Open Search Box
    $('.sidebar-offcanvas-header .search-toggle').on('click', function(){
      $('.sidebar-offcanvas-header .user-panel').hide();
      $('.sidebar-offcanvas-header .toggle-wrap').hide();
      $('.sidebar-offcanvas-header .search-box').fadeIn();
    });

    //Close Search Box
    $(document).on('click', function (e) {
      if (!$(e.target).hasClass("search-toggle") 
            && $(e.target).parents(".sidebar-offcanvas-header").length === 0) 
        {
            $(".search-box").hide();
            $('.sidebar-offcanvas-header .user-panel').fadeIn();
        $('.sidebar-offcanvas-header .toggle-wrap').fadeIn();
        }
    });

    // Header menu layout 1
    $('.header-nav-content .menu-toggle-btn').on('click', function(e){
        e.preventDefault();
        var $this = $(this);
        if( $('#dt-main-menu').hasClass('active') ){
            $('#dt-main-menu').removeClass('active');
        }else{
            $('#dt-main-menu').addClass('active');
        }
        $($this).find('.viem-icon-menu').toggleClass('iclose');
    });
  };
  
  DawnThemes.menu = function(){
  	// Check dropdown menu derection
  	DawnThemes.menu_direction('check');
  	
      // Sticky menu
      var adminBar = $('#wpadminbar'),
          adminBarHeight = (adminBar.length)?adminBar.height():0;

      $win.scroll(function () {
          if( $('body').hasClass('sticky-menu') ){
              var siteHeader = $('header.site-header');
              var scrollTop = parseInt( $(this).scrollTop() );
              var headerTop = parseInt( siteHeader.outerHeight() );
              if( adminBarHeight ){
                scrollTop += adminBarHeight;
              }
              if ( scrollTop >= headerTop ) {
                  if( ! siteHeader.hasClass('sticky') ){
                      siteHeader.addClass('sticky');
                  }
              }else if( scrollTop < headerTop ){
                  if( siteHeader.hasClass('sticky') ){
                      siteHeader.removeClass('sticky');
                  }
              }
          }
      });
  };
  
  DawnThemes.menu_direction = function( check ){
      if( $('.dawnthemes-navigation-wrap').length ){
          $('.dawnthemes-navigation-wrap').each(function(){
                var $this_nav = $(this);
                $(this).find('li').each(function(){
                    if( $(this).hasClass('menu-item-has-children') ){
                        if( check === 'check'){
                            $(this).removeClass('dawnthemes-menu-dir-r');
                            var $this_nav_li_offsetright = ($(window).width() - ($(this).offset().left + $(this).outerWidth()));
                            var $this_nav_li_sub_menu_w = $(this).find(">ul.sub-menu").width();
                            if( $this_nav_li_offsetright <  $this_nav_li_sub_menu_w){
                                $(this).addClass('dawnthemes-menu-dir-r');
                            }
                        }else{
                            $(this).removeClass('dawnthemes-menu-dir-r');
                        }
                    }
                })
          });
      }
  };
  
  DawnThemes.scrollToTOp = function(){
    //Go to top
    $(window).scroll(function () {
      if ($(this).scrollTop() > 500) {
        $('.go-to-top').addClass('on');
      }
      else {
        $('.go-to-top').removeClass('on');
      }
    });
    $('body').on( 'click', '.go-to-top, #scroll-to-top', function () {
      $("html, body").animate({
        scrollTop: 0
      }, 800);
      return false;
    });
  };

  DawnThemes.toggle_social = function(){
      if( $('.viem-social-share-toggle').length ){
            $('.viem-social-share-toggle').each(function(){
                  var $this = $(this);
                  $(this).find('.toggle_social').on('click', function(){
                      if( $(this).hasClass('active') ){
                          $(this).next().removeClass('active');
                          $(this).removeClass('active');
                      }else{
                        $(this).addClass('active');
                        $(this).next().addClass('active');
                      }
                  });
            });
        }
  };

  DawnThemes.slickSlider = function(){

      if( $('.viem-slick-slider').length && $().slick ){
      		$('.viem-slick-slider').each(function(){
      			var $this = $(this);
      			var $this_id, $mode, $infinite, $autoplay, $slidesToShow, $slidesToScroll, $dots, $fade;

            $this_id = $(this).attr('id');
            $mode = $(this).attr('data-mode');
      			$dots = $(this).attr('data-dots');
            $fade = $(this).attr('data-fade');
      			$infinite = $(this).attr('data-infinite');
            $autoplay = $(this).attr('data-autoplay');
      			$slidesToShow = parseInt($(this).attr('data-visible'));
      			$slidesToScroll = parseInt($(this).attr('data-scroll'));

      			$($this).removeClass('viem-preload');

      			$dots = ($dots == '1' || $dots == 'true') ? true : false;
      			$infinite = ($infinite == '1' || $infinite == 'true') ? true : false;
            $autoplay = ($autoplay == '1' || $autoplay == 'true') ? true : false;
            $fade = ($fade == '1' || $fade == 'true') ? true : false;
            
            switch($mode){
              case 'syncing':

                  $('#' + $this_id + ' .slider-for').slick({
                  slidesToShow: 1,
                  slidesToScroll: 1,
                  arrows: false,
                  fade: $fade,
                  asNavFor: '#' + $this_id + ' .slider-nav'
                });

                $('#' + $this_id + ' .slider-nav').slick({
                  slidesToShow: $slidesToShow,
                  slidesToScroll: $slidesToScroll,
                  autoplay: $autoplay,
                  autoplaySpeed: 5000,
                  asNavFor: '#'+ $this_id + ' .slider-for',
                  nextArrow: '<div class="next-slider"></div>',
                  prevArrow: '<div class="pre-slider"></div>',
                  dots: false,
                  centerMode: false,
                  focusOnSelect: true,
                  responsive: [
                         {
                           breakpoint: 1024,
                           settings: {
                             slidesToShow: $slidesToShow,
                             slidesToScroll: $slidesToScroll,
                           }
                         },
                         {
                           breakpoint: 600,
                           settings: {
                             slidesToShow: $slidesToShow - 1,
                             slidesToScroll: $slidesToScroll,
                           }
                         },
                         {
                           breakpoint: 480,
                           settings: {
                             slidesToShow: 2,
                             slidesToScroll: 1,
                           }
                         },
                         {
                           breakpoint: 320,
                           settings: {
                             slidesToShow: 1,
                             slidesToScroll: 1,
                           }
                         }
                         // You can unslick at a given breakpoint now by adding:
                         // settings: "unslick"
                         // instead of a settings object
                       ]
                });

                break;
              default:
                  $(this).slick({
                      dots: $dots,
                      infinite: $infinite,
                      fade: $fade,
                      slidesToShow: $slidesToShow,
                      slidesToScroll: $slidesToScroll,
                      nextArrow: '<div class="next-slider"></div>',
                      prevArrow: '<div class="pre-slider"></div>',
                      responsive: [
                         {
                           breakpoint: 1024,
                           settings: {
                             slidesToShow: 3,
                             slidesToScroll: 3,
                           }
                         },
                         {
                           breakpoint: 600,
                           settings: {
                             slidesToShow: 2,
                             slidesToScroll: 2
                           }
                         },
                         {
                           breakpoint: 480,
                           settings: {
                             slidesToShow: 1,
                             slidesToScroll: 1
                           }
                         }
                         // You can unslick at a given breakpoint now by adding:
                         // settings: "unslick"
                         // instead of a settings object
                       ]
                  });
              break;
            }

      		});
    	}
  };
  
  DawnThemes.carouselInit = function(){
	  if ($().owlCarousel) {
          $('.viem-carousel-slide.owl-carousel').each(function(){
             	  var _this = $(this);
                $(_this).removeClass('viem-preload');

                var $autoplay = _this.attr('data-autoplay') == 'true' ? true : false;
                var $loop = _this.attr('data-loop') == 'true' ? true : false;
                var $margin    = (typeof _this.attr('data-margin') !== typeof undefined) ? parseInt( _this.attr('data-margin') ) : 0;
                var $animateOut    = (typeof _this.attr('data-animateOut') !== typeof undefined) ? _this.attr('data-animateOut') : '';
                var $autoWidth    = (typeof _this.attr('data-autoWidth') !== typeof undefined && _this.attr('data-autoWidth') == 'true' ) ? true : false;
                var $rtl    = (typeof _this.attr('data-rtl') !== typeof undefined && _this.attr('data-rtl') == '1') ? true : false;

                _this.owlCarousel({
                  items: _this.attr('data-items'),
                  animateOut: 'fadeOut',
                  lazyLoad: true,
                  loop: $loop,
                  margin: $margin,
                  autoWidth: $autoWidth,
                  nav: _this.attr('data-nav') == '1' ? true : false,
                  //navText: [&#x27;next&#x27;,&#x27;prev&#x27;],
                  dots: _this.attr('data-dots') == 1 ? true : false,
                  center: _this.attr('data-center') == 1 ? true : false,
                  autoplay : $autoplay,
                  autoplayTimeout: 5000,
                  autoplayHoverPause: _this.attr('data-stop_on_hover') == 1 ? true : false,
                  responsive : {
                      // breakpoint from 0 up
                      0 : {
                          items : 1,
                      },
                      // breakpoint from 480 up
                      480 : {
                           items: _this.attr('data-items') > 2 ? 2 : 1 ,
                      },
                      // breakpoint from 768 up
                      768 : {
                          items: _this.attr('data-items') > 2 ? _this.attr('data-items') - 2 : 1,
                      },
                      1000 : {
                          items: _this.attr('data-items') > 2 ? _this.attr('data-items') - 1 : 1,
                      },
                      1200 : {
                          items: _this.attr('data-items'),
                      }
                  },
                  rtl: $rtl,
                });
          });
       }
  };
  
  DawnThemes.CountdownInit = function(){
	  if( $('.viem-countdown').length ){
		  $('.viem-countdown').each(function(){
			  var _this = $(this);
			  var $countdown = _this.data('end');
			  var $html = _this.data('html');
			  $(_this).find('.countdown-content').countdown($countdown, function(event) {
					$(this).html(event.strftime($html));
			  });
		  });
	  }
  };

  DawnThemes.magnificpopupInit = function(){
    if($().magnificPopup){
      $("a[data-rel='magnific-popup-video']").each(function(){
        $(this).magnificPopup({
          type: 'inline',
          mainClass: 'viem-mfp-popup',
          fixedContentPos: true,
          callbacks : {
              open : function(){
                $(this.content).find(".video-embed.video-embed-popup,.audio-embed.audio-embed-popup").dt_mediaelementplayer();
                $(this.content).find('iframe:visible').each(function(){
                if(typeof $(this).attr('src') != 'undefined'){
                  if( $(this).attr('src').toLowerCase().indexOf("youtube") >= 0 || $(this).attr('src').toLowerCase().indexOf("vimeo") >= 0  || $(this).attr('src').toLowerCase().indexOf("twitch.tv") >= 0 || $(this).attr('src').toLowerCase().indexOf("kickstarter") >= 0 || $(this).attr('src').toLowerCase().indexOf("dailymotion") >= 0) {
                    $(this).attr('data-aspectRatio', this.height / this.width).removeAttr('height').removeAttr('width');
                    if($(this).attr('src').indexOf('wmode=transparent') == -1) {
                      if($(this).attr('src').indexOf('?') == -1){
                        $(this).attr('src',$(this).attr('src') + '?wmode=transparent');
                      } else {
                        $(this).attr('src',$(this).attr('src') + '&wmode=transparent');
                      }
                    }
                  }
                } 
              });
                $(this.content).find('iframe[data-aspectRatio]').each(function() {
                var newWidth = $(this).parent().width();
                var $this = $(this);
                $this.width(newWidth).height(newWidth * $this.attr('data-aspectRatio'));
                
               });
              },
              close: function() {
                $(this.st.el).closest('.video-embed-shortcode').find('.video-embed-shortcode').html($(this.st.el).data('video-inline'));
              }
          }
        });
      });
      $("a[data-rel='magnific-popup']").magnificPopup({
        type: 'image',
        mainClass: 'viem-mfp-popup',
        fixedContentPos: true,
        gallery:{
          enabled: true
        }
      });
      $("a[data-rel='magnific-popup-verticalfit']").magnificPopup({
        type: 'image',
        mainClass: 'viem-mfp-popup',
        overflowY: 'scroll',
        fixedContentPos: true,
        image: {
          verticalFit: false
        },
        gallery:{
          enabled: true
        }
      });
      $("a[data-rel='magnific-single-popup']").magnificPopup({
        type: 'image',
        mainClass: 'viem-mfp-popup',
        fixedContentPos: true,
        gallery:{
          enabled: false
        }
      });
    }
  };
  DawnThemes.responsiveEmbedIframe = function(){};
  DawnThemes.isotopeInit = function(){
    var self = this;
    $('[data-layout="masonry"]').each(function(){
      var $this = $(this),
        container = $this.find('.masonry-wrap'),
        itemColumn = $this.data('masonry-column'),
        itemWidth,
        container_width = container.width();
        if(DawnThemes.getViewport().width > 992){
          itemWidth = container_width / itemColumn;
        }else if(DawnThemes.getViewport().width <= 992 && DawnThemes.getViewport().width >= 768){
          itemWidth = container_width / 2;
        }else {
          itemWidth = container_width / 1;
        }
        container.isotope({
          layoutMode: 'masonry',
          itemSelector: '.masonry-item',
          transitionDuration : '0.8s',
          getSortData : { 
            title : function (el) { 
              return $(el).data('title');
            }, 
            date : function (el) { 
              return parseInt($(el).data('date'));
            } 
          },
          masonry : {
            gutter : 0
          }
        }).isotope( 'layout' );
        
        imagesLoaded($this,function(){
          container.isotope( 'layout' );
        });
      if(!$this.hasClass('masonry-inited')){
        $this.addClass('masonry-inited');
        var filter = $this.find('.masonry-filter ul a');
        filter.on('click',function(e){
          e.stopPropagation();
          e.preventDefault();
          
          var $this = jQuery(this);
          // don't proceed if already selected
          if ($this.hasClass('selected')) {
            return false;
          }
          
          var filters = $this.closest('ul');
          filters.find('.selected').removeClass('selected');
          $this.addClass('selected');
          $this.closest('.masonry-filter').find('.filter-heaeding h3').text($this.text());
          var options = {
            layoutMode : 'masonry',
            transitionDuration : '0.8s',
            getSortData : { 
              title : function (el) { 
                return $(el).data('title');
              }, 
              date : function (el) { 
                return parseInt($(el).data('date'));
              } 
            }
          }, 
          key = filters.attr('data-filter-key'), 
          value = $this.attr('data-filter-value');
    
          value = value === 'false' ? false : value;
          options[key] = value;
          container.isotope(options);
          var wrap = $this.closest('[data-layout="masonry"]');
        });
        $('[data-masonry-toogle="selected"]').trigger('click');
      }
    });
    
  },
  DawnThemes.easyZoomInit = function(){
    if($().easyZoom) {
      $('.easyzoom').easyZoom();
    }
  },
  DawnThemes.mediaelementplayerInit = function(){
    if($().mediaelementplayer) {
      $(".video-embed:not(.video-embed-popup),.audio-embed:not(.audio-embed-popup)").dt_mediaelementplayer();
    }
  };
  DawnThemes.AjaxSearch = {
      searching: false,
      lastSearchQuery: "",
      searchTimeout: false,
      init: function() {
          $(document).on('click', '.navbar-search-button', function(e) {
              e.stopPropagation();
              e.preventDefault();
              var $_this = $(this);
              if ($('.search-form-wrap').length) {
                  if ($('.search-form-wrap').hasClass('hide')) {
                      $_this.find('i').removeClass('fa-search').addClass('fa-times');
                      $('.search-form-wrap').removeClass('hide').addClass('show');
                      if (!DawnThemes.userAgent.isTouch) {
                          setTimeout(function() {
                              try {
                                  $('.search-form-wrap .searchinput').focus();
                                  $('.search-form-wrap .searchinput').select();
                              } catch (e) {}
                          }, 200);
                      }
                  }else{
                    $('.search-form-wrap').removeClass('show').addClass('hide');
                    $_this.find('i').removeClass('fa-times').addClass('fa-search');
                  }
              } else if ($('.header-search-overlay').length) {
                  $('.header-search-overlay').stop(true, true).removeClass('hide').css('opacity', 0).animate({
                      'opacity': 1
                  }, 600, 'swing', function() {
                      var _this = $(this);
                      if (!DawnThemes.userAgent.isTouch) {
                          setTimeout(function() {
                              try {
                                  _this.find('.searchinput').focus();
                                  _this.find('.searchinput').select();
                              } catch (e) {}
                          }, 200);
                      }
                  });
              }
          });
          $(document).on('click', '.navbar-searchform-ink .top-searchform-icon', function(e) {
              e.stopPropagation();
              e.preventDefault();
              var _this = $(this).closest('.navbar-searchform-ink');
              _this.addClass('focused');
              if (!DawnThemes.userAgent.isTouch) {
                  setTimeout(function() {
                      try {
                          _this.find('.searchinput').focus();
                          _this.find('.searchinput').select();
                      } catch (e) {}
                  }, 200);
              }
              _this.find('.navbar-searchform-wrap').show();
              _this.find('i').removeClass('top-searchform-icon').addClass('top-searchform-close-icon');
          });
          $(document).on('click', '.navbar-searchform-ink .top-searchform-close-icon', function(e) {
              e.stopPropagation();
              e.preventDefault();
              var _this = $(this).closest('.navbar-searchform-ink');
              _this.removeClass('focused');
              _this.find('.navbar-searchform-wrap').hide();
              _this.find('i').addClass('top-searchform-icon').removeClass('top-searchform-close-icon');
          });
          $('body').on('mousedown', $.proxy(function(e) {
              var element = $(e.target);
              if ($('.navbar-search-inline').length) {
                  if (!element.is('.navbar-search-inline') && element.parents('.navbar-search-inline').length === 0) {
                      $('.navbar-search-inline').removeClass('focused');
                      $('.navbar-search-inline').find('.searchform-result').empty();
                      setTimeout(function() {
                          $('.navbar-search-inline').find('.searchinput').val('');
                          DawnThemes.AjaxSearch.lastSearchQuery = '';
                      }, 200);
                  }
              }
              /*if (DawnThemes.enableAnimation() && $('.search-form-wrap').length) {
                  if (!element.is('.search-form-wrap') && element.parents('.search-form-wrap').length === 0) {
                      $('.search-form-wrap').removeClass('show').addClass('hide');
                      $('.searchform-result').hide();
                  }
              } else if ($('.header-search-overlay').length) {
                  if (!element.is('.header-search-overlay') && element.parents('.header-search-overlay').length === 0) {
                      $('.header-search-overlay').removeClass('show').addClass('hide');
                  }
              }*/
          }, this));
          $('form.search-ajax').on('keyup', '.searchinput', $.proxy(function(e) {
              if (DawnThemes.AjaxSearch.searchTimeout) window.clearTimeout(DawnThemes.AjaxSearch.searchTimeout);
              if (e.currentTarget.value.length >= 1 && DawnThemes.AjaxSearch.lastSearchQuery != $.trim(e.currentTarget.value)) {
                  DawnThemes.AjaxSearch.searchTimeout = window.setTimeout($.proxy(DawnThemes.AjaxSearch.doSearch, this, e), 350);
              }
          }, this));
          $(document).on('click', '.header-search-overlay .close', function() {
              $('.header-search-overlay').stop(true, true).animate({
                  'opacity': 0
              }, 600, 'swing', function() {
                  $(this).addClass('hide');
              });
          });

      },
      doSearch: function(e) {
          var input = e.currentTarget;
          var form = $(input).closest('form'),
              wrapper = form.parent(),
              result = wrapper.find('.searchform-result');
          if (DawnThemes.AjaxSearch.searching && input.value.indexOf(DawnThemes.AjaxSearch.lastSearchQuery) != -1) {
              return;
          }
          DawnThemes.AjaxSearch.lastSearchQuery = input.value;
          var query = form.serialize() + "&action=dt_search_ajax";
          $.ajax({
              url: DawnThemesL10n.ajax_url,
              type: "POST",
              data: query,
              beforeSend: function() {
                  form.addClass('loading');
                  wrapper.addClass('ajax-search-loading');
                  DawnThemes.AjaxSearch.searching = true;
              },
              success: function(response) {
                  if (response === 0) response = "";
                  result.html(response).show();
              },
              complete: function() {
                  form.removeClass('loading');
                  wrapper.removeClass('ajax-search-loading');
                  DawnThemes.AjaxSearch.searching = false;
              }
          });
      }
  };
  DawnThemes.loadmoreInit = function(){
    var self = this;
    $('[data-paginate="loadmore"]').each(function(){
      var $this = $(this);
      $this.dtLoadmore({
        contentSelector : $this.data('contentselector') || null,
        navSelector  : $this.find('div.paginate'),            
            nextSelector : $this.find('div.paginate a.next'),
            itemSelector : $this.data('itemselector'),
            finishedMsg : $this.data('finishedmsg') || DawnThemesL10n.ajax_finishedMsg
      },function(newElements){
        $(newElements).find(".video-embed:not(.video-embed-popup),.audio-embed:not(.audio-embed-popup)").dt_mediaelementplayer();
        
        if($this.hasClass('masonry')){
          $this.find('.masonry-wrap').isotope('appended', $(newElements));
          if($this.find('.masonry-filter').length){
            var selector = $this.find('.masonry-filter').find('a.selected').data('filter-value');
            $this.find('.masonry-wrap').isotope({ filter: selector });
          }
          imagesLoaded(newElements,function(){
            DawnThemes.magnificpopupInit();
            DawnThemes.responsiveEmbedIframe();
            if($this.hasClass('masonry')){
              $this.find('.masonry-wrap').isotope('layout');
            }
          });
        }
        setTimeout(function() {
            DawnThemes.toggle_social();
            DawnThemes.SmartSidebar.onResize();
        }, 500);
      });
    });
  },
  DawnThemes.infiniteScrollInit = function(){
    var self = this;
    //Posts
    $('[data-paginate="infinite_scroll"]').each(function(){
      var $this = $(this);
      var finishedmsg = $this.data('finishedmsg') || DawnThemesL10n.ajax_finishedMsg,
        msgtext = $this.data('msgtext') || DawnThemesL10n.ajax_msgText,
        maxPage = $this.data('contentselector') ? $($this.data('contentselector')).data('maxpage') : undefined;
      $this.find('.infinite-scroll-wrap').infinitescroll({
        navSelector  : $this.find('div.paginate'),            
            nextSelector : $this.find('div.paginate a.next'),    
            itemSelector : $this.data('itemselector'),
            contentSelector : $this.data('contentselector') || $this.find('.infinite-scroll-wrap'),
            msgText: " ",
            maxPage:maxPage,
            loading: {
              speed:0,
              finishedMsg: finishedmsg,
          msgText: $this.data('msgtext') || DawnThemesL10n.ajax_msgText,
          selector: $this.data('loading-selector') || $this,
          msg: $('<div class="infinite-scroll-loading"><div class="fade-loading"><i></i><i></i><i></i><i></i></div><div class="infinite-scroll-loading-msg">' + msgtext +'</div></div>')
        },
        errorCallback: function(){
          $this.find('.infinite-scroll-loading-msg').html(finishedmsg).animate({ opacity: 1 }, 2000, function () {
                    $(this).parent().fadeOut('fast',function(){
                      $this.find('.infinite-scroll-loading-msg').html(msgtext);
                    });
                });
        }
      },function(newElements){
        
        $(newElements).find(".video-embed:not(.video-embed-popup),.audio-embed:not(.audio-embed-popup)").dt_mediaelementplayer();
        
        if($this.hasClass('masonry')){
          $this.find('.masonry-wrap').isotope('appended', $(newElements));
          if($this.find('.masonry-filter').length){
            var selector = $this.find('.masonry-filter').find('a.selected').data('filter-value');
            $this.find('.masonry-wrap').isotope({ filter: selector });
          }
          imagesLoaded(newElements,function(){
            DawnThemes.magnificpopupInit();
            DawnThemes.responsiveEmbedIframe();
            if($this.hasClass('masonry')){
              $this.find('.masonry-wrap').isotope('layout');
            }
          });
        }
      });
    });
    
  };

  DawnThemes.tab_loadmore = function(){
    if(jQuery('.viem_tab_loadmore').length){
      jQuery('.viem_tab_loadmore').each(function() {
            var $wapp_id = jQuery(this).attr('id');

            jQuery(this).find('.viem_sc_content').removeClass('viem-preload');

            // Click load tab
            jQuery(this).find('.viem-sc-nav-tabs li a.tab-intent, .tabdrop li a.tab-intent').on('click', function(e){
              var $this = jQuery(this);
              if( ! jQuery($this).hasClass('tab-loaded') ){
                
                jQuery('#'+$wapp_id+' .viem_sc_content').addClass('viem_sc-tab-loading');

                var wrap_id     = jQuery(this).attr('data-wrap-id'),
                  href          = jQuery(this).attr('href'),
                  display_type  = jQuery(this).attr('data-display_type'),
                  query_types   = jQuery(this).attr('data-query_types'),
                  tab       = jQuery(this).attr('data-tab'),
                  orderby     = jQuery(this).attr('data-orderby'),
                  meta_key     = jQuery(this).attr('data-meta_key'),
                  order     = jQuery(this).attr('data-order'),
                  number_query  = jQuery(this).attr('data-number_query'),
                  columns  = jQuery(this).attr('data-columns'),
                  number_load   = jQuery(this).attr('data-number_load'),
                  number_display  = jQuery(this).attr('data-number_display'),
                  template    = jQuery(this).attr('data-template'),
                  speed       = jQuery(this).attr('data-speed'),
                  dots      = jQuery(this).attr('data-dots'),
                  col       = jQuery(this).attr('data-col'),
                  loadmore_text   = jQuery(this).attr('data-loadmore_text'),
                  loaded_text   = jQuery(this).attr('data-loaded_text'),
                  hover_thumbnail = jQuery(this).attr('data-hover_thumbnail');
                  img_size = jQuery(this).attr('data-img_size');

                  var tab_id = href.substring(1);

                jQuery.ajax({
                    url : viem_dt_ajaxurl,
                    data:{
                      action      : 'viem_tab_loadmore',
                      wrap_id     : wrap_id,
                      display_type  : display_type,
                      query_types   : query_types,
                      tab       : tab,
                      orderby     : orderby,
                      meta_key    : meta_key,
                      order       : order,
                      number_query  : number_query,
                      columns   : columns,
                      number_load   : number_load,
                      number_display  : number_display,
                      template    : template,
                      img_size    : img_size,
                      col       : col,
                      tab_id    : tab_id,
                    },
                    type: 'POST',
                    success: function(data){
                      if(data != ''){
                        var $active = jQuery('#'+$wapp_id+' .viem_sc_content .tab-pane.active');
                          
                        jQuery('#'+$wapp_id+' .viem_sc_content .viem-loadmore-tab-content').prepend(data);

                        jQuery($active).removeClass('active in').addClass('fade');
                        
                        jQuery($this).addClass('tab-loaded');

                        setTimeout(function(){

                          jQuery('#'+$wapp_id+' .viem_sc_content').removeClass('viem_sc-tab-loading');

                        },500);
                        
                      }else{

                      }
                    }
                });
              }
            });
        
      });
    }
  };

  DawnThemes.ajax_nav_content = function(){
    var self = this;
    $('.viem_nav_content').each(function(){
      var $wrap_id = $(this).attr('id');

      $(this).find('.viem-next-prev-wrap a').on('click', function(e){
        e.preventDefault();
        var $_this = $(this);
        
        if( ! $(this).hasClass('ajax-page-disabled') ){
          
          jQuery('#'+$wrap_id+' .viem-sc-content').addClass('viem-loading');
          var $_this_parents = $($_this).parents('.viem-next-prev-wrap');
          var cat       = $_this_parents.attr('data-cat'),
            post_type   = (typeof $_this_parents.attr('data-post_type') !== typeof undefined && $_this_parents.attr('data-post_type') !== false) ? $_this_parents.attr('data-post_type') : 'post',
            taxonomy    = (typeof $_this_parents.attr('data-taxonomy') !== typeof undefined && $_this_parents.attr('data-taxonomy') !== false) ? $_this_parents.attr('data-taxonomy') : '',
            orderby     = $_this_parents.attr('data-orderby'),
            meta_key    = (typeof $_this_parents.attr('data-meta_key') !== typeof undefined && $_this_parents.attr('data-meta_key') !== false) ? $_this_parents.attr('data-meta_key') : '',
            meta_value  = (typeof $_this_parents.attr('data-meta_value') !== typeof undefined && $_this_parents.attr('data-meta_value') !== false) ? $_this_parents.attr('data-meta_value') : '',
            order       = $_this_parents.attr('data-order'),
            offset      = $($_this).attr('data-offset'),
            current_page  = $($_this).attr('data-current-page'),
            columns  = $_this_parents.attr('data-columns'),
            posts_per_page  = $_this_parents.attr('data-posts-per-page'),
            img_size      = $_this_parents.attr('data-img_size'),
            show_excerpt  = (typeof $_this_parents.attr('data-show_excerpt') !== typeof undefined && $_this_parents.attr('data-show_excerpt') !== false) ? $_this_parents.attr('data-show_excerpt') : '',
            style      = $_this_parents.attr('data-style');
            
          $.ajax({
              url : viem_dt_ajaxurl,
              data:{
                action      : 'viem_nav_content',
                post_type   : post_type,
                cat         : cat,
                taxonomy    : taxonomy,
                orderby     : orderby,
                meta_key    : meta_key,
                meta_value  : meta_value,
                order       : order,
                offset      : offset,
                current_page  : current_page,
                columns: columns,
                posts_per_page  : posts_per_page,
                img_size   : img_size,
                show_excerpt: show_excerpt,
                style    : style,
              },
              type: 'POST',
              success: function(data){
                if(data != ''){
                  setTimeout(function(){
                    $('#'+$wrap_id+' .viem-sc-content').removeClass('viem-loading');

                    $('#'+$wrap_id+' .viem-sc-content .viem-sc-list-renderer').html(data).hide();
                    $('#'+$wrap_id+' .viem-sc-content .viem-sc-list-renderer').fadeIn('slow');
                            
                            
                            // uddate current page - offset
                            var current_page  = parseInt( $($_this).attr('data-current-page') );
                            var current_offset  = parseInt( $($_this).attr('data-offset') );

                            if( $($_this).hasClass('viem-ajax-next-page') ){
                              $('#'+$wrap_id+' .viem-next-prev-wrap .viem-ajax-next-page').attr('data-current-page', current_page + 1);
                              var prev_page = parseInt( $($_this).attr('data-current-page') - 1 );
                              $('#'+$wrap_id+' .viem-next-prev-wrap .viem-ajax-prev-page').attr('data-current-page', prev_page);

                              $($_this).attr('data-offset', parseInt(offset) + parseInt(posts_per_page));

                              $('#'+$wrap_id+' .viem-ajax-prev-page').removeClass('ajax-page-disabled');
                              $('#'+$wrap_id+' .viem-ajax-prev-page').attr('data-offset', parseInt(offset) - parseInt(posts_per_page));

                            }else if( $($_this).hasClass('viem-ajax-prev-page') ){
                              $('#'+$wrap_id+' .viem-next-prev-wrap .viem-ajax-prev-page').attr('data-current-page', current_page - 1);
                              $('#'+$wrap_id+' .viem-next-prev-wrap .viem-ajax-next-page').attr('data-current-page', current_page);

                              if(current_offset <= 0){
                                $($_this).addClass('ajax-page-disabled');
                                $($_this).attr('data-offset', 0);
                                $('#'+$wrap_id+' .viem-ajax-next-page').attr('data-offset', parseInt(posts_per_page));
                                $('#'+$wrap_id+' .viem-next-prev-wrap .viem-ajax-next-page').attr('data-current-page', 1);

                              }else{
                                $($_this).attr('data-offset', parseInt(current_offset) - parseInt(posts_per_page));

                                $('#'+$wrap_id+' .viem-ajax-next-page').attr('data-offset', parseInt(current_offset) + parseInt(posts_per_page));
                              }
                              
                              $('#'+$wrap_id+' .viem-ajax-next-page').removeClass('ajax-page-disabled');
                              
                            }

                            // hidden action
                            if( $('#'+$wrap_id+' #viem-ajax-no-p').length > 0 ){
                              $_this.addClass('ajax-page-disabled');
                            }
                            
                  },500);
                  
                }else{

                }
              }
          });
        }
      });
    });
  };
  
  DawnThemes.SmartSidebar = {
        itemList: [],
        lastKnownScrollY: 0,
        navbar: undefined,
        navbarHeight: 0,
        navbarFixedHeight: DawnThemes.getNavbarFixedHeight(),
        enable: true,
        ready: false,
        hasItem: false,
        adminbarHeight: 0,
        windowHeight: 0,
        addItem: function(item) {
            if (item.sidebar.length && item.content.length) {
                DawnThemes.SmartSidebar.hasItem = true;
                item.sidebar.data('state', 'default');
                item.sidebar.data('sidebar_height', 0);
                item.sidebar.data('content_height', 0);
                item.sidebar.data('navbar_offset', 0);
                DawnThemes.SmartSidebar.itemList.push({
                    sidebar: {
                        element: item.sidebar,
                        height: 0,
                        width: 0,
                        top: 0,
                        bottom: 0
                    },
                    content: {
                        element: item.content,
                        top: 0,
                        height: 0,
                        bottom: 0
                    },
                    state: ''
                });
            }
        },
        _isEqual: function(number1, number2) {
            if (Math.abs(number1 - number2) >= 1) {
                if (number1 < number2) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        },
        _isSmaller: function(number1, number2) {
            if (Math.abs(number1 - number2) >= 1) {
                if (number1 < number2) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        },
        _calc: function() {
            DawnThemes.SmartSidebar.adminbarHeight = DawnThemes.getAdminbarHeight();
            DawnThemes.SmartSidebar.navbarHeight = DawnThemes.SmartSidebar.navbarFixedHeight + DawnThemes.SmartSidebar.adminbarHeight;
            if ($('.header7-smart').length && $('.header-container').hasClass('header-type-7'))
                DawnThemes.SmartSidebar.navbarHeight = DawnThemes.SmartSidebar.adminbarHeight;
        },
        init: function() {
            if (!DawnThemes.enableAnimation() || !DawnThemes.SmartSidebar.hasItem) {
                DawnThemes.SmartSidebar.enable = false;
                return;
            }
            if (!DawnThemes.SmartSidebar.ready) {
                DawnThemes.SmartSidebar._calc();
                DawnThemes.SmartSidebar.ready = true;
            }
        },
        onScroll: function(scrollTop) {
            if (window.requestAnimationFrame) {
                window.requestAnimationFrame(function() {
                    DawnThemes.SmartSidebar.update(scrollTop);
                });
            } else {
                DawnThemes.SmartSidebar.update(scrollTop);
            }
        },
        _destroy: function() {
            if (!DawnThemes.SmartSidebar.hasItem || !DawnThemes.SmartSidebar.enable || !DawnThemes.SmartSidebar.ready) {
                return;
            }
            for (var i = 0; i < DawnThemes.SmartSidebar.itemList.length; i++) {
                var currentItem = DawnThemes.SmartSidebar.itemList[i];
                currentItem.sidebar.element.data('state', 'default');
                currentItem.sidebar.element.data('sidebar_height', 0);
                currentItem.sidebar.element.data('content_height', 0);
                currentItem.sidebar.element.data('navbar_offset', 0);
                currentItem.state = '';
                DawnThemes.SmartSidebar.ready = false;
                DawnThemes.SmartSidebar.enable = false;
                currentItem.sidebar.element.attr('style', '');
            }
        },
        onResize: function() {
            if (!DawnThemes.enableAnimation() || window.innerWidth < 992) {
                if (DawnThemes.SmartSidebar.ready) {
                    DawnThemes.SmartSidebar._destroy();
                    DawnThemes.SmartSidebar.enable = false;
                }
                return;
            } else {
                DawnThemes.SmartSidebar.enable = true;
                DawnThemes.SmartSidebar.init();
                DawnThemes.SmartSidebar._calc();
                if (window.requestAnimationFrame) {
                    window.requestAnimationFrame(function() {
                        DawnThemes.SmartSidebar.update($(window).scrollTop());
                    });
                } else {
                    DawnThemes.SmartSidebar.update($(window).scrollTop());
                }
            }
        },
        _adjustSidebar: function(currentItem) {
            var state = currentItem.state;
            if ((('pinnedBottom' != state || 'fixedUp' != state) && state === currentItem.sidebar.element.data('state')) || ('pinnedBottom' === state && 'pinnedBottom' === currentItem.sidebar.element.data('state') && currentItem.sidebar.element.data('sidebar_height') === currentItem.sidebar.height && currentItem.sidebar.element.data('content_height') === currentItem.content.height) || ('fixedUp' === state && 'fixedUp' === currentItem.sidebar.element.data('state') && currentItem.sidebar.element.data('navbar_offset') === DawnThemes.SmartSidebar.navbarHeight))
                return;
            switch (state) {
                case 'fixedDown':
                    currentItem.sidebar.element.css({
                        width: currentItem.sidebar.width,
                        position: "fixed",
                        top: "auto",
                        bottom: "0",
                        "z-index": "1"
                    });
                    break;
                case 'default':
                    currentItem.sidebar.element.css({
                        width: "auto",
                        position: "static",
                        top: "auto",
                        bottom: "auto"
                    });
                    break;
                case 'pinnedBottom':
                    currentItem.sidebar.element.data('sidebar_height', currentItem.sidebar.height);
                    currentItem.sidebar.element.data('content_height', currentItem.content.height);
                    currentItem.sidebar.element.css({
                        width: currentItem.sidebar.width,
                        position: "absolute",
                        top: currentItem.content.bottom - currentItem.sidebar.height - currentItem.content.top,
                        bottom: "auto"
                    });
                    break;
                case 'fixedUp':
                    currentItem.sidebar.element.data('navbar_offset', DawnThemes.SmartSidebar.navbarHeight);
                    currentItem.sidebar.element.css({
                        width: currentItem.sidebar.width,
                        position: "fixed",
                        top: DawnThemes.SmartSidebar.navbarHeight,
                        bottom: "auto"
                    });
                    break;
                case 'pinnedTop':
                    currentItem.sidebar.element.css({
                        width: currentItem.sidebar.width,
                        position: "absolute",
                        top: currentItem.sidebar.top - currentItem.content.top,
                        bottom: "auto"
                    });
                    break;
            }
            currentItem.sidebar.element.data('state', state);
        },
        update: function(scrollTop) {
            if (!DawnThemes.SmartSidebar.hasItem || !DawnThemes.SmartSidebar.enable || !DawnThemes.SmartSidebar.ready) {
                return;
            }
            DawnThemes.SmartSidebar.windowHeight = DawnThemes.getViewport().height;
            var currentScrollY = scrollTop,
                windowBottom = scrollTop + DawnThemes.SmartSidebar.windowHeight,
                scrollDirection = '';
            if (currentScrollY !== DawnThemes.SmartSidebar.lastKnownScrollY) {
                if (currentScrollY > DawnThemes.SmartSidebar.lastKnownScrollY) {
                    scrollDirection = 'down';
                } else {
                    scrollDirection = 'up';
                }
            }
            DawnThemes.SmartSidebar.lastKnownScrollY = currentScrollY;
            currentScrollY = currentScrollY + DawnThemes.SmartSidebar.navbarHeight;
            for (var i = 0; i < DawnThemes.SmartSidebar.itemList.length; i++) {
                var currentItem = DawnThemes.SmartSidebar.itemList[i];
                currentItem.content.height = currentItem.content.element.outerHeight();
                currentItem.content.top = currentItem.content.element.offset().top;
                currentItem.content.bottom = currentItem.content.top + currentItem.content.height;
                currentItem.sidebar.top = currentItem.sidebar.element.offset().top;
                currentItem.sidebar.height = currentItem.sidebar.element.outerHeight();
                currentItem.sidebar.width = currentItem.sidebar.element.width();
                currentItem.sidebar.bottom = currentItem.sidebar.top + currentItem.sidebar.height;
                if (currentItem.content.height <= currentItem.sidebar.height) {
                    currentItem.state = 'default';
                    DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'default');
                } else if (currentItem.sidebar.height < DawnThemes.SmartSidebar.windowHeight) {
                    if (DawnThemes.SmartSidebar._isEqual(currentScrollY, currentItem.content.top)) {
                        currentItem.state = 'default';
                        DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'default');
                    } else if (true === DawnThemes.SmartSidebar._isSmaller(currentItem.sidebar.bottom, currentScrollY)) {
                        if (DawnThemes.SmartSidebar._isSmaller(currentScrollY, (currentItem.content.bottom - currentItem.sidebar.height))) {
                            currentItem.state = 'fixedUp';
                            DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'fixedUp');
                        } else {
                            currentItem.state = 'pinnedBottom';
                            DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'pinnedBottom');
                        }
                    } else {
                        if (DawnThemes.SmartSidebar._isEqual(currentItem.content.bottom, currentItem.sidebar.bottom)) {
                            if ('up' === scrollDirection && DawnThemes.SmartSidebar._isEqual(currentScrollY, currentItem.sidebar.top)) {
                                currentItem.state = 'fixedUp';
                                DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'fixedUp');
                            } else {
                                currentItem.state = 'pinnedBottom';
                                DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'pinnedBottom');
                            }
                        } else {
                            if (currentItem.content.bottom - currentScrollY >= currentItem.sidebar.height) {
                                currentItem.state = 'fixedUp';
                                DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'fixedUp');
                            } else {
                                currentItem.state = 'pinnedBottom';
                                DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'pinnedBottom');
                            }
                        }
                    }
                } else {
                    if (true === DawnThemes.SmartSidebar._isSmaller(currentItem.sidebar.bottom, currentScrollY)) {
                        if (true === DawnThemes.SmartSidebar._isEqual(currentScrollY, currentItem.sidebar.top) && true === DawnThemes.SmartSidebar._isEqual(currentItem.content.top, currentScrollY)) {
                            currentItem.state = 'fixedUp';
                            DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'fixedUp');
                        } else if (true === DawnThemes.SmartSidebar._isSmaller(currentItem.sidebar.bottom, windowBottom) && true === DawnThemes.SmartSidebar._isSmaller(currentItem.sidebar.bottom, currentItem.content.bottom) && currentItem.content.bottom >= windowBottom) {
                            currentItem.state = 'fixedDown';
                            DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'fixedDown');
                        } else {
                            currentItem.state = 'pinnedBottom';
                            DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'pinnedBottom');
                        }
                    } else if (true === DawnThemes.SmartSidebar._isSmaller(currentItem.sidebar.bottom, windowBottom) && true === DawnThemes.SmartSidebar._isSmaller(currentItem.sidebar.bottom, currentItem.content.bottom) && 'down' === scrollDirection && currentItem.content.bottom >= windowBottom) {
                        currentItem.state = 'fixedDown';
                        DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'fixedDown');
                    } else if (true === DawnThemes.SmartSidebar._isEqual(currentItem.sidebar.top, currentItem.content.top) && 'up' === scrollDirection && currentItem.content.bottom >= windowBottom) {
                        currentItem.state = 'default';
                        DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'default');
                    } else if ((true === DawnThemes.SmartSidebar._isEqual(currentItem.content.bottom, currentItem.sidebar.bottom) && 'down' === scrollDirection) || currentItem.content.bottom < windowBottom) {
                        currentItem.state = 'pinnedBottom';
                        DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'pinnedBottom');
                    } else if (true === DawnThemes.SmartSidebar._isEqual(currentScrollY, currentItem.sidebar.top) && 'up' === scrollDirection && true === DawnThemes.SmartSidebar._isEqual(currentItem.content.top, currentScrollY)) {
                        currentItem.state = 'fixedUp';
                        DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'fixedUp');
                    }
                    if (('fixedDown' === currentItem.sidebar.element.data('state') && 'up' === scrollDirection) || ('fixedUp' === currentItem.sidebar.element.data('state') && 'down' === scrollDirection)) {
                        currentItem.state = 'pinnedTop';
                        DawnThemes.SmartSidebar._adjustSidebar(currentItem, 'pinnedTop');
                    }
                }
            }
        }
    };

    DawnThemes.AutoNextVideo = {
      init: function(){
          if(typeof(Cookies.get('viem_autonextvideo'))!='undefined'){
            if(Cookies.get('viem_autonextvideo')=='on'){
              $('.viem-auto-next-op').addClass('active');
              return true;
            }else{
              $('.viem-auto-next-op').removeClass('active');
              return false;
            };  
          }else{
            if($('.viem-auto-next-op').hasClass('active')){
              return true;
            }else{
              return false;
            };
          };
      }
    };
  
  	DawnThemes.VideoPlayLists = {
        init: function() {
            if ($('.video-playlists').length) {
                $('.video-playlists').each(function() {
                    var container = $(this),
                        service = $(this).data('service');
                    DawnThemes.VideoPlayLists._nextVideo(container);
                    //Load first Video
                    DawnThemes.VideoPlayLists.runFirst(service, container);
                    container.find('.video-playlists-control-icon a').on('click', function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        var videoID = container.data('current-video');
                        var _this = $(this).closest('.video-playlists-control');
                        if (_this.hasClass('current-playing')) {
                            DawnThemes.VideoPlayLists._pauseVideo(service, container, videoID);
                        } else if (_this.hasClass('current-paused')) {
                            DawnThemes.VideoPlayLists._playVideo(service, container, videoID);
                        }
                    });
                    //Click Item Event
                    container.find('.video-playlists-item').on('click', function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        var _this = $(this),
                            videoID = _this.data('video-id');
                        DawnThemes.VideoPlayLists._activeControlItem(_this);
                        if (videoID === container.data('current-video')) {
                            if (_this.hasClass('current-playing')) {
                                DawnThemes.VideoPlayLists._pauseVideo(service, container, videoID);
                            } else if (_this.hasClass('current-paused')) {
                                DawnThemes.VideoPlayLists._playVideo(service, container, videoID);
                            }
                        } else {
                            container.data('current-video', videoID);
                            DawnThemes.VideoPlayLists.runClick(service, container, videoID);
                        }
                    });
                });
            }
        },
        runFirst: function(service, container) {
            switch (service) {
                case 'youtube':
                    DawnThemes.VideoPlayLists._YTPlayer(container, container.data('current-video'));
                    break;
                case 'vimeo':
                    DawnThemes.VideoPlayLists._VMPlayerEvent(container.find('iframe')[0], container);
                    break;
            }
        },
        runClick: function(service, container, videoID) {
            switch (service) {
                case 'youtube':
                    DawnThemes.VideoPlayLists._YTPlayerLoadVideo(container, videoID);
                    break;
                case 'vimeo':
                    DawnThemes.VideoPlayLists._VMPlayer(container, videoID, true);
                    break;
            }
        },
        fitvids: function() {
            $('.video-playlists').dtfitvids();
        },
        _activeControlItem: function(controlItem) {
            controlItem.closest('.video-playlists-list').find('.active').removeClass('active');
            controlItem.addClass('active');
            DawnThemes.VideoPlayLists._activeControl(controlItem);
        },
        _statusControlItem: function(status, container) {
            var controlItem = $('[data-video-id="' + container.data('current-video') + '"]');
            container.find('.video-playlists-item').removeClass('current-paused current-playing');
            DawnThemes.VideoPlayLists._statusControl(status, container);
            if ('playing' === status) {
                controlItem.addClass('current-playing');
            } else if ('paused' === status) {
                controlItem.addClass('current-paused');
            }
        },
        _statusControl: function(status, container) {
            var control = container.find('.video-playlists-control');
            control.removeClass('current-paused current-playing');
            if ('playing' === status) {
                control.addClass('current-playing');
            } else if ('paused' === status) {
                control.addClass('current-paused');
            }
        },
        _activeControl: function(controlItem) {
            var control = controlItem.closest('.video-playlists-content').find('.video-playlists-control');
            control.find('.video-playlists-control-title').text(controlItem.find('.video-playlists-item-title').text());
            control.find('.video-playlists-control-time').text(controlItem.find('.video-playlists-item-time').text());
        },
        _nextVideo: function(container) {
            var control = $('[data-video-id="' + container.data('current-video') + '"]');
            if (container.data('auto-next')) {
                var videoList = container.data('video-list'),
                    nextVideoId = 0,
                    found = false,
                    currentVideo = container.data('current-video');
                $.each(videoList, function(index, video) {
                    if (true === found) {
                        nextVideoId = video;
                        found = false;
                        return;
                    }
                    if (video == currentVideo) {
                        found = true;
                    }
                });
                if (0 !== nextVideoId) {
                    control.removeClass('current-playing');
                    container.find('[data-video-id="' + nextVideoId + '"]').trigger('click');
                }
            }
        },
        _playVideo: function(service, container) {
            var ifr = container.find('iframe');
            switch (service) {
                case 'youtube':
                    try {
                        if (ifr.data('player') !== undefined) {
                            ifr.data('player').playVideo();
                        }
                    } catch (e) {}
                    break;
                case 'vimeo':
                    var player = $f(ifr[0]);
                    player.api('play');
                    break;
            }
        },
        _pauseVideo: function(service, container) {
            var ifr = container.find('iframe');
            switch (service) {
                case 'youtube':
                    try {
                        if (ifr.data('player') !== undefined) {
                            ifr.data('player').pauseVideo();
                        }
                    } catch (e) {}
                    break;
                case 'vimeo':
                    var player = $f(ifr[0]);
                    player.api('pause');
                    break;
            }
        },
        _YTPlayerLoadVideo: function(container, videoID) {
            var ifr = container.find('iframe');
            try {
                if (ifr.data('player') !== undefined) {
                    ifr.data('player').loadVideoById(videoID);
                }
            } catch (e) {}
        },
        _YTPlayer: function(container) {
            var ifr = container.find('iframe'),
                frameID = "youtube_player_" + Math.round(Math.random() * 100000 + 1);
            ifr.attr('id', frameID);
            if ('undefined' === typeof(YT) || 'undefined' === typeof(YT.Player)) {
               
            	// 2. This code loads the IFrame Player API code asynchronously.
                var tag = document.createElement('script');

                tag.src = "https://www.youtube.com/iframe_api";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            }
            window.onYouTubePlayerAPIReady = function() {
                var player = new YT.Player(frameID, {
                    events: {
                        'onStateChange': function(event) {
                            if (event.data === YT.PlayerState.PLAYING) {
                                DawnThemes.VideoPlayLists._statusControlItem('playing', container);
                            } else if (event.data === YT.PlayerState.ENDED) {
                                DawnThemes.VideoPlayLists._nextVideo(container);
                            } else if (YT.PlayerState.PAUSED) {
                                DawnThemes.VideoPlayLists._statusControlItem('paused', container);
                            }
                        },
                        'onReady': function(event) {
                            if (container.data('auto-play')) {
                                event.target.playVideo();
                            }
                        }
                    }
                });
                ifr.data('player', player);
            };
        },
        _VMPlayer: function(container, videoID, isClick) {
            isClick = isClick || false;
            var frameID = "vimeo_player_" + Math.round(Math.random() * 100000 + 1);
            $(container).find('.video-playlists-player').html('<iframe name="' + frameID + '" id="' + frameID + '" src="https://player.vimeo.com/video/' + videoID + '?api=1&player_id=' + frameID + '&autoplay=1" width="' + $(container).find('.dtfitvids').data('width') + '" height="' + $(container).find('.dtfitvids').data('height') + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
            var ifr = $(container).find('#' + frameID),
                frameDOM = ifr[0];
            DawnThemes.VideoPlayLists._VMPlayerEvent(frameDOM, container, isClick);
        },
        _VMPlayerEvent: function(frameDOM, container, isClick) {
        	if(typeof $f !== 'undefined'){
	            var player = $f(frameDOM);
	            player.addEvent('ready', function() {
	                DawnThemes.VideoPlayLists.fitvids();
	                if (container.data('auto-play')) {
	                    player.api('play');
	                }
	                if (isClick) DawnThemes.VideoPlayLists._statusControlItem('playing', container);
	                player.addEvent('play', function() {
	                    DawnThemes.VideoPlayLists._statusControlItem('playing', container);
	                });
	                player.addEvent('pause', function() {
	                    DawnThemes.VideoPlayLists._statusControlItem('paused', container);
	                });
	                player.addEvent('finish', function() {
	                    if (!DawnThemes.userAgent.isAndroid || DawnThemes.userAgent.isIOS) DawnThemes.VideoPlayLists._nextVideo(container);
	                });
	            });
        	}
        }
    };
  
  DawnThemes.getAdminbarHeight = function() {
        var adminbarHeight = 0;
        if ($('#wpadminbar').length) {
            adminbarHeight = parseInt($('#wpadminbar').outerHeight());
        }
        return adminbarHeight;
    };
    
    DawnThemes.counter = function(){
    	if (!DawnThemes.enableAnimation()) {
            return;
        }
        $('.viem-sc-counter').each(function() {
            var $this = $(this);
            var counter_number = $this.find('.counter-number');
            counter_number.text('0');
            "undefined" != typeof jQuery.fn.waypoint && $this.waypoint(function() {
                counter_number.countTo({
                    from: 0,
                    to: counter_number.data('to'),
                    speed: counter_number.data('speed'),
                    decimals: counter_number.data('num-decimals'),
                    decimal: counter_number.data('decimal-sep'),
                    thousand: counter_number.data('thousand-sep'),
                    refreshInterval: 30,
                    formatter: function(value, options) {
                        value = value.toFixed(options.decimals);
                        value += '';
                        var x, x1, x2, rgx;
                        x = value.split('.');
                        x1 = x[0];
                        x2 = x.length > 1 ? options.decimal + x[1] : '';
                        rgx = /(\d+)(\d{3})/;
                        if (typeof(options.thousand) === 'string' && options.thousand != '') {
                            while (rgx.test(x1)) {
                                x1 = x1.replace(rgx, '$1' + options.thousand + '$2');
                            }
                        }
                        return x1 + x2;
                    }
                });
            }, {
                offset: "85%",
                triggerOnce: true
            });
        });
    };

    DawnThemes.fix_vc_full_width_row = function(){
        if( $('body').hasClass('rtl') ){
            function viem_fix_vc_full_width_row(){
              var $elements = jQuery('[data-vc-full-width="true"]');
              jQuery.each($elements, function () {
                  var $el = jQuery(this);
                  $el.css('right', $el.css('left')).css('left', '');
              });
          }

          // Fixes rows in RTL
          jQuery(document).on('vc-full-width-row', function () {
              viem_fix_vc_full_width_row();
          });

          // Run one time because it was not firing in Mac/Firefox and Windows/Edge some times
          viem_fix_vc_full_width_row();
        }
    };
    
    DawnThemes.helperInit = function() {
    	//PopUp
	    DawnThemes.magnificpopupInit();
	      //Load more
	    DawnThemes.loadmoreInit();
	    //Infinite Scrolling
	    DawnThemes.infiniteScrollInit();
	    //Media element player
	    DawnThemes.mediaelementplayerInit();
	    //isotope
	    DawnThemes.isotopeInit();
	    DawnThemes.slickSlider();
	    DawnThemes.carouselInit();
	    DawnThemes.CountdownInit();
	    //Ajax Search
	    DawnThemes.AjaxSearch.init();
	    
	    DawnThemes.counter();

      //DawnThemes.fix_vc_full_width_row();
    };
    
  DawnThemes.getViewport = function() {
        var e = window,
            a = 'inner';
        if (!('innerWidth' in window)) {
            a = 'client';
            e = document.documentElement || document.body;
        }
        return {
            width: e[a + 'Width'],
            height: e[a + 'Height']
        };
    };
    DawnThemes.enableAnimation = function() {
        if ('yes' === DawnThemesL10n.is_mobile_theme)
            return false;
        return DawnThemes.getViewport().width > DawnThemesL10n.breakpoint && !DawnThemes.userAgent.isTouch;
    };
    DawnThemes.historySupport = function() {
        return !!window.history && !!history.pushState;
    };
  
  DawnThemes.Event.init();
  $.DawnThemes= DawnThemes;

  $(document).ajaxComplete(function(){
  });
})(window.jQuery);
(function($){
  'use strict';
  var body = $( 'body' ),
    $win = $( window );
  
  $(document).ready(function(){
        // Do something here
        var $windowHeight = $win.height();
        var $htmlHeight = $('html').outerHeight();
        
        if( $htmlHeight <  $windowHeight ){
          var $wpadminbarHeight = 0;
          if( $('#wpadminbar').length ){
            $wpadminbarHeight = $('#wpadminbar').height();
          }
          $( '#main.site-main' ).css('min-height', ($windowHeight - $wpadminbarHeight )- ($('#masthead').height() +  $('#footer').height()) );
        }
      
      // Quantity Input plus/minus btns
	  $('.quantity').on('click', '.qty-increase', function(e) {
		  var $input = $(this).closest('.quantity').find('input.qty');
    	  var $val = parseInt($input.val());
    	  $input.val( $val + 1 ).change();
      });
	  
	  $('.quantity').on('click', '.qty-decrease', function(e) {
		  var $input = $(this).closest('.quantity').find('input.qty');
		  var $val = parseInt($input.val());
      var $min = $($input).attr('min');
      $min = ( $min == 0 ) ? 1 : $min; console.log($min); 
		  if( $val > $min){
			  $input.val( $val - 1 ).change();
		  }
	  });
	  
	  /* WtiLikePost */
	  function setClickLike(obj){
			var $this = obj;
			setTimeout(function(){
				var interValLike = setInterval(function(){
					if(!$this.parents('.watch-action').find('.status').hasClass('loading')) {
						if($this.attr('data-task')=='like'){
							$('[data-post_id="'+$this.attr('data-post_id')+'"][data-task="like"]').addClass('active');
							$('[data-post_id="'+$this.attr('data-post_id')+'"][data-task="unlike"]').removeClass('active');
						}else{
							$('[data-post_id="'+$this.attr('data-post_id')+'"][data-task="unlike"]').addClass('active');
							$('[data-post_id="'+$this.attr('data-post_id')+'"][data-task="like"]').removeClass('active');
						};						
						clearInterval(interValLike);
						return false;
					};
				},50);				
			},500);
		};
		
		$('.viem-video-features .toolbar-block-item.viem-like-post .action-like > .jlk, .viem-video-features .toolbar-block-item.viem-like-post .action-unlike > .jlk').on('click', function(){		
			setClickLike($(this));
		});
		
		// Share video
		$('.viem-video-features .toolbar-block-item .toggle_social').on('click', function(){
			if( $(this).hasClass('active') ){
				$(this).removeClass('active');
				$(this).parents('.viem-video-features').find('.share-links').removeClass('active');
			}else{
				$(this).addClass('active');
				$(this).parents('.viem-video-features').find('.share-links').addClass('active');
			}
		});
		
		// More videos
		$('.viem-video-features .toolbar-block-item .show-more-videos').on('click', function(e){
      e.preventDefault();
			if( $(this).hasClass('active') ){
				$(this).removeClass('active');
				$(this).parents('.viem-video-player-wrapper, #viem-playlist-wrapper').find('.viem-more-videos-slider').removeClass('active');
			}else{
				$(this).addClass('active');
				$(this).parents('.viem-video-player-wrapper, #viem-playlist-wrapper').find('.viem-more-videos-slider').addClass('active');
			}
		});
  });
 
  if(typeof DawnThemesL10n.smoothscroll !== 'undefined' && DawnThemesL10n.smoothscroll && typeof DawnThemesL10n.device === 'undefined') {
		smoothScroll();
  }

  //Loading video
  $("#viem_btn_lightbulb").on('click', function (e) {
    e.preventDefault();
    var $this = $(this);
    if ($this.hasClass('on')) {
      $this.removeClass('on');
      $("#viem_background_lamp").removeClass('on');
      $(".viem-video-player-wrapper").removeClass('light_on');
      $("body").removeClass('viem_light_on');
    } else {
      $this.addClass('on');
      $("#viem_background_lamp").addClass('on');
       $(".viem-video-player-wrapper").addClass('light_on');
       $("body").addClass('viem_light_on');
    }
  });

  $('.viem-auto-next-op').on('click', function(){
    var $this_btn = $(this);
    $($this_btn).toggleClass('active');
    if( $($this_btn).hasClass('active') ){
      Cookies.set('viem_autonextvideo', 'on');
    }else{
      Cookies.set('viem_autonextvideo', 'off');
    }
  });

  /* showmore post content */
  $('.showmore-post-content-btn .showmore-btn').on('click', function(e){
      e.preventDefault();
      $('body.single').find('.post-content.hidden-content').removeClass('hidden-content');
  });
  
})(window.jQuery);
// Class rating
(function($){
  'use strict';
	// viem_single_class_params is required to continue, ensure the object exists
	if ( typeof DawnThemesL10n.i18n_required_rating_text === 'undefined' ) {
		return false;
	}
	$('#viem_class_reviews #rating').hide();
	$( 'body' )
		.on( 'click', '#viem_class_reviews #respond .class-stars-review a', function() {
			var $star   	= $( this ),
				$rating 	= $( this ).closest( '#viem_class_reviews #respond' ).find( '#rating' ),
				$container 	= $( this ).closest( '.class-stars-review' );
	
			$rating.val( $star.text() );
			$star.siblings( 'a' ).removeClass( 'active' );
			$star.addClass( 'active' );
			$container.addClass( 'selected' );
	
			return false;
		})
		.on( 'click', '#viem_class_reviews #respond #submit', function() {
			var $rating = $( this ).closest( '#viem_class_reviews #respond' ).find( '#rating' ),
				rating  = $rating.val();
	
			if ( $rating.length > 0 && ! rating && DawnThemesL10n.review_rating_required === 'yes' ) {
				window.alert( DawnThemesL10n.i18n_required_rating_text );
	
				return false;
			}
		});
})(window.jQuery);
(function($){
    'use strict';
    $(window).on("load",function(){
        $('.viem-playlist-items').mCustomScrollbar({
            axis:"y",
            theme: "light-2",
        });
        $('.viem-playlist-items').mCustomScrollbar('scrollTo',$('.viem-playlist-items').find('.viem-playlist-item-video.active'));
    });
})(window.jQuery);