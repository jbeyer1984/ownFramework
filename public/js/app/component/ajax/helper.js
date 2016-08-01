/**
 * Created by Jens on 30.07.2016.
 */

define('app/component/ajax/helper', [
  'jquery',
], function($) {
  var self;

  var AjaxHelper = function() {
    console.log("hallo welt");
    this.init();
  };

  AjaxHelper.prototype = {
    successor: {},
    $input: {},
    init : function () {
      self = this;
    },
    bindSuccessor : function (successor) {
      this.successor = successor
    },
    bindInput: function ($input) {
      this.$input = $input;
    },
    successBind : function ($attribute) {
      this.$attributeToSuccess = $attribute; 
    },
    success : function (result) {
      this.successor.$updateElement.html(result);
    },
    bindAjaxSubmit : function () {
      var self = this;

      var paramObj = {};
      self.successor.$formToBind.each( function (index, item) {
        $(this).on('submit', function (e) {
          e.preventDefault();
          $.each($(this).serializeArray(), function(_, kv) {
            paramObj[kv.name] = kv.value;
          });
          paramObj['ajaxCall'] = true;
          $.ajax({
            url: $(this).attr('action'),
            // method: 'post',
            type: 'post',
            data: paramObj,
            // dataType: 'html',
            success: function (result) {
              self.success(result)
            },
            error: function (xhr, ajaxOptions, thrownError) {
              alert("ERROR:" + xhr.responseText+" - "+thrownError);
            }
          });
        });
      })
    }
  };

  return AjaxHelper;
});
