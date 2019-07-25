const compilerLanguages = {
    'cpp': {
        'title': 'C++',
        'mode': 'c_cpp',
        'code': '#include <iostream>\nusing namespace std;\n\nint main() \n{\n\tcout << "Hello, World!";\n\treturn 0;\n}'
    },
    'c_sharp': {
        'title': 'C#',
        'mode': 'csharp',
        'code': 'using System;\nclass HelloWorld {\n\tstatic void Main() {\n\t\tConsole.WriteLine("Hello, World!");\n\t}\n}'
    },
    'python2': {
        'title': 'Python 2',
        'mode': 'python',
        'code': 'print(\'Hello, World!\')'
    },
    'python3': {
        'title': 'Python 3',
        'mode': 'python',
        'code': 'print(\'Hello, World!\')'
    },
    'java': {
        'title': 'Java',
        'mode': 'java',
        'code': 'public class Main {\n\tpublic static void main(String[] args) {\n\t\tSystem.out.println("Hello, World!");\n\t}\n}'
    },
    'php': {
        'title': 'PHP',
        'mode': 'php',
        'code': '<?php\n\techo "Hello, World!";'
    },
    'js': {
        'title': 'JavaScript',
        'mode': 'javascript',
        'code': 'console.log(\'Hello, World!\');'
    },
    'free_pascal': {
        'title': 'Free Pascal',
        'mode': 'pascal',
        'code': 'program Hello;\nbegin\n\twriteln (\'Hello, World!\');\nend.'
    }
};

let codeEditor = ace.edit("code-editor");
let inputEditor = ace.edit("input-data");
let outputEditor = ace.edit("output-data");
const languageSelector = $('select[name=compiler-language]');

$(document).ready(function () {
    // Create language selector.
    for (let language in compilerLanguages) {
        let option = new Option(compilerLanguages[language]['title'], language);
        languageSelector.append(option);
    }

    // Set themes.
    codeEditor.setTheme("ace/theme/chrome");
    inputEditor.setTheme("ace/theme/chrome");
    outputEditor.setTheme("ace/theme/chrome");

    // Set default values
    codeEditor.session.setMode("ace/mode/c_cpp");
    codeEditor.setValue(compilerLanguages['cpp']['code']);

    // Set additional options.
    codeEditor.clearSelection();
    outputEditor.setReadOnly(true);
});

/**
 * Set editor mode for selected language.
 */
languageSelector.change(function () {
    let mode = compilerLanguages[this.value]['mode'];
    let text = compilerLanguages[this.value]['code'];

    codeEditor.setValue(text);
    codeEditor.clearSelection();
    codeEditor.session.setMode("ace/mode/" + mode);
});

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
                outputEditor.setValue(
                    'language: ' + response.language + ', \n\n' +
                    'code: \n\n' + response.code + '\n\n' +
                    'inputData: \n\n' + response.inputData
                );

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
