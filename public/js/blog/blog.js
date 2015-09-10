var self;

var Blog = function() {
  this.init();
};

Blog.prototype = {
  init : function () {
    self = this;
    //this.bindAjaxSubmit();
  },
  bindAjaxSubmit : function () {
    var $form = $('form');
    var paramObj = {};
    $form.submit( function (e) {
      e.preventDefault();
      $.each($form.serializeArray(), function(_, kv) {
        paramObj[kv.name] = kv.value;
      });
      $.ajax({
        url: $form.attr('action'),
        method: 'post',
        data: paramObj,
        dataType: 'html',
        success: function (result) {
          $('.contentWrapper').html(result);
          console.log('result', result);
          self.bindAjaxSubmit();
        }
      });
    });
  }
};

$(document).ready( function () {
  var blog = new Blog();  
});
