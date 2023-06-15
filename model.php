<?php
//Api model to look up recipes
class RecipeModel {
    public function loadRecipe($query) {
        $api_url = "https://forkify-api.herokuapp.com/api/v2/recipes/?search=" . $query;

        $response = \Web::instance()->request($api_url);
        $data = json_decode($response['body']);

        if ($response['status'] == 200) {
            return $data;
        } else {
            return NULL;
        }
    }
}
