/**
 * Created by Jens on 30.07.2016.
 */

define('app/controller/parser_move',
  [
    'jquery'
  ],
  function ($) {
    var self;
    
    var Parser = function() {
      this.init();
    };

    Parser.prototype = {
      init: function () {
        self = this;
        $(document).ready( function () {
          self.test();
        })
      },
      test : function () {
        $('.move_able').click( function () {
          var text = $(this).next('.hidden').text().trim();
          $('#input_string').val(text);
        })  
      }  
    };
    
    return Parser;
  });