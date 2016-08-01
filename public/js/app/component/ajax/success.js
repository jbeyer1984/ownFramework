/**
 * Created by Jens on 30.07.2016.
 */

define('app/component/ajax/success', function() {
  var self;

  var Success = function() {
    this.init();
  };

  Success.prototype = {
    $formToBind : {},
    $updateElement: {},
    init : function () {
      self = this;
    },
    bindForm : function ($form) {
      this.$formToBind = $form; 
    },
    bindUpdateArea : function ($updateElement) {
      this.$updateElement.html(result);
    }
  };

  return Success;
});
