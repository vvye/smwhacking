// Function to execute when secret code is entered - removes "secret" class from all elements belonging to it
function onSecretUnlocked() {
    var elements = document.querySelectorAll('.secret-locked');

    for (var i = 0; i < elements.length; i++) {
        elements[i].classList.add("secret-unlocked");
        elements[i].classList.remove("secret-locked");
    }
}

var enteredCode = "-----------";    // The code we've entered so far
var passCode = "uuddlrlrbas";       // The code to compare against

// The actual function which checks our keypresses
var keyCheckFunction = function (event) {

    // Basically, what we're doing here is build a string which easily lets us compare our input to our pass code
    enteredCode = enteredCode.slice(1, enteredCode.length);

    var keys = {
        38: 'u',
        40: 'd',
        37: 'l',
        39: 'r',
        66: 'b',
        65: 'a',
        13: 's'
    };

    if (keys.hasOwnProperty(event.keyCode)) {
        enteredCode += keys[event.keyCode];
    }
    else {
        enteredCode += '-';
    }

    // Check if we entered the correct pass code, and if so, unlock our secrets
    if (enteredCode === passCode) {
        document.removeEventListener('keydown', keyCheckFunction);
        window.location.href = '#main-menu';
        onSecretUnlocked();
    }
};

// Add the event listener to get us started
document.addEventListener('keydown', keyCheckFunction);