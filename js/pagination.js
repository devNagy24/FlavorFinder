// Normally I would pass 'e' instead of 'event', but this is more readable
document.querySelector('.pagination').addEventListener('click', function(event) {
    // Verifying that the anchor tag is clicked
    if (event.target.tagName === 'A') {
        // Preventing reloading of page
        event.preventDefault();

        // Looking for href link of "?page=NUMBER"
        let page = parseInt(event.target.href.split('=')[1]);
        loadRecipes(page);
    }
});

function loadRecipes(page) {
    let searchKeyword = document.querySelector('#searchInput').value;
    fetch('/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ keyword: searchKeyword, page: page })
    })
        .then(response => response.text())
        .then(data => {
            // update URL
            history.pushState({}, "", `?page=${page}`);
            // Assuming that the server returns only the recipe cards as HTML
            document.querySelector('.row-cols-1').innerHTML = data;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}