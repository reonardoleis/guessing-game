const apiURL = `${window.location.href.split('/')[0]}/guessing-game/api`;
const maxAttempts = 6;
var attempts = 0;

var currentRow = 0;

function setLetterSquareListeners() {
    for (let i = 0; i < 6; i++) {
        for (let j = 1; j <= 5; j++) {
            const id = `letter-${i}-${j}`;
            const element = document.getElementById(id);
            element.addEventListener('keyup', (e) => {
                if (j > 1) {
                    if (e.keyCode == 8) {
                        const previousId = `letter-${i}-${j - 1}`;
                        const previousElement = document.getElementById(previousId);
                        element.blur();
                        previousElement.focus();
                    }
                }

                if (j < 5) {
                    if (e.keyCode != 8) {
                        const nextId = `letter-${i}-${j + 1}`;
                        const nextElement = document.getElementById(nextId);
                        element.blur();
                        nextElement.focus();
                    }
                }
            });
        }
    }
}

setLetterSquareListeners();

function setActiveRow(rowNumber) {
    if (rowNumber > 5) {
        return;
    }
    const baseId = `letter-${rowNumber}-`;
    for (let i = 1; i <= 5; i++) {
        const id = baseId + i;
        const element = document.getElementById(id);
        element.classList.add('active');
        element.removeAttribute('disabled');
    }

    for (let i = 0; i < 6; i++) {
        if (i == rowNumber) {
            continue;
        }

        const baseId = `letter-${i}-`;
        for (let j = 1; j <= 5; j++) {
            const id = baseId + j;
            const element = document.getElementById(id);
            element.classList.remove('active');
            element.setAttribute('disabled', 'disabled');
        }
    }
}

function handleLettersResult(correctLetters) {
    for (let i = 0; i < 5; i++) {
        const id = `letter-${currentRow}-${i+1}`;
        const val = document.getElementById(id).value;
        if (correctLetters[i].position) {
            document.getElementById(id).classList.add('correct');
        } else if (correctLetters[i].exists) {
            document.getElementById(id).classList.add('partial');
        }
    }
}

async function attempt() {
    const baseId = `letter-${currentRow}-`;
    let attempt = '';
    for (let i = 1; i <= 5; i++) {
        const id = baseId + i;
        const element = document.getElementById(id);
        attempt += element.value;
    }

    attemp = attempt.toLowerCase();
    while (attempt.length < 5) {
        attempt += ' ';
    }

    let url = `${apiURL}/words.php`;

    const req = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ word: attempt })
    });

    const res = await req.json();

    
    handleLettersResult(res.correct_letters);

    attempts++;
    currentRow++;


    if (res.is_correct) {
        const wordID = await getCurrentWordID();
        localStorage.setItem("result", "win");
        localStorage.setItem("word_id", wordID);
        document.getElementsByClassName("game")[0].style.display = "none";
        document.getElementById("win").style.display = "block";
    } else if(attempts >= maxAttempts) {
        const wordID = await getCurrentWordID();
        localStorage.setItem("result", "lose");
        localStorage.setItem("word_id", wordID);
        document.getElementsByClassName("game")[0].style.display = "none";
        document.getElementById("lose").style.display = "block";
    }

    


    setActiveRow(currentRow);
}

function getTimeToNextWord() {
    let now = new Date();
    let now_utc = Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), now.getUTCHours(), now.getUTCMinutes(), now.getUTCSeconds());
    let diff = nextWordTimestamp - now_utc;
    let hours = Math.floor(diff / 3600000);
    let minutes = Math.floor((diff % 3600000) / 60000);
    let seconds = Math.floor((diff % 60000) / 1000);
    return `${hours} hours, ${minutes} minutes and ${seconds} seconds`;
}

setInterval(() => {
    document.getElementById('next-word').innerText = 'Next word in ' + getTimeToNextWord();
}, 1000);

if (localStorage.getItem("result")) {
    handleDailyWord();
}

async function getCurrentWordID() {
    const url = `${apiURL}/words.php`;
    const req = await fetch(url, {
        method: 'GET'
    });

    const res = await req.json();
    return res.id;
}

async function handleDailyWord() {
    const wordID = await getCurrentWordID();
    const savedWordID = localStorage.getItem("word_id");
    if (wordID != savedWordID) {
        localStorage.removeItem("result");
        localStorage.removeItem("word_id");
    } else {
        if (localStorage.getItem("result") == "win") {
            document.getElementsByClassName("game")[0].style.display = "none";
            document.getElementById("win").style.display = "block";
        } else if (localStorage.getItem("result") == "lose") {
            document.getElementsByClassName("game")[0].style.display = "none";
            document.getElementById("lose").style.display = "block";
        }
    }
}
