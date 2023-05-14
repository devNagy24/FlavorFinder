let userSignedIn = false;  // Set this to true when the user signs in

let model = document.getElementById("mymodel");

let span = document.querySelector(".close");



// Show the model if the user is not signed in

if (!userSignedIn) {

    model.style.display = "block";

}



span.onclick = () => {

    model.style.display = "none";

}



window.onclick = (event) => {

    if (event.target === model) {

        model.style.display = "none";

    }

}


document.getElementById("signupForm").addEventListener("submit", (event) => {

    event.preventDefault();



    let name = document.getElementById("name").value;

    let email = document.getElementById("email").value;


    console.log(`Name: ${name}`);

    console.log(`Email: ${email}`);

});



fetch('../signupModel.html')

    .then(response => response.text())

    .then(data => {

        document.body.innerHTML += data;

    })

    .catch(error => {

        console.error('Error:', error);

    });