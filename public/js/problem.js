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
