var self;

var Blog = function() {
  this.init();
};

Blog.prototype = {
  init : function () {
    self = this;
    this.bindAjaxSubmit();
    //$('.messages.all').transition({
    //  marginTop: 100,
    //  marginLeft: 300,
    //  duration: 2000,
    //  easing: 'in'
    //});
  },
  bindAjaxSubmit : function () {
    var $form = $('form');
    var paramObj = {};
    $form.each( function (index, item) {
      $(this).on('submit', function (e) {
        e.preventDefault();
        $.each($(this).serializeArray(), function(_, kv) {
          paramObj[kv.name] = kv.value;
        });
        paramObj['ajaxCall'] = true;
        var method = $(this).find("input[type=submit]:focus").attr('value');
        paramObj['method'] = method;
        $.ajax({
          url: $(this).attr('action'),
          method: 'POST',
          data: paramObj,
          dataType: 'html',
          success: function (result) {
            $('.contentWrapper').html(result);
            console.log('result', result);
            self.bindAjaxSubmit();
          }
        });
      });
    })
  }
};

$(document).ready( function () {
  var blog = new Blog();  
});
