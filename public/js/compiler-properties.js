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
    'node_js': {
        'title': 'Node.js',
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
const languageSelector = $('select[name=compiler-language]');

$(document).ready(function () {
    // Create language selector.
    for (let language in compilerLanguages) {
        let option = new Option(compilerLanguages[language]['title'], language);
        languageSelector.append(option);
    }

    // Set theme.
    codeEditor.setTheme("ace/theme/chrome");

    // Set default values.
    codeEditor.session.setMode("ace/mode/c_cpp");
    codeEditor.setValue(compilerLanguages['cpp']['code']);

    // Set additional options.
    codeEditor.clearSelection();
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

