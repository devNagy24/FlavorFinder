<?php

class ApiController
{
    public function loadRecipe($id)
    {
        $web = \Web::instance();
        $response = $web->request('https://forkify-api.herokuapp.com/api/v2/recipes/'.$id);

        if ($response['body']) {
//            var_dump($response);
            // Status code indicates success:
            $data = json_decode($response['body'], true);
            return $data['data']['recipe'];
        } else {
            // Handle error...
            return null;
        }
    }


    function getRecipesFromForkify()
    {
        // Use $query to fetch data from the API...
        $json = file_get_contents("https://forkify-api.herokuapp.com/api/v2/recipes?search=pizza");
        $data = json_decode($json, true);
        $newArray = $data['data']['recipes'];

        // Limit the number of results
        $limitedRecipes = array_slice($newArray, 0, 10);

        return $limitedRecipes;

//        foreach ($newArray as $recipe) {
//            echo "Publisher: " . $recipe['publisher'] . "<br>";
//            echo "Image URL: " . $recipe['image_url'] . "<br>";
//            echo "Title: " . $recipe['title'] . "<br>";
//            echo "ID: " . $recipe['id'] . "<br><br>";
//        }

    }


}