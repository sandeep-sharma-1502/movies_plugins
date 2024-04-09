<?php

    echo  '<div class="conatiner">';
    echo "<div class='immmg'><img src=".get_the_post_thumbnail_url($post_id)." alt='' width='500' height='600'> </div>";
    echo '<h2>' . get_the_title() . '</h2>';
    echo '<p><strong>Author: </strong> ' .get_the_author(). '</p>';
    $categories = get_the_category($post_id);
    echo '<p><strong>Category: </strong> '.$categories[0]->name. '</p>';

   
    $specific_post_permalink = get_permalink($post_id);


    echo '<a href="' . $specific_post_permalink . '">SHOW</a>';


echo  '</div>';


?>