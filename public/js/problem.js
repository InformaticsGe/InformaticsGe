/**
 * Copy problem sample tests.
 */
$('.copy-test').on('click', function () {
   let button = $(this);
   let parent = button.parent();
   let spanWithTest = parent.find('span').first();
   let range = document.createRange();

   range.selectNodeContents(spanWithTest.get(0));

   let sel = window.getSelection();
   sel.removeAllRanges();
   sel.addRange(range);

   document.execCommand('copy');
});

/**
 * Submit problem solution.
 */
$('#problem-solution-form-submit').on('click', function () {
   let submitButton = $(this);
   let loader = $('div#compiler-loader');
   let form = $('form[name=submit-problem-solution-form]');
   let formAction = form.attr('action');
   let formMethod = form.attr('method');

   let _token = $('input[name=_token]').val();
   let language = languageSelector.val();
   let code = codeEditor.getValue();

   // Disable button and Show loader.
   submitButton.attr('disabled', true);
   loader.attr('hidden', false);

   $.ajax({
      url: formAction,
      type: formMethod,
      data: {
         _token, language, code
      },
      cache: false,
   });

   $(location).attr('href', statusPageUrl);
});
