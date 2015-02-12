$(document).ready(function(){
    /* Login focus */
    $('input[name=email]').focus();
    
    // Date Picker
    $('.datepicker').datepicker();
    $('.datepicker-action').click(function(e){
        e.preventDefault();
        $(this).prev('input').datepicker('show');
    });
    
    /* User actions */
    $('#reset_password').click(function(){
        if ($(this).is(':checked')) {
            $('#password').removeAttr('disabled');
        } else {
            $('#password').attr('disabled', true);
        }   
    });
    
    $('.remove-item-action').click(function(){
        var $modal = $('#removeModal');
        var html = $modal.html();
        html = html.replace('{url}', $(this).attr('href'));
        $modal.html(html).modal('show');
        
        return false;
    });
    
    var profile_links = $('#profile_links');
    if(profile_links[0]){
        $('.add_link', profile_links).live('click', function(e){
            e.preventDefault();
            var $icon = $(this).find('i');
            
            if ($icon.hasClass('icon-plus')) {
                var add = $('<div>' + $('div', profile_links).last().html() + '</div>').hide();
                profile_links.append(add);
                add.find('input').val('');
                add.fadeIn();
                $icon.removeClass('icon-plus').addClass('icon-minus');
            } else {
                $(this).parent().fadeOut().remove();
            }   
        });
    }
    
    var profile_photo = $('#profile_photo');
    if(profile_photo[0]){
        $('img', profile_photo).click(function(){
            $(this).parent().next().click();
        });
    }
    
    $('.view-profile-details').live('click', function(){
        var href = $(this).attr('href');
        
        $.get(href, function(d){
            var $modal = $(d);
            
            $('body').append($modal);
            $modal.modal('show');

            $modal.on('hidden', function(){
                $modal.remove();
            });
        });
        
        return false;
    });
    
    /* General actions */
    $('#show-hide-users').click(function(){
        var targetId = $(this).attr('target-id');
        if($(this).hasClass('expand')){
            $(this).removeClass('expand').addClass('collapse');
            $('#' + targetId).show('slow');
        } else {
            $(this).removeClass('collapse').addClass('expand');
            $('#' + targetId).hide('slow');
        }
    });
    
    /* Dashboard actions*/
    /*$('#switch-project-view').click(function(){
        if($(this).hasClass('global-tasks')) {
            $('div.project-task').show('slow');
            $(this).attr('title', 'Show mine');
            $(this).removeClass('global-tasks').addClass('user-tasks');
        } else {
            $('div.project-task').hide('slow');
            $(this).attr('title', 'Show all');
            $(this).removeClass('user-tasks').addClass('global-tasks');
        }
        return false;
    });*/
    
    /* Reverse Dashboard actions*/
    $('#switch-project-view').click(function(){
        if($(this).hasClass('global-tasks')) {
            $('div.project-task').show('slow');
            $(this).attr('title', 'Show all');
            $(this).removeClass('global-tasks').addClass('user-tasks');
        } else {
            $('div.project-task').hide('slow');
            $(this).attr('title', 'Show mine');
            $(this).removeClass('user-tasks').addClass('global-tasks');
        }
        return false;
    });
    
    /* Task actios */
     $('a.task_time_control').live('click', function(){
       var element = $(this);
       var duration = element.next();
       
       $.get(element.attr('href'),
           function(data){
               if(data.result == 1){
                   if(element.hasClass('stop')) {
                       element.attr('title', 'Continue');
                       element.attr('href', data.new_action);
                       element.removeClass('stop').addClass('play');
                       duration.html(data.duration);
                   } else {
                       element.attr('title', 'Stop');
                       element.attr('href', data.new_action);
                       element.removeClass('play').addClass('stop');
                       duration.html(data.duration + ' - running');
                   }
               }
           },
           'json'
       );
           
       return false;
   });
   
   $('#task-history-details').click(function(){
        var href = $(this).attr('href');
        
        $.get(href, function(d){
            var $modal = $(d);
            
            $('body').append($modal);
            $modal.modal('show');

            $modal.on('hidden', function(){
                $modal.remove();
            });
        });
        
        return false;
   });
   
    $('.get-comment-action').click(function(){
        var href = $(this).attr('href').split('/');
        href = CI.base_url + 'task/ajax_comment/' + href[href.length - 3] + '/' + href[href.length - 2] + '/' + href[href.length - 1];
        
        $.get(href, function(d){
            var $modal = $(d);

            $('body').append($modal);
            $modal.modal('show');

            $modal.on('hidden', function(){
                $modal.remove();
            });
        });
        
        return false;
    });
    
    $('#task-comment-submit').live('click', function(){
        var $form = $('#task-comment-modal-form');
        
        $.post($form.attr('action'),
            $form.serialize(),
            function(d){
                window.location = d;
            }
        );
    });

    // Forms
    tinyMCE.init({
            mode : "textareas",
            theme : "modern"
    });
 });
 







/*
                       _ _ _____                      _   _
                      | | |  __ \                    | | (_)
    ___  ___ _ __ ___ | | | |__) |_____   _____  __ _| |  _ ___
   / __|/ __| '__/ _ \| | |  _  // _ \ \ / / _ \/ _` | | | / __|
   \__ \ (__| | | (_) | | | | \ \  __/\ V /  __/ (_| | |_| \__ \
   |___/\___|_|  \___/|_|_|_|  \_\___| \_/ \___|\__,_|_(_) |___/
                                                        _/ |
                                                       |__/

    "Declarative on-scroll reveal animations."

/*=============================================================================

    scrollReveal.js is inspired by cbpScroller.js, © 2014, Codrops.

    Licensed under the MIT license.
    http://www.opensource.org/licenses/mit-license.php

    scrollReveal.js, © 2014 https://twitter.com/julianlloyd

=============================================================================*/

;(function (window) {

  'use strict';

  var docElem = window.document.documentElement;

  function getViewportH () {
    var client = docElem['clientHeight'],
      inner = window['innerHeight'];

    return (client < inner) ? inner : client;
  }

  function getOffset (el) {
    var offsetTop = 0,
        offsetLeft = 0;

    do {
      if (!isNaN(el.offsetTop)) {
        offsetTop += el.offsetTop;
      }
      if (!isNaN(el.offsetLeft)) {
        offsetLeft += el.offsetLeft;
      }
    } while (el = el.offsetParent)

    return {
      top: offsetTop,
      left: offsetLeft
    }
  }

  function isElementInViewport (el, h) {
    var scrolled = window.pageYOffset,
        viewed = scrolled + getViewportH(),
        elH = el.offsetHeight,
        elTop = getOffset(el).top,
        elBottom = elTop + elH,
        h = h || 0;

    return (elTop + elH * h) <= viewed && (elBottom) >= scrolled;
  }

  function extend (a, b) {
    for (var key in b) {
      if (b.hasOwnProperty(key)) {
        a[key] = b[key];
      }
    }
    return a;
  }


  function scrollReveal(options) {
      this.options = extend(this.defaults, options);
      this._init();
  }



  scrollReveal.prototype = {
    defaults: {
      axis: 'y',
      distance: '25px',
      duration: '0.66s',
      delay: '0s',

  //  if 0, the element is considered in the viewport as soon as it enters
  //  if 1, the element is considered in the viewport when it's fully visible
      viewportFactor: 0.33
    },

    /*=============================================================================*/

    _init: function () {

      var self = this;

      this.elems = Array.prototype.slice.call(docElem.querySelectorAll('[data-scrollReveal]'));
      this.scrolled = false;

  //  Initialize all scrollreveals, triggering all
  //  reveals on visible elements.
      this.elems.forEach(function (el, i) {
        self.animate(el);
      });

      var scrollHandler = function () {
        if (!self.scrolled) {
          self.scrolled = true;
          setTimeout(function () {
            self._scrollPage();
          }, 60);
        }
      };

      var resizeHandler = function () {
        function delayed() {
          self._scrollPage();
          self.resizeTimeout = null;
        }
        if (self.resizeTimeout) {
          clearTimeout(self.resizeTimeout);
        }
        self.resizeTimeout = setTimeout(delayed, 200);
      };

      window.addEventListener('scroll', scrollHandler, false);
      window.addEventListener('resize', resizeHandler, false);
    },

    /*=============================================================================*/

    _scrollPage: function () {
        var self = this;

        this.elems.forEach(function (el, i) {
            if (isElementInViewport(el, self.options.viewportFactor)) {
                self.animate(el);
            }
        });
        this.scrolled = false;
    },

    /*=============================================================================*/

    parseLanguage: function (el) {

  //  Splits on a sequence of one or more commas or spaces.
      var words = el.getAttribute('data-scrollreveal').split(/[, ]+/),
          enterFrom,
          parsed = {};

      function filter (words) {
        var ret = [],

            blacklist = [
              "from",
              "the",
              "and",
              "then",
              "but"
            ];

        words.forEach(function (word, i) {
          if (blacklist.indexOf(word) > -1) {
            return;
          }
          ret.push(word);
        });

        return ret;
      }

      words = filter(words);

      words.forEach(function (word, i) {

        switch (word) {
          case "enter":
            enterFrom = words[i + 1];

            if (enterFrom == "top" || enterFrom == "bottom") {
              parsed.axis = "y";
            }

            if (enterFrom == "left" || enterFrom == "right") {
              parsed.axis = "x";
            }

            return;

          case "after":
            parsed.delay = words[i + 1];
            return;

          case "wait":
            parsed.delay = words[i + 1];
            return;

          case "move":
            parsed.distance = words[i + 1];
            return;

          case "over":
            parsed.duration = words[i + 1];
            return;

          case "trigger":
            parsed.eventName = words[i + 1];
            return;

          default:
        //  Unrecognizable words; do nothing.
            return;
        }
      });

  //  After all values are parsed, let’s make sure our our
  //  pixel distance is negative for top and left entrances.
  //
  //  ie. "move 25px from top" starts at 'top: -25px' in CSS.

      if (enterFrom == "top" || enterFrom == "left") {

        if (!typeof parsed.distance == "undefined") {
          parsed.distance = "-" + parsed.distance;
        }

        else {
          parsed.distance = "-" + this.options.distance;
        }

      }

      return parsed;
    },

    /*=============================================================================*/

    genCSS: function (el) {
      var parsed = this.parseLanguage(el);

      var dist   = parsed.distance || this.options.distance,
          dur    = parsed.duration || this.options.duration,
          delay  = parsed.delay    || this.options.delay,
          axis   = parsed.axis     || this.options.axis;

      var transition = "-webkit-transition: all " + dur + " ease " + delay + ";" +
                          "-moz-transition: all " + dur + " ease " + delay + ";" +
                            "-o-transition: all " + dur + " ease " + delay + ";" +
                               "transition: all " + dur + " ease " + delay + ";";

      var initial = "-webkit-transform: translate" + axis + "(" + dist + ");" +
                       "-moz-transform: translate" + axis + "(" + dist + ");" +
                            "transform: translate" + axis + "(" + dist + ");" +
                              "opacity: 0;";

      var target = "-webkit-transform: translate" + axis + "(0);" +
                      "-moz-transform: translate" + axis + "(0);" +
                           "transform: translate" + axis + "(0);" +
                             "opacity: 1;";
      return {
        transition: transition,
        initial: initial,
        target: target,
        totalDuration: ((parseFloat(dur) + parseFloat(delay)) * 1000)
      };
    },

    /*=============================================================================*/

    animate: function (el) {
      var css = this.genCSS(el);

      if (!el.getAttribute('data-sr-init')) {
        el.setAttribute('style', css.initial);
        el.setAttribute('data-sr-init', true);
      }

      if (el.getAttribute('data-sr-complete')) {
        return;
      }

      if (isElementInViewport(el, this.options.viewportFactor)) {
        el.setAttribute('style', css.target + css.transition);

        setTimeout(function () {
          el.removeAttribute('style');
          el.setAttribute('data-sr-complete', true);
        }, css.totalDuration);
      }

    }
  }; // end scrollReveal.prototype

  document.addEventListener("DOMContentLoaded", function (evt) {
    window.scrollReveal = new scrollReveal();
  });

})(window);