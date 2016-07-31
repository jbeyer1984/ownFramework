/**
 * Created by Jens on 30.07.2016.
 */

define('app/controller/parser', 
  [
    'jquery',
    'app/component/ajax/helper',
    'app/component/ajax/success'
  ],
  function($, AjaxHelper, Success) {
  var self;
  
  console.log('außer der Reihe');

  var Parser = function() {
    this.init();
  };

  Parser.prototype = {
    $input: {},
    init : function () {
      self = this;
      
      var successor = new Success();
      successor.$formToBind = $('form.parser.input');
      this.$input = successor.$formToBind;
      successor.$updateElement = $('.parser.output');
      var ajaxHelper = new AjaxHelper();
      ajaxHelper.bindSuccessor(successor);
      ajaxHelper.bindAjaxSubmit();
      
      this.bindAjaxKeyPressSubmit();
      console.log('ajaxHelper', ajaxHelper);
    },
    bindAjaxKeyPressSubmit : function() {
      var self = this;

      var pressedKeyTimes = 0;
      var $input = $('input');
      var isAltDown = false;
      var isCombinationAltS = false;
      $('body').on('keydown', function (e) {
        isCombinationAltS = isAltDown && ('s' == e.key);

        isAltDown = ('Alt' == e.key);

        if (isCombinationAltS) {
          self.$input.trigger('submit');
        }
      });
    }
  };

  return Parser;
});
