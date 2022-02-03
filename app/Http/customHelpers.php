<?php 

    function getBlogCategory($id)
    {
        $category = DB::table('category')->where('id', $id)->first();

        return $category->name;
    }

    function getTime($param)
    {
        $time = date("Y-m-d h:i A", strtotime($param));

        return $time;
    }


