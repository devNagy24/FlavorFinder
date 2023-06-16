# FlavorFinder
Final Project for Sdev328 @ Green River College

## Table of Contents
- [Introduction](#introduction)
- [Installation](#installation)
- [API Documentation](#api-documentation)
- [Application Usage](#application-usage)
- [UML Class Diagram](#uml-class-diagram)
- [Contributions](#contributions)
- [Support](#support)

## Introduction
FlavorFinder is a PHP-based web application that allows users to explore and discover a multitude of recipes. The user can search for recipes, bookmark their favorites, and review them at any time. The application relies on the Forkify API to fetch recipe data.

## Project Implementation

Here's a list on how and where each project requirement was implemented.

1. **API Integration and Data Retrieval:**
   The first major objective was integrating with the chosen API and retrieving data from it. This was accomplished by using the `fetch` function in JavaScript, and it was implemented in the file `main.js` within the function `getData()`. This function was used to make requests to the API and receive responses. The data obtained from the API was then stored in a variable for later use.

2. **HTML and Bootstrap Template:**
   The second goal was to create a user interface for displaying the API data. To achieve this, we started with the HTML file `index.html` and styled it with Bootstrap. This template includes sections for displaying various categories of API data, such as news articles, weather data, and more. It provides a basic structure for organizing and presenting the API data.

3. **Data Manipulation and Display:**
   The third step was to manipulate the data from the API and display it in the HTML template. This was done using the F3 repeat directive in our HTML file, as it allowed us to iterate over the received data and display each piece of information in the template. The manipulation and display of data were implemented in the `displayData()` function within `main.js`.

4. **JavaScript and Front-End Functionality:**
   Once the data was successfully displayed on the HTML template, we turned our attention to implementing the frontend functionality. This was achieved using JavaScript and was implemented in `main.js`. This file contains functions for handling user interactions such as button clicks and form submissions.

5. **Database Integration:**
   The final step was integrating with our MySQL database. This was done using the `mysql` module in Node.js. We used it to create a connection to our database, perform queries, and retrieve results. The database connection and operations were implemented in `database.js`. This file contains functions for creating and closing the database connection, as well as for performing CRUD operations on the data.

These are the steps that led us to achieving our goal.


## Installation
Here are the installation steps:
1. Clone the repository to your local machine or server.
2. Import the `database.sql` file into your MySQL database. This file will create the necessary tables and populate them with data.
3. Update the `db.php` file to use your MySQL database credentials.
4. Navigate to the application's root directory in your browser to start using FlavorFinder.
5. Make sure your PHP version is at least 7.1
6. Verify Fat-Free Framework is downloaded with a "composer update" in your terminal.

## API Documentation
FlavorFinder uses the [Forkify API](https://forkify-api.herokuapp.com/v2) to fetch recipe data. Forkify API provides access to a ton of recipes and an easy-to-use search functionality.

## Application Usage
FlavorFinder offers the following features:

1. **User Registration and Authentication:** Users can register by providing a unique username, password, and a valid email address. They can log in to the application using the registered credentials. The user-related functionality is found in the `model/user_model.php` file.

2. **Recipe Search:** Users can search for a wide range of recipes using the search bar. The search results show a list of recipes fetched from the Forkify API. The API calls are handled by the `model/API_Model.php` file.

3. **Bookmark Recipes:** Users can bookmark their favorite recipes. The bookmarks are stored in the database and can be reviewed at any time. The bookmarking functionality is also located in the `model/user_model.php` file.

4. **Routing:** The application uses a simple routing system to navigate between different pages. The routing is controlled by the `index.php` file in the root directory, with the `controllers/controller.php` handling the route controllers.

5. **JavaScript Functions:** The front end interactivity, including the search bar functionality, bookmarking, and pagination is controlled by JavaScript located in the `js/routing_controller.js` directory. There is a ton of AJAX calls being made, like the pagination, bookmarks, and grabbing the search value to let our backend code retrieve the value to start the API call using \Web (F3). But the main ingredient to achieve status 200 is -> fetch(appRoot + `?page=${page}`). appRoot is the url path "/328/FlavorFinder". After a couple of hours of watching my network tab in the browser, I finally thought of creating a variable to hold the path, instead of just supplying the whole path in the "fetch()". Nothing made me happier than to see the green 200, after seeing 404 or 500 error codes numerous of times. 

6. **Styles:** The application's visual appeal and user-friendly design are governed by the CSS files located in the `styles` directory. This includes `main.css`, `recipe.css`, and `modal.css`.

# UML Class Diagram

The main classes that make up the architecture of the FlavorFinder project are as follows:

1. **UserModel**: This class manages all operations related to the user, including registration, login, authentication, bookmarking, and fetching bookmarks.

2. **ApiController**: This class interacts with the Forkify API to fetch recipes based on a keyword and loads a specific recipe based on its ID.

3. **RouteController**: This class manages routes and actions associated with them. It handles the home route, user sign-up, user login, session retrieval, bookmark creation, and bookmark retrieval.

## UserModel

- **Constructor**: Takes a database connection object and stores it.
- **register**: Registers a new user with a given username, password, and email.
- **login**: Logs in a user with a given username and password.
- **authenticate**: Authenticates a user with a given username and password.
- **bookmark**: Adds a recipe to the user's bookmarks.
- **getBookmarks**: Retrieves all of a user's bookmarked recipes.

## ApiController

- **loadRecipe**: Loads a specific recipe from the Forkify API using its ID.
- **getRecipesFromForkify**: Fetches recipes from the Forkify API based on a keyword.

## RouteController

- **Constructor**: Instantiates F3, APIController, and UserModel.
- **home**: Handles the home route and retrieves and displays recipes based on a keyword.
- **clearCookies**: Clears the user session data and resets the URL.
- **signUp**: Handles user sign-up and registers a new user.
- **login**: Handles user login and validates it.
- **getSession**: Retrieves session data for the user.
- **postBookmarks**: Handles bookmarking a recipe.
- **getBookmarked**: Retrieves the user's bookmarked recipes from the database.


## Contributions
Contributions are always welcome! If you have suggestions or improvements, please fork the repository and create a pull request.

## Support
If you need any assistance or have any queries, you can open an issue in the repository or contact the project team.

- [Devon](https://github.com/devNagy24)
- [Sayed](https://github.com/sayedjsadat)
