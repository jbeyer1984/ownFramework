var self;

var Blog = function() {
  this.init();
};

Blog.prototype = {
  init : function () {
    
    var $form = $('form');
    var paramObj = {};
    
    $form.submit( function (e) {
      e.preventDefault();
      $.each($form.serializeArray(), function(_, kv) {
        paramObj[kv.name] = kv.value;
      });
      $.ajax({
        url: '/index.php/task1/show',
        method: 'post',
        data: paramObj,
        dataType: 'html',
        success: function (result) {
          $('body').html(result);
          console.log('result', result);
        }
      });
    });
    //console.log($form.serializeArray());
  }
};

$(document).ready( function () {
  var blog = new Blog();  
});
