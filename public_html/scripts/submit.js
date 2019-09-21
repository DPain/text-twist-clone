function postData(url = '', data = {}) {
  // Default options are marked with *
  return fetch(url, {
      method: 'POST', // *GET, POST, PUT, DELETE, etc.
      mode: 'cors', // no-cors, cors, *same-origin
      cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
      credentials: 'same-origin', // include, *same-origin, omit
      headers: {
        'Content-Type': 'application/json'
      },
      redirect: 'follow', // manual, *follow, error
      referrer: 'no-referrer', // no-referrer, *client
      body: JSON.stringify(data), // body data type must match "Content-Type" header
    })
    .then(response => response.json()); // parses JSON response into native JavaScript objects 
}

function submitWord() {
  if (input.value) {
    // #TODO: Implement.
    // Example POST method implementation:

    postData('/api/submit.php', {
        word: input.value.toUpperCase()
      })
      .then((data) => {
        if (data['status'] == "RIGHT") {
          addToSet(data['word'].toUpperCase());
        }
        else {
          snackbar.labelText = "Incorrect!";
        }
      }) // JSON-string from `response.json()` call
      .catch((error) => {
        console.log(error);
      });
    input.value = "";
  }
  else {
    snackbar.labelText = "Please type a word!";
    snackbar.open();
  }
};

function addToSet(word) {
  let word_set = document.querySelector('#solved-word-list').getElementsByTagName('li');
  let add = true;
  for (let i = 0; i < word_set.length; i++) {
    console.log(word_set.item(i).innerHTML);
    console.log(word);
    if (word_set.item(i).innerHTML == word) {
      add = false;
      break;
    }
  }

  if (add) {
    let entry = document.createElement("li");
    entry.className = "mdc-list-item";
    entry.innerHTML = word;
    document.querySelector('#solved-word-list').appendChild(entry);
    snackbar.labelText = "Added!";
  }
  else {
    snackbar.labelText = "Already added!";
  }
  snackbar.open();
}

input.addEventListener('keyup', function(event) {
  if (event.keyCode === 13) {
    submitWord();
  }
});


enterBtn.addEventListener("click", submitWord, false);
