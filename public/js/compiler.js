let inputEditor = ace.edit("input-data");
let outputEditor = ace.edit("output-data");

$(document).ready(function () {
    // Set themes.
    inputEditor.setTheme("ace/theme/chrome");
    outputEditor.setTheme("ace/theme/chrome");

    // Set additional options.
    outputEditor.setReadOnly(true);
});

/**
 * Submit code.
 */
$('input#compiler-form-submit').click(function () {
    let submitButton = $(this);
    let loader = $('div#compiler-loader');
    let form = $('form[name=compiler-form]');
    let formAction = form.attr('action');
    let formMethod = form.attr('method');

    let _token = $('input[name=_token]').val();
    let language = languageSelector.val();
    let code = codeEditor.getValue();
    let inputData = inputEditor.getValue();

    // Disable button.
    submitButton.attr('disabled', true);

    // Show loader
    loader.attr('hidden', false);

    $.ajax({
        url: formAction,
        type: formMethod,
        data: {
            _token, language, code, inputData
        },
        cache: false,
        success: function (response) {
            if (response.success) {
                let result;
                if (response.isError) {
                    result = 'Compilation Error:\n\n' + response.error;
                } else {
                    result = 'Time: ' + response.time + '\n' +
                        'Memory: ' + response.memory + '\n\n' +
                        'Output:\n\n' + response.output;
                }

                outputEditor.setValue(result);

                outputEditor.clearSelection();
                outputEditor.focus();
            } else {
                let errorCode = response.message;

                toastr.error(compilerTranslations[errorCode], compilerTranslations['error'], {"positionClass": "toast-top-center"});
            }

            // Enable button.
            submitButton.attr('disabled', false);

            // Hide loader
            loader.attr('hidden', true);
        },
        error: function (response) {
            // toastr.error('I do not think that word means what you think it means.', 'Inconceivable!')

            // Enable button.
            submitButton.attr('disabled', false);

            // Hide loader
            loader.attr('hidden', true);
        }
    });
});
