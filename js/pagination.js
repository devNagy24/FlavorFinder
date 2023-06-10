document.querySelector('.pagination').addEventListener('click', function(event) {
    if (event.target.tagName === 'A') {
        event.preventDefault();
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
            history.pushState({}, "", `?page=${page}`);  // update URL
            document.querySelector('.row-cols-1').innerHTML = data; // Here, we assume that the server returns only the recipe cards as HTML
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}
