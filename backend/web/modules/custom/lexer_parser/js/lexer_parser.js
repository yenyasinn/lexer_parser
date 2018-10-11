(function ($, Drupal) {
  Drupal.behaviors.LexerParserMathParser = {
    attach: function attach(context, settings) {
      $('.lexer-parser--math-parser--field-wrapper').hover(function(){
        $(this).children('.lexer-parser--math-parser--expression').addClass('visually-hidden');
        $(this).children('.lexer-parser--math-parser--result').removeClass('visually-hidden');
      },
      function() {
        $(this).children('.lexer-parser--math-parser--expression').removeClass('visually-hidden');
        $(this).children('.lexer-parser--math-parser--result').addClass('visually-hidden');
      })
    }
  }
})(jQuery, Drupal);
