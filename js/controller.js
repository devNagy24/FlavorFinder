const appRoot = '/328/FlavorFinder';


document.querySelector('.pagination').addEventListener('click', function(event) {
    // Verifying that the anchor tag is clicked
    if (event.target.tagName === 'A') {
        // Preventing reloading of page
        event.preventDefault();

        // Looking for href link of "?page=NUMBER"
        let page = parseInt(event.target.getAttribute('data-page'));
        loadRecipes(page);
    }
});

document.querySelector('.nav-item.active .home-link').addEventListener('click', function(event) {
    event.preventDefault();
    // Send a request to the F3 PHP controller to clear cookies
    fetch(appRoot + '/clear-cookies', {
        method: 'GET'
    })
        .then(response => {
            // Clear the search input value
            document.querySelector('#searchInput').value = '';
            // Set the page to 1
            let page = 1;
            // Call the loadRecipes function with the page set to 1 and an empty keyword
            loadRecipes(page, '');

            // Remove the query string from the URL
            window.history.replaceState({}, "", window.location.pathname + window.location.hash);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});


document.querySelector('.home-link').addEventListener('click', function(event) {
    event.preventDefault();
    // Send a request to the F3 PHP controller to clear cookies
    fetch(appRoot + '/clear-cookies', {
        method: 'GET'
    })
        .then(response => {
            // Clear the search input value
            document.querySelector('#searchInput').value = '';
            // Set the page to 1
            let page = 1;
            // Call the loadRecipes function with the page set to 1 and an empty keyword
            loadRecipes(page, '');

            // Removes the page number from the url
            // otherwise when a user starts a fresh search, it will be loaded to the page
            // specified in the url
            window.history.replaceState({}, "", window.location.pathname + window.location.hash);

        })
        .catch((error) => {
            console.error('Error:', error);
        });
});

function loadRecipes(page) {
    let searchKeyword = document.querySelector('#searchInput').value;
    console.log("Page before fetch:", page);
    fetch(appRoot + `?page=${page}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ keyword: searchKeyword.trim() }) // Trim the searchKeyword and only send if not empty
    })
        .then(response => {
            console.log("Response:", response);
            return response.text(); // Assuming the response is full HTML
        })
        .then(data => {
            console.log("Data:", data);
            // Create a DOMParser to parse the HTML string
            const parser = new DOMParser();
            const htmlDocument = parser.parseFromString(data, "text/html");
            // Select the row with the recipe cards from the parsed HTML
            const newRow = htmlDocument.querySelector('.row-cols-1');
            // Replace the old row with the new one
            document.querySelector('.row-cols-1').innerHTML = newRow.innerHTML;
            // Update the active page indicator
            updateActivePage(page);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

function updateActivePage(page) {
    // Get all pagination buttons
    let paginationButtons = document.querySelectorAll('.pagination a');

    // Loop through the pagination buttons and update the active indicator
    paginationButtons.forEach(button => {
        if (parseInt(button.getAttribute('data-page')) === page) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });
}

// Load the initial recipes on page load
window.addEventListener('DOMContentLoaded', function() {
    let urlParams = new URLSearchParams(window.location.search);
    let initialPage = parseInt(urlParams.get('page')) || 1;
    loadRecipes(initialPage);
});


// Get Sign Up Data

document.getElementById('signUpSubmit').addEventListener('click', function(event) {
    console.log("clicked");
    event.preventDefault();
    let form = document.getElementById('signupForm');

    if(form.checkValidity()) {
        let username = document.getElementById('signUpUsername').value;
        let email = document.getElementById('signUpEmail').value;
        let password = document.getElementById('signUpPassword').value;
        let confirmPassword = document.getElementById('signUpPasswordConfirm').value;

        console.log(username);
        console.log(email);

        if(password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }

        let payload = {
            username: username,
            email: email,
            password: password
        };
        console.log(payload);
        JSON.stringify(payload);
        fetch(appRoot + '/sign-up', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        }).then(response => response.json())
            .then(data => {
                console.log(data);
                if(data.success) {
                    // Close the signup model
                    let modal = document.getElementById('signUpModal');
                    let bootstrapModal = bootstrap.Modal.getInstance(modal);
                    bootstrapModal.hide();

                    // Show the toast here when the signup is successful
                    let toast = new bootstrap.Toast(document.getElementById('toast'));
                    toast.show();

                    // Add "Welcome, username" to the navbar
                    let welcomeItem = document.createElement('li');
                    welcomeItem.classList.add('nav-item');
                    welcomeItem.innerHTML = '<i class="fa-solid fa-user fa-sm"></i> Welcome, ' + data.username;
                    welcomeItem.style.color = 'white';
                    welcomeItem.style.listStyleType = 'none';
                    welcomeItem.style.paddingRight = '10px';
                    document.querySelector('.navbar').appendChild(welcomeItem);
                } else {
                    alert('Username taken');
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        form.reportValidity();
    }
});