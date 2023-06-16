# FlavorFinder
Final Project for Sdev328 @ Green River College

## Table of Contents
- [Introduction](#introduction)
- [Installation](#installation)
- [API Documentation](#api-documentation)
- [Application Usage](#application-usage)
- [Contributions](#contributions)
- [Support](#support)

## Introduction
FlavorFinder is a PHP-based web application that allows users to explore and discover a multitude of recipes. The user can search for recipes, bookmark their favorites, and review them at any time. The application relies on the Forkify API to fetch recipe data.

## Installation
Here are the installation steps:
1. Clone the repository to your local machine or server.
2. Import the `database.sql` file into your MySQL database. This file will create the necessary tables and populate them with data.
3. Update the `db.php` file to use your MySQL database credentials.
4. Navigate to the application's root directory in your browser to start using FlavorFinder.

## API Documentation
FlavorFinder uses the [Forkify API](https://forkify-api.herokuapp.com/v2) to fetch recipe data. Forkify API provides access to a ton of recipes and an easy-to-use search functionality.

## Application Usage
FlavorFinder offers the following features:

1. **User Registration and Authentication:** Users can register by providing a unique username, password, and a valid email address. They can log in to the application using the registered credentials. The user-related functionality is found in the `model/user_model.php` file.

2. **Recipe Search:** Users can search for a wide range of recipes using the search bar. The search results show a list of recipes fetched from the Forkify API. The API calls are handled by the `model/API_Model.php` file.

3. **Bookmark Recipes:** Users can bookmark their favorite recipes. The bookmarks are stored in the database and can be reviewed at any time. The bookmarking functionality is also located in the `model/user_model.php` file.

4. **Routing:** The application uses a simple routing system to navigate between different pages. The routing is controlled by the `index.php` file in the root directory, with the `controllers/controller.php` handling the route controllers.

5. **JavaScript Functions:** The front end interactivity, including the search bar functionality, bookmarking, and pagination is controlled by JavaScript located in the `js/routing_controller.js` directory.

6. **Styles:** The application's visual appeal and user-friendly design are governed by the CSS files located in the `styles` directory. This includes `main.css`, `recipe.css`, and `modal.css`.

## Contributions
Contributions are always welcome! If you have suggestions or improvements, please fork the repository and create a pull request.

## Support
If you need any assistance or have any queries, you can open an issue in the repository or contact the project team.

- [Devon](https://github.com/devNagy24)
- [Sayed](https://github.com/sayedjsadat)
