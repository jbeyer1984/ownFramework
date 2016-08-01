var self;

var Blog = function() {
  this.init();
};

Blog.prototype = {
  init : function () {
    self = this;
    this.bindAjaxSubmit();
    //this.bindCopyContent();
    this.bindMarkContent();
    //$('.messages.all').transition({
    //  marginTop: 100,
    //  marginLeft: 300,
    //  duration: 2000,
    //  easing: 'in'
    //});
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
        $input.trigger('submit');
      }
    });
  },
  bindAjaxSubmit : function () {
    this.bindAjaxKeyPressSubmit();
    
    var $form = $('form');
    var paramObj = {};
    $form.each( function (index, item) {
      $(this).on('submit', function (e) {
        e.preventDefault();
        $.each($(this).serializeArray(), function(_, kv) {
          paramObj[kv.name] = kv.value;
        });
        paramObj['ajaxCall'] = true;
        $.ajax({
          url: $(this).attr('action'),
          method: 'post',
          data: paramObj,
          dataType: 'html',
          success: function (result) {
            $('.contentWrapper').html(result);
            // console.log('result', result);
            var lastCopyButton = $('input.copy-content').last();
            self.bindAjaxSubmit();
            lastCopyButton.click(function () { //@todo should be changed
              self.markCopyContent(lastCopyButton);  
            });
          }
        });
      });
    })
  },
  bindCopyContent : function () {
    $('input.copy-content').on('click', function () {
      self.copyContentFromElement($(this));
    });
  },
  copyContentFromElement : function (el)  {
    var $temp = $("<input>");
    $("body").append($temp);
    var item = $(el).parent().next();
    $temp.val(item.text()).select();
    document.execCommand("copy");
    $temp.remove();
  },
  bindMarkContent : function () {
    $('input.copy-content').on('click', function () {
      self.markCopyContent($(this));
    });
  },
  markCopyContent : function (el) {
    var item = $(el).parent().next();
    self.selectElementText(item[0]); // this is not needed at mom but useful
  },
  // Selects text inside an element node.
  selectElementText : function (el) {
    self.removeTextSelections();
    if (document.selection) {
      var range = document.body.createTextRange();
      range.moveToElementText(el);
      range.select();
    }
    else if (window.getSelection) {
      var range = document.createRange();
      range.selectNode(el);
      window.getSelection().addRange(range);
    }
  },
  // Deselects all text in the page.
  removeTextSelections : function () {
    if (document.selection) document.selection.empty();
    else if (window.getSelection) window.getSelection().removeAllRanges();
  }
};

$(document).ready( function () {
  var blog = new Blog();  
});
