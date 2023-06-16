// Define the application root path
const appRoot = '/328/FlavorFinder';

// Add click event listener to the pagination
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

// Add click event listener to the active home link in the navigation
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

// Repeat of above block for the generic home link --
// we should combine the function above & this one
// @ a later date - Devon
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

// Define the loadRecipes function
function loadRecipes(page) {
    // Get the search keyword from the input field
    let searchKeyword = document.querySelector('#searchInput').value;

    // testing to see page numbers before the fetch happens
    console.log("Page before fetch:", page);

    // Send a POST request to the server with the page number and search keyword
    fetch(appRoot + `?page=${page}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        // Trim the searchKeyword and only send if not empty
        body: JSON.stringify({ keyword: searchKeyword.trim() })
    })
        .then(response => {
            console.log("Response:", response);
            // Expecting HTML, not JSON @ this point
            return response.text();
        })
        .then(data => {
            console.log("Data:", data);
            /* ... Parse the response and update the UI ... */ }
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
        /* ... Handle error ... */
            console.error('Error:', error);
        });
}

// Updates the pagination page #
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

// this route is used in a seperate function (clear cookies) in controller.php
const appRootTwo = '/328/FlavorFinder';

document.addEventListener("DOMContentLoaded", function(event) {
    fetch(appRootTwo + '/get-session', {
        method: 'GET'
    })
        .then(response => response.json())
        .then(data => {
            if(data.username) {
                // User is logged in, show the welcome message
                let welcomeItem = document.createElement('li');
                welcomeItem.classList.add('nav-item');
                welcomeItem.innerHTML = '<i class="fa-solid fa-user fa-sm"></i> Welcome, ' + data.username;
                welcomeItem.style.color = 'white';
                welcomeItem.style.listStyleType = 'none';
                welcomeItem.style.paddingRight = '10px';
                document.querySelector('.navbar').appendChild(welcomeItem);
            }
        })
});

// Event listener attatched to "Log In"
document.getElementById('loginSubmit').addEventListener('click', function(event) {
    event.preventDefault();
    let form = document.getElementById('loginForm');

    if(form.checkValidity()) {
        let username = document.getElementById('loginUsername').value;
        let password = document.getElementById('loginPassword').value;

        let payload = {
            username: username,
            password: password
        };

        fetch(appRootTwo + '/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        }).then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Close the login model
                    let modal = document.getElementById('loginModal');
                    let bootstrapModal = bootstrap.Modal.getInstance(modal);
                    bootstrapModal.hide();

                    // Save the username in the localStorage
                    localStorage.setItem('username', data.username);

                    // Show the toast here when the signup is successful
                    let toast = new bootstrap.Toast(document.getElementById('toastLog'));
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
                    alert('Login failed');
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        form.reportValidity();
    }
});


document.getElementById('bookmarksLink').addEventListener('click', function(event) {
    event.preventDefault();

    // Clear existing bookmarks
    document.getElementById('bookmarksList').innerHTML = '';

    // Fetch the bookmarks from your backend
    fetch(appRootTwo + '/bookmarks', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
    }).then(response => response.json())
        .then(data => {
            console.log(data);
            // Assuming your API returns an array of bookmarked recipes
            if (Array.isArray(data.bookmarks)) {
                // Create an array to store the fetch promises
                const fetchPromises = [];

                // Iterate over the bookmark IDs
                for (let bookmarkId of data.bookmarks) {
                    // Fetch the recipe details by ID
                    const fetchPromise = fetch(appRootTwo + '/recipe/' + bookmarkId, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                    }).then(response => response.text())
                        .then(recipeData => {
                            // Extract the title from the returned HTML
                            const parser = new DOMParser();
                            const htmlDoc = parser.parseFromString(recipeData, 'text/html');
                            const titleElement = htmlDoc.querySelector('.fw-light');
                            const title = titleElement.textContent;

                            // Create a link element for each bookmarked recipe
                            const bookmarkEl = document.createElement('a');
                            bookmarkEl.className = "list-group-item list-group-item-action";
                            bookmarkEl.href = appRootTwo + '/recipe/' + bookmarkId;
                            bookmarkEl.textContent = title;

                            document.getElementById('bookmarksList').appendChild(bookmarkEl);
                        })
                        .catch(error => console.error('Error:', error));

                    // Add the fetch promise to the array
                    fetchPromises.push(fetchPromise);
                }

                // Wait for all fetch promises to resolve
                Promise.all(fetchPromises)
                    .then(() => {
                        // Show the modal
                        let modal = new bootstrap.Modal(document.getElementById('bookmarksModal'));
                        modal.show();
                    })
                    .catch(error => console.error('Error:', error));
            }
        })
        .catch(error => console.error('Error:', error));
});


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
        fetch(appRootTwo + '/sign-up', {
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


