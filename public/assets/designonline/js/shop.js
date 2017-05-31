(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  $(document).foundation();

  jQuery(function($) {
    var FullscreenSlider, HEADER, PAGE, VideoModal, addToCart, addToCartFail, addToCartPass, cart_dropdown_timer, fadeOutCartDropdown, fullscreen_slider, imgZoom, isFirefox, large_photos, large_thumbs, log, mainMenu, mobileMenu, page_content, productModal, recentCartItemPopUp, retinaLogo, searchAndAccount, slideDownCartDropdown, smallPromos, small_photos, small_thumbs, startTimer, stickyFooter, stopResetTimer, toggleCartDropdown, validateSize;
    PAGE = $('body');
    HEADER = $('.main-header');
    log = function(value) {
      if (typeof console !== "undefined") {
        return console.log(value);
      }
    };
    stickyFooter = function() {
      var total_content_height;
      total_content_height = $('.main-header').outerHeight() + $('.main-content').outerHeight() + $('.main-footer').outerHeight();
      if ($(window).outerHeight() > total_content_height) {
        return $('.main-content').css({
          'min-height': $(window).outerHeight() - $('.main-header').outerHeight() - $('.main-footer').outerHeight()
        });
      }
    };
    stickyFooter();
    $(window).resize(function() {
      return stickyFooter();
    });
    retinaLogo = function() {
      if ($('.main-header .title img').length && window.devicePixelRatio >= 2) {
        return $('.main-header .title img').imagesLoaded(function() {
          $(this).width($(this).naturalWidth());
          return $(this).attr('src', $(this).attr('data-retina'));
        });
      }
    };
    retinaLogo();
    searchAndAccount = function() {
      $('.searchbar-open').click(function() {
        $(this).closest('.menu').fadeOut(100, function() {
          $('.main-header .searchbar-container').fadeIn(200);
          return $('.main-header .searchbar-container .search-box').focus();
        });
        return false;
      });
      $('.searchbar-close').click(function() {
        $('.main-header .searchbar-container').fadeOut(100, function() {
          return $('.search-account .menu').fadeIn(200);
        });
        return false;
      });
      $('.account-open').click(function() {
        $(this).closest('.menu').fadeOut(100, function() {
          return $('.account-container').fadeIn(200);
        });
        return false;
      });
      return $('.account-close').click(function() {
        $('.account-container').fadeOut(100, function() {
          return $('.search-account .menu').fadeIn(200);
        });
        return false;
      });
    };
    searchAndAccount();
    mainMenu = function() {
      var dropdown_panel, main_header, main_menu_dropdown_timer, slideUpPanel, startTimer, stopResetTimer;
      dropdown_panel = $(".main-menu-dropdown-panel .row");
      main_header = $(".template-index .main-header");
      HEADER.find(".main-menu .widescreen .dropdown > a").click(function() {
        var autoHeight, curHeight, dropdown, sub_nav;
        dropdown = $(this).parent();
        sub_nav = dropdown.find(".sub-nav .columns");
        if (PAGE.hasClass('template-index') && PAGE.hasClass('transparent-menu') && Modernizr.touch) {
          if ($('.main-header').hasClass('dropdown-open')) {
            if (dropdown.hasClass("active")) {
              startTimer();
            }
          } else {
            $('.main-header .bg').fadeIn();
          }
        }
        if (dropdown.hasClass("active")) {
          slideUpPanel();
        } else if ($('.main-header').hasClass('dropdown-open')) {
          dropdown_panel.find(".columns").animate({
            opacity: 0
          }, 200);
          dropdown_panel.find('.columns').remove();
          HEADER.find(".main-menu .dropdown").removeClass('active');
          dropdown.addClass("active");
          sub_nav.clone().appendTo(".main-menu-dropdown-panel .row");
          dropdown_panel.find(".columns").delay(200).animate({
            opacity: 1
          }, 200);
          curHeight = dropdown_panel.height();
          autoHeight = dropdown_panel.css('height', 'auto').outerHeight();
          dropdown_panel.height(curHeight).animate({
            height: autoHeight
          }, 400);
        } else {
          dropdown_panel.find('.columns').remove();
          $('.main-header').addClass('dropdown-open');
          dropdown.addClass("active");
          sub_nav.clone().appendTo(".main-menu-dropdown-panel .row");
          dropdown_panel.slideDown(400, function() {
            return dropdown_panel.css("height", dropdown_panel.outerHeight());
          });
          dropdown_panel.find(".columns").delay(200).animate({
            opacity: 1
          }, 200);
        }
        return false;
      });
      slideUpPanel = function() {
        $('.main-header').removeClass('dropdown-open');
        dropdown_panel.find(".columns").animate({
          opacity: 0
        }, 200);
        return dropdown_panel.delay(200).slideUp(function() {
          HEADER.find(".main-menu .dropdown").removeClass('active');
          dropdown_panel.find('.columns').remove();
          return dropdown_panel.css('height', 'auto');
        });
      };
      main_menu_dropdown_timer = '';
      if (Modernizr.touch === false) {
        $('.main-header').mouseenter(function() {
          if (PAGE.hasClass('template-index') && PAGE.hasClass('transparent-menu')) {
            $('.main-header .bg').fadeIn();
          }
          return stopResetTimer();
        }).mouseleave(function() {
          if ($('.main-header').hasClass('dropdown-open')) {
            return startTimer();
          } else {
            if (PAGE.hasClass('template-index') && main_header.css("position") === "absolute") {
              return $('.main-header .bg').stop(true, true).fadeOut();
            }
          }
        });
      }
      startTimer = function() {
        return main_menu_dropdown_timer = setTimeout((function() {
          slideUpPanel();
          if (PAGE.hasClass('template-index') && PAGE.hasClass('transparent-menu')) {
            return $('.main-header .bg').delay(300).fadeOut();
          }
        }), 500);
      };
      return stopResetTimer = function() {
        return clearTimeout(main_menu_dropdown_timer);
      };
    };
    mainMenu();
    mobileMenu = function() {
      var dropdown_links, mobile_menu, mobile_menu_link;
      mobile_menu_link = $('.mobile-tools .menu');
      mobile_menu = $('.mobile-menu');
      dropdown_links = mobile_menu.find("a.dropdown-link");
      mobile_menu_link.click(function() {
        mobile_menu.toggle();
        return false;
      });
      return dropdown_links.click(function() {
        var sub_menu;
        sub_menu = $(this).closest('li').find('.sub-nav:eq(0)');
        sub_menu.slideToggle();
        $(this).find('.glyph.plus').toggle();
        $(this).find('.glyph.minus').toggle();
        return false;
      });
    };
    mobileMenu();
    VideoModal = (function() {
      function VideoModal(video) {
        this.createIframe = __bind(this.createIframe, this);
        this.extractVideoId = __bind(this.extractVideoId, this);
        this.extractVideoType = __bind(this.extractVideoType, this);
        this.eventListeners = __bind(this.eventListeners, this);
        this.centerPosition = __bind(this.centerPosition, this);
        this.close = __bind(this.close, this);
        this.open = __bind(this.open, this);
        this.opened = false;
        this.video = video;
        this.modal = $('.video.modal');
        this.player_button = video.find('.player-button');
        this.src_url = video.find('.play-button').attr('href');
        this.type = this.extractVideoType();
        this.id = this.extractVideoId();
        this.iframe = this.createIframe();
        this.caption = video.find('.caption');
      }

      VideoModal.prototype.open = function() {
        this.opened = true;
        this.modal.find(".flex-video").append(this.iframe);
        if (this.caption.length > 0) {
          this.modal.find(".caption").append(this.caption.html());
          this.modal.addClass("wide");
        } else {
          this.modal.find(".player").removeClass('large-8');
          this.modal.find('.caption').hide();
          this.modal.removeClass("wide");
        }
        this.player_button.hide();
        $('.modal-mask').show();
        this.modal.find('.close').show();
        this.modal.fadeIn();
        this.centerPosition();
        $(".modal").fadeIn(0);
        return this.eventListeners();
      };

      VideoModal.prototype.close = function() {
        this.opened = false;
        this.modal.find(".flex-video").empty();
        this.modal.find(".caption").empty();
        this.modal.hide();
        $('.modal-mask').fadeOut();
        if (this.caption.length === 0) {
          this.modal.find(".player").addClass('large-8');
          return this.modal.find('.caption').show();
        }
      };

      VideoModal.prototype.centerPosition = function() {
        if ($(window).height() < this.modal.outerHeight()) {
          return this.modal.css({
            'position': 'absolute',
            'top': '30px',
            'margin-top': 0,
            'margin-left': -(this.modal.outerWidth() / 2)
          });
        } else {
          return this.modal.css({
            'position': 'fixed',
            'top': '50%',
            'margin-top': -(this.modal.outerHeight() / 2),
            'margin-left': -(this.modal.outerWidth() / 2)
          });
        }
      };

      VideoModal.prototype.eventListeners = function() {
        var modal;
        modal = this;
        this.modal.find('.close').on('click', function() {
          return modal.close();
        });
        $(window).resize(function() {
          return modal.centerPosition();
        });
        $(document).keydown(function(e) {
          if (modal.opened) {
            if (e.keyCode === 27) {
              return modal.close();
            }
          }
        });
        $('.modal-mask').on('click', function() {
          return modal.close();
        });
        return this.player_button.on('click', function() {
          return false;
        });
      };

      VideoModal.prototype.extractVideoType = function() {
        var matches, re;
        re = /\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9_\-]+)/i;
        matches = re.exec(this.src_url);
        if (matches) {
          return 'youtube';
        } else {
          re = /^.*(vimeo)\.com\/(?:watch\?v=)?(.*?)(?:\z|$|&)/;
          matches = re.exec(this.src_url);
          if (matches) {
            return 'vimeo';
          }
        }
        return false;
      };

      VideoModal.prototype.extractVideoId = function() {
        var match, regExp;
        if (this.type === 'youtube') {
          regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
          match = this.src_url.match(regExp);
          if (match && match[2].length === 11) {
            return match[2];
          }
        } else if (this.type === "vimeo") {
          regExp = /^.*(vimeo)\.com\/(?:watch\?v=)?(.*?)(?:\z|$|&)/;
          match = this.src_url.match(regExp);
          if (match) {
            return match[2];
          }
        }
      };

      VideoModal.prototype.createIframe = function() {
        if (this.type === "youtube") {
          return '<iframe  src="//www.youtube.com/embed/' + this.id + '?autoplay=1" frameborder="0" allowfullscreen></iframe>';
        } else if (this.type === "vimeo") {
          return '<iframe src="//player.vimeo.com/video/' + this.id + '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff&amp;autoplay=1?" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }
      };

      return VideoModal;

    })();
    productModal = function() {
      var changeModal, closeModal, modal, modal_controls, modal_init, modal_length, modal_open, openModal, resizeModal;
      modal = $('.modal');
      if ($('.modal').length && product_modal_enabled) {
        modal_length = $(".middle-column .photo").length;
        modal_init = false;
        modal_open = false;
        modal.find('.loading').spin('small');
        $(".middle-column.photos .container").addClass('clickable');
        if (modal_length > 1) {
          modal.addClass("with-nav");
          modal_controls = modal.find('.glyph');
        } else {
          modal_controls = modal.find('.close');
        }
        resizeModal = function() {
          var active_photo, height_ratio, image_height, image_width, width_ratio, window_height, window_width;
          active_photo = modal.find('.photo.active');
          image_width = active_photo.naturalWidth();
          image_height = active_photo.naturalHeight();
          window_width = $(window).width();
          window_height = $(window).height();
          width_ratio = image_width / window_width;
          height_ratio = image_height / window_height;
          if (width_ratio > height_ratio && width_ratio > .90) {
            image_width = image_width * .90 / width_ratio;
            image_height = image_height * .90 / width_ratio;
          } else if (width_ratio < height_ratio && height_ratio > .90) {
            image_width = image_width * .90 / height_ratio;
            image_height = image_height * .90 / height_ratio;
          }
          modal.css({
            'width': image_width,
            'height': image_height,
            'margin-top': -(image_height / 2),
            'margin-left': -(image_width / 2)
          });
          active_photo.delay(200).fadeIn(100);
          return active_photo.css({
            'height': image_height
          });
        };
        openModal = function(index) {
          var active_photo;
          modal_open = true;
          if (!modal_init) {
            $(".middle-column .photo").each(function() {
              return modal.find(".slides").append($('<img />').attr('src', $(this).attr('href')).addClass('photo'));
            });
            modal_init = true;
          }
          $('.modal-mask').show();
          modal.fadeIn();
          active_photo = $(".modal img").eq(index);
          active_photo.addClass('active');
          return modal.find(".photo.active").imagesLoaded(function() {
            modal.find(".loading").hide();
            modal_controls.show();
            modal.find(".slides").show();
            return resizeModal();
          });
        };
        $('.photos.show-for-medium-up .container').on('click', function() {
          var index;
          index = $(this).find('.photo.active').index();
          return openModal(index);
        });
        closeModal = function() {
          modal_open = false;
          modal.find(".photo.active").removeClass("active");
          modal.css('display', 'none');
          return $('.modal-mask').fadeOut();
        };
        modal.find('.close').on('click', function() {
          return closeModal();
        });
        $('.modal-mask').on('click', function() {
          return closeModal();
        });
        changeModal = function(direction) {
          var active_index, active_photo, new_index, photo_length;
          modal.find('.photo').hide();
          modal.find('.photo.active').hide();
          active_photo = modal.find('.photo.active');
          active_index = modal.find('.photo.active').index();
          photo_length = modal.find('.photo').length;
          if (direction === 'prev') {
            if (active_index === 0) {
              new_index = photo_length - 1;
            } else {
              new_index = active_index - 1;
            }
          }
          if (direction === 'next') {
            if (active_index === photo_length - 1) {
              new_index = 0;
            } else {
              new_index = active_index + 1;
            }
          }
          active_photo.removeClass('active');
          //modal_controls.hide();
          //modal.find(".loading").delay(50).fadeIn(0);
          return modal.find('.photo').eq(new_index).imagesLoaded(function() {
            //modal.find(".loading").stop(true, true).fadeOut(0);
            //modal_controls.show();
            modal.find('.photo').eq(new_index).addClass('active');
            return resizeModal();
          });
        };
        modal.find('.prev').on('click', function() {
          return changeModal('prev');
        });
        modal.find('.next').on('click', function() {
          return changeModal('next');
        });
        modal.find('.slides').on('click', function() {
          return changeModal('next');
        });
        $(document).keydown(function(e) {
          if (modal_open) {
            if (e.keyCode === 37 && modal_length > 1) {
              changeModal('prev');
            }
            if (e.keyCode === 39 && modal_length > 1) {
              changeModal('next');
            }
            if (e.keyCode === 27) {
              return closeModal();
            }
          }
        });
        if (modal_init) {
          return $(window).resize(function() {
            return resizeModal();
          });
        }
      }
    };
    $('.accordion.headings').each(function() {
      return $(this).add($(this).next('.accordion.content')).wrapAll("<div class='accordion-wrapper'/>");
    });
    $('.accordion.headings li').wrapInner('<div class="trigger"></div>');
    $('.accordion.headings li .trigger').append('<div class="bg"></div>');
    $('.accordion-wrapper').each(function() {
      var accordion_content, accordion_heading;
      accordion_heading = $(this).find('.accordion.headings > li');
      accordion_content = $(this).find('.accordion.content > li');
      accordion_heading.first().addClass('active');
      accordion_content.each(function(index) {
        var content;
        content = $('<div class="content">' + $(this).html() + '</div>');
        return content.appendTo(accordion_heading.eq(index));
      });
      accordion_content.remove();
      $(this).find('.content').first().show();
      return $(this).find('.trigger').on("click", function() {
        var panels, this_panel;
        panels = $(this).closest(".accordion").find('.content');
        this_panel = $(this).closest("li").find(".content");
        panels.not(this_panel).slideUp(200);
        this_panel.slideDown(200);
        $(this).closest(".accordion").find("li").removeClass("active");
        return $(this).closest("li").addClass("active");
      });
    });
    $('.tabs-horizontal.headings').each(function() {
      return $(this).add($(this).next('.tabs.content')).wrapAll("<div class='tabs-wrapper horizontal'/>");
    });
    $('.tabs-horizontal.headings li').wrapInner('<div class="trigger"></div>');
    $('.tabs-horizontal.headings li .trigger').append('<div class="bg"></div>');
    $('.tabs-wrapper.horizontal').each(function() {
      var tab_content, tab_headings;
      tab_headings = $(this).find('.headings > li');
      tab_content = $(this).find('.tabs.content > li');
      tab_content.first().addClass('active');
      tab_headings.first().addClass('active');
      return tab_headings.on('click', function() {
        tab_headings.removeClass('active');
        tab_content.removeClass('active');
        $(this).addClass('active');
        return tab_content.eq($(this).index()).addClass('active');
      });
    });
    $('.tabs-vertical.headings').each(function() {
      return $(this).add($(this).next('.tabs.content')).wrapAll("<div class='tabs-wrapper vertical'/>");
    });
    $('.tabs-vertical.headings li').wrapInner('<div class="trigger"></div>');
    $('.tabs-vertical.headings li .trigger').append('<div class="bg"></div>');
    $('.tabs-wrapper.vertical').each(function() {
      var tab_content, tab_headings;
      tab_headings = $(this).find('.headings > li');
      tab_content = $(this).find('.tabs.content > li');
      tab_content.first().addClass('active');
      tab_headings.first().addClass('active');
      return tab_headings.on('click', function() {
        tab_headings.removeClass('active');
        tab_content.removeClass('active');
        $(this).addClass('active');
        return tab_content.eq($(this).index()).addClass('active');
      });
    });
    $('.cart-form').submit(function() {
      if ($('.main-header .mobile-tools').is(":hidden")) {
        $("html, body").animate({
          scrollTop: 0
        });
        addToCart($(this));
        return false;
      }
    });
    cart_dropdown_timer = '';
    toggleCartDropdown = function() {
      return $('.main-header .recently-added').slideToggle('fast');
    };
    slideDownCartDropdown = function() {
      return $('.main-header .recently-added').slideDown('fast');
    };
    fadeOutCartDropdown = function() {
      return $('.main-header .recently-added').fadeOut('fast');
    };
    $('.main-header .recently-added').mouseenter(function() {
      return stopResetTimer();
    });
    $('.main-header .recently-added').mouseleave(function() {
      return startTimer();
    });
    startTimer = function() {
      return cart_dropdown_timer = setTimeout((function() {
        return fadeOutCartDropdown();
      }), 4000);
    };
    stopResetTimer = function() {
      return clearTimeout(cart_dropdown_timer);
    };
    validateSize = function(cart_form) {
      if (cart_form.find('select option:selected').is(':disabled')) {
        cart_form.find('.dropdown').effect('shake', {
          'times': 2,
          'distance': 5
        }, 400);
        return false;
      }
      return true;
    };
    addToCart = function(cart_form) {
      return $.ajax({
        type: "POST",
        url: "/cart/add",
        dataType: "json",
        data: cart_form.serialize(),
        success: addToCartPass,
        error: addToCartFail
      });
    };
    addToCartPass = function(product) {
      return recentCartItemPopUp();
    };
    addToCartFail = function(obj, status) {
      $('.recently-added .error').show();
      $('.recently-added table').hide();
      $('.recently-added div.row').hide();
      slideDownCartDropdown();
      return startTimer();
    };
    recentCartItemPopUp = function() {
      var cart_item, cart_total, currency_symbol;
      cart_item = {};
      cart_total = {};
      currency_symbol = $('.actual-price').html();
      currency_symbol = $.trim(currency_symbol.slice(0, currency_symbol.search(/\d/)));
      Shopify.money_format = currency_symbol + " {{amount}}";
      return $.getJSON("/cart/getcart", function(cart, textStatus) {
        var current_raw_total, item, new_cart_row, _i, _len, _ref;
        cart_item.image_url = cart.items[0].image;
        cart_item.url = cart.items[0].url;
        cart_item.title = cart.items[0].title;
        cart_item.price_raw = cart.items[0].price;
        cart_item.price = cart_item.price_raw;
        current_raw_total = parseInt($('.recently-added .raw-total').html()) * 100;
        cart_total.quantity = 0;
        cart_total.price = 0;
        _ref = cart.items;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          item = _ref[_i];
          cart_total.quantity += item.quantity;
          cart_total.price += item.price * item.quantity;
        }
        $('.cart-link .number').html("("+cart_total.quantity+")");
        $('.recently-added .items-count .number').html(cart_total.quantity);
        $('.recently-added .total-price').html(number_format(cart_total.price));
        new_cart_row = '<tr>';
        new_cart_row += '<td class="cart-item">';
        new_cart_row += '<a href="' + cart_item.url + '">';
        new_cart_row += '<img style="width:68px;" src="' + cart_item.image_url + '" alt="' + cart_item.title + '">';
        new_cart_row += '</a>';
        new_cart_row += '</td>';
        new_cart_row += '<td class="cart-detail">';
        new_cart_row += '<h2><a href="' + cart_item.url + '">' + cart_item.title + '</a></h2>';
        new_cart_row += '</td>';
        new_cart_row += '<td class="cart-price">' + number_format(cart_item.price) + '</td>';
        new_cart_row += '</tr>';
        $('.recently-added tbody').html(new_cart_row);
        $('.recently-added .error').hide();
        $('.recently-added table').show();
        $('.recently-added div.row').show();
        slideDownCartDropdown();
        return startTimer();
      });
    };
    if (Modernizr.touch === false) {
      $('.product-grid .product-item').mouseenter(function() {
        return $(this).find('.image-wrapper').animate({
          opacity: 1
        }, 100);
      }).mouseleave(function() {
        return $(this).find('.image-wrapper').stop(true, true).animate({
          opacity: 1
        }, 300);
      });
    }
    if (PAGE.hasClass('template-page')) {
      page_content = $('.page-content .rte-content');
      if (page_content.find('.left-side-column').length || page_content.find('.right-side-column').length) {
        if (page_content.find('.left-side-column').length && page_content.find('.right-side-column').length) {
          page_content.wrapInner("<div class='main-column with-2-sidebars'></div>");
          $('.left-side-column').addClass('with-2-sidebars');
          $('.right-side-column').addClass('with-2-sidebars');
        } else {
          page_content.wrapInner("<div class='main-column'></div>");
        }
        $('.left-side-column').prependTo(page_content);
        $('.right-side-column').appendTo(page_content);
      }
    }
    if (PAGE.hasClass('template-index') || PAGE.hasClass('template-page')) {
      FullscreenSlider = (function() {
        function FullscreenSlider(slider_element, autoplay_enabled, autoplay_frequency) {
          this.eventListeners = __bind(this.eventListeners, this);
          this.alignPlayButton = __bind(this.alignPlayButton, this);
          this.alignCaption = __bind(this.alignCaption, this);
          this.getActiveIndex = __bind(this.getActiveIndex, this);
          this.autoplay = __bind(this.autoplay, this);
          this.createSlider = __bind(this.createSlider, this);
          this.el = slider_element;
          this.autoplay_enabled = autoplay_enabled;
          this.autoplay_frequency = autoplay_frequency;
          this.createSlider();
          this.owl = $(".owl-carousel").data('owlCarousel');
        }

        FullscreenSlider.prototype.createSlider = function() {
          var slider;
          slider = this;
          return slider.el.owlCarousel({
            singleItem: true,
            navigation: true,
            navigationText: false,
            paginationNumbers: false,
            scrollPerPageNav: true,
            slideSpeed: 800,
            pagination: false,
            autoHeight: true,
            autoPlay: slider.autoplay(),
            afterInit: function() {
              return slider.eventListeners();
            },
            afterAction: function() {
              slider.alignCaption();
              return slider.alignPlayButton();
            }
          });
        };

        FullscreenSlider.prototype.autoplay = function() {
          if (this.autoplay_enabled) {
            return this.autoplay_frequency;
          }
          return false;
        };

        FullscreenSlider.prototype.getActiveIndex = function() {
          return this.el.find('.owl-pagination .owl-page.active').index();
        };

        FullscreenSlider.prototype.alignCaption = function() {
          var caption, caption_height, caption_width, slide, slide_padding, top_offset;
          slide = this.el.find('.owl-item').eq(this.getActiveIndex());
          caption = slide.find('.caption');
          caption.css('visibility', 'hidden');
          caption_height = caption.outerHeight();
          caption_width = caption.outerWidth();
          slide_padding = 30;
          if (PAGE.hasClass('transparent-menu')) {
            top_offset = $('.main-header').outerHeight();
          } else {
            top_offset = 0;
          }
          return slide.find('img').first().imagesLoaded(function() {
            var left_offset, middle_top, slide_height, slide_width;
            slide_height = slide.outerHeight();
            slide_width = slide.outerWidth();
            if (caption.hasClass('top')) {
              caption.css('top', top_offset + slide_padding);
            } else if (caption.hasClass('middle')) {
              middle_top = top_offset + (slide_height - top_offset - caption_height) / 2;
              caption.css('top', middle_top);
            }
            if (caption.hasClass('center')) {
              left_offset = (slide_width - caption_width) / 2;
              caption.css('left', left_offset);
            }
            return caption.css('visibility', 'visible');
          });
        };

        FullscreenSlider.prototype.alignPlayButton = function() {
          var play_button, slide;
          slide = this.el.find('.owl-item').eq(this.getActiveIndex());
          play_button = slide.find('.play-button');
          play_button.css('visibility', 'hidden');
          if (PAGE.hasClass('transparent-menu') && $('.main-header').css('position') === 'absolute') {
            slide.find('img').first().imagesLoaded(function() {
              var header_height, play_button_height, slide_height, video_offset;
              slide_height = slide.outerHeight();
              header_height = $('.main-header').outerHeight();
              play_button_height = play_button.outerHeight();
              video_offset = header_height + (slide_height - header_height - play_button_height) / 2;
              return play_button.css({
                'margin-top': 0,
                'top': video_offset
              });
            });
          } else {
            play_button.css({
              'margin-top': '-40px',
              'top': '50%'
            });
          }
          return play_button.css('visibility', 'visible');
        };

        FullscreenSlider.prototype.eventListeners = function() {
          var slider;
          slider = this;
          this.el.find(".play-button").on('click', function() {
            var video_modal;
            video_modal = new VideoModal($(this).closest('.video'));
            video_modal.open();
            slider.owl.stop();
            return false;
          });
          return this.el.find('.owl-pagination .owl-page').on('click', function() {
            return slider.owl.stop();
          });
        };

        return FullscreenSlider;

      })();
      // fullscreen_slider = new FullscreenSlider($('.slider .slides'), home_slider_auto_enabled, home_slider_rotate_frequency);
      // $('.product-slider').slice(1).css('padding-top', 0);
      // $('.product-slider .product-grid').owlCarousel({
      //   items: 4,
      //   navigation: true,
      //   scrollPerPage: true,
      //   slideSpeed: 800,
      //   lazyLoad: true,
      //   pagination: false,
      //   navigationText: false
      // });
      $('.product-slider .product-item').show();
    }
    smallPromos = function() {
      return $('.small-promos .image-text-widget').mouseenter(function() {
        return $(this).find('.caption').fadeIn(300);
      }).mouseleave(function() {
        return $(this).find('.caption').stop(true, true).fadeOut(300);
      });
    };
    smallPromos();
    if (PAGE.hasClass('template-list-collections')) {
      $('.collection-item').mouseenter(function() {
        return $(this).find('.caption').fadeIn(300);
      }).mouseleave(function() {
        return $(this).find('.caption').stop(true, true).fadeOut(300);
      });
    }
    if (PAGE.hasClass('template-product')) {
      productModal();
      $(".photos").on('click', function() {
        return false;
      });
      large_photos = $('.photos.show-for-medium-up');
      large_thumbs = $('.thumbs.show-for-medium-up');
      small_photos = $('.show-for-small .photos');
      small_thumbs = $('.show-for-small .thumbs');
      // imgZoom = function(index) {
      //   if (Modernizr.touch === false && product_zoom_enabled) {
      //     return large_photos.find('.container').zoom({
      //       url: large_photos.find('.photo').eq(index).attr('data-zoom')
      //     });
      //   }
      // };
      // if (Modernizr.touch === false && product_zoom_enabled) {
      //   large_photos.find('.container').on("mouseover", function() {
      //     $(this).css('outline-width', 1);
      //     return large_photos.find('.zoomImg').css({
      //       opacity: 1
      //     });
      //   }).on("mouseleave", function() {
      //     return $(this).css('outline-width', 0);
      //   });
      // }
      large_photos.find('.photo').first().addClass("active").fadeIn();
      small_photos.find('.photo').first().addClass("active").fadeIn();
      large_thumbs.find('.thumb').first().addClass("active");
      small_thumbs.find('.thumb').first().addClass("active");
      // imgZoom(0);
      large_photos.find('.photo.active').imagesLoaded(function() {
        return large_photos.find('.container').css({
          "max-height": large_photos.find('.photo').eq(0).find('img').naturalHeight(),
          "max-width": large_photos.find('.photo').eq(0).find('img').naturalWidth()
        });
      });
      // large_thumbs.find('.thumb').click(function() {
      //   var index;
      //   index = $(this).index();
      //   large_thumbs.find('.thumb').removeClass('active');
      //   $(this).addClass('active');
      //   large_photos.find('.container').find('.zoomImg').remove();
      //   return large_photos.find('.photo.active').removeClass("active").fadeOut(300, function() {
      //     large_photos.find('.container').css({
      //       "max-height": large_photos.find('.photo').eq(index).find('img').naturalHeight(),
      //       "max-width": large_photos.find('.photo').eq(index).find('img').naturalWidth()
      //     });
      //     return large_photos.find('.photo').eq(index).addClass("active").fadeIn(300, function() {
      //       return imgZoom(index);
      //     });
      //   });
      // });
      small_thumbs.find('.thumb').click(function() {
        var index;
        index = $(this).index();
        small_thumbs.find('.thumb').removeClass('active');
        $(this).addClass('active');
        return small_photos.find('.photo.active').removeClass("active").fadeOut(300, function() {
          small_photos.find('.container').css({
            "max-height": small_photos.find('.photo').eq(index).find('img').naturalHeight(),
            "max-width": small_photos.find('.photo').eq(index).find('img').naturalWidth()
          });
          return small_photos.find('.photo').eq(index).addClass("active").fadeIn(300);
        });
      });
    }
    // if (PAGE.hasClass('template-cart')) {
    //   setTimeout(function() {
    //     Foundation.libs.forms.refresh_custom_select($('#address_country'), true);
    //     return Foundation.libs.forms.refresh_custom_select($('#address_province'), true);
    //   }, 500);
    //   $('#address_country').change(function() {
    //     $('#address_province_container').hide();
    //     return setTimeout(function() {
    //       if ($("#address_province").has('option').length > 0) {
    //         $('#address_province_container').show();
    //         return Foundation.libs.forms.refresh_custom_select($('#address_province'), true);
    //       } else {
    //         return $('#address_province_container').hide();
    //       }
    //     }, 500);
    //   });
    // }
    isFirefox = typeof InstallTrigger !== "undefined";
    if (isFirefox) {
      return $('img').addClass('image-scale-hack');
    }
  });

  return false;

}).call(this);

function number_format(n,c,d,t){
  c = isNaN(c = Math.abs(c)) ? 2 : c;
  d = d == undefined ? "." : d;
  t = t == undefined ? "," : t;
  s = n < 0 ? "-" : "";
  i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "";
  j = (j = i.length) > 3 ? j % 3 : 0;
  return '$ '+ s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}