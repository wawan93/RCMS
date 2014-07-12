/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.0
 *
 * RCMS JS Core
 */

var
  rcms = {},
  app = {},
  lang = {};

lang.core = {};
lang.app = {};

/**
 * Show loading
 * @param act
 */
rcms.loading = function(act) {
  if (act) {
    var
      left = ($(window).width()-$('#cms_loading-layer').width())/2,
      top = ($(window).height()-$('#cms_loading-layer').height())/2;

      $('#cms_loading-layer')
       .text(lang.core.loadingLayer)
       .css({left: left + 'px', top: top + 'px', position: 'fixed', zIndex: 10000})
       .fadeIn(250);
    } else
      $('#cms_loading-layer').fadeOut(250);
};

/**
 * Alert
 *
 * @param type
 * @param message
 */
rcms.alert = function(type, message) {
  $('<div class="cms-notice ' + type + '" onclick="$(this).fadeOut(300);">' + message + '</div>')
    .appendTo('#cms_alert')
    .hide()
    .fadeIn(300)
    .animate({
      queue: false
    }, 5000, function() {
      $(this).fadeOut(300);
    });

  setTimeout(function() {
      $(this).fadeOut(300);
  }, 3000);
};

/**
 * AJAX request
 *
 * @param url
 * @param data
 * @param callback
 */
rcms.ajax = function(url, data, callback) {
  rcms.loading(1);

  $.ajax({
    url: url,
    type: 'POST',
    data: data,
    dataType: 'json',
    success: callback,
    error: function(info) {
      rcms.alert('danger', lang.core.unknownError);
      console.error(info.responseText);
    },
    complete: function() {
      rcms.loading(0);
    }
  });
};