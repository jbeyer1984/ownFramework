requirejs.config({
  baseUrl: '/public/js',
  //waitSeconds: 15,
  paths: {
    jquery: 'lib/jquery',
    jsface: 'lib/jsface',
    app: 'app'
  }
});

define("main", function (require) {
  // var $ = require('lib/jquery');
  var Parser = require('app/controller/parser'); 
  var parser = new Parser();

  var ParserMove = require('app/controller/parser_move');
  var parserMove = new ParserMove();

  var BeforeRenderParser = require('app/controller/before_render_parser');
  var beforeRenderParser = new BeforeRenderParser();
});