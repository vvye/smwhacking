function fileInput(className, options) {
    options = options || {};
    var buttonText = options.buttonText || 'Browse&hellip;';
    var noFileText = options.noFileText || 'No file selected.';
    var multiFilesText = options.multiFilesText || '{num} files selected.';
    var noFilesText = options.noFilesText || 'No files selected.';

    var inputs = document.getElementsByClassName(className);
    for (var i = 0; i < inputs.length; i++) {
        var input = inputs[i];

        if (input.tagName.toLowerCase() !== 'input' || input.type.toLowerCase() !== 'file') {
            continue;
        }

        input.style.position = 'absolute';
        input.style.visibility = 'hidden';

        var label = document.createElement('label');
        label.className = input.className;
        input.removeAttribute('class');

        var browseButton = document.createElement('a');
        browseButton.className = 'button';
        browseButton.innerHTML = buttonText;

        var fileNameBox = document.createElement('span');

        (function updateFileName(fileNameBox, input) {
            (input.onchange = function () {
                var multiple = input.hasAttribute('multiple');
                var numFiles = input.files.length;
                if ((multiple && numFiles > 1)) {
                    fileNameBox.innerHTML = multiFilesText.replace('{num}', '' + numFiles);
                } else {
                    var fileName = (input.value || '').split(/[\\/]/).pop();
                    if (multiple) {
                        fileNameBox.innerHTML = fileName || noFilesText;
                    } else {
                        fileNameBox.innerHTML = fileName || noFileText;
                    }
                }
            })();
        })(fileNameBox, input);

        input.parentNode.replaceChild(label, input);
        label.appendChild(input);
        label.appendChild(browseButton);
        label.appendChild(fileNameBox);
    }
}