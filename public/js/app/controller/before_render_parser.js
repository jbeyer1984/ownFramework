/**
 * Created by Jens on 13.08.2016.
 */

define('app/controller/before_render_parser',
  [
    'jquery'
  ],
  function ($) {
    var self;
    
    var BeforeRenderParser = function() {
      this.init();
    };

    BeforeRenderParser.prototype = {
      init: function () {
        self = this;
        $(document).ready( function () {
          self.bind();
        })
      },
      bind : function () {
        this.bindBold();
      },
      bindBold : function () {
        $('.click_able').on('selectstart', function (event) {
          event.preventDefault();
        });
        $('.click_able').click( function (e) {
          e.preventDefault();
          if ($(this).hasClass('bold')) {
            $(this).removeClass('bold');
          } else {
            $(this).addClass('bold');
          }
        })
      }
    };

    return BeforeRenderParser;
  });