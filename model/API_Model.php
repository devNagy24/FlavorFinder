<?php
//using API to search for a recepie online
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


    function getRecipesFromForkify($query)
    {
        // Only make the API call if $query is not empty
        if (!empty($query)) {
            // Use $query to fetch data from the API...
            $json = file_get_contents("https://forkify-api.herokuapp.com/api/v2/recipes?search=" . $query);
            $data = json_decode($json, true);
            $recipes = $data['data']['recipes'];

            return $recipes;
        } else {
            // If $query is empty, return an empty array
            return [];
        }
    }



}
