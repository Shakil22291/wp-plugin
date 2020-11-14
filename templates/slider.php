<?php

$args = [
    'post_type'     => 'testimonial',
    'post_status'   => 'publish',
    'post_per_page' => 5,
    'meta_query'    => [
        [
            'key'     => '_myplugin_testimonial_key',
            'value'   => 's:8:"approved";i:1;s:8:"featured";i:1;',
            'compare' => 'LIKE'
        ]
    ]
];
$query = new WP_Query($args);
?>

<?php if ($query->have_posts()) : $i = 0; ?>
    <div class="myplugin-slider-wrapper">
        <div class="myplugin-slider-container">
            <div class="myplugin-slider-view">
                <ul>
                    <?php while($query->have_posts()): $query->the_post(); $i++; ?>
                        <li 
                            class="single-slide <?= $i === 1 ? 'is-active' : '';  ?>"
                        >
                            <p class="testimonial-quote">"<?php the_content(); ?>"</p>
                            <p class="testimonial-author">
                                ~<?= get_post_meta(get_the_ID(),'_myplugin_testimonial_key', true)['name'] ?? ''; ?>~
                            </p>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <div class="slider-arrows">
                    <span class="arrow-left"> &#x3c </span>
                    <span class="arrow-right"> &#x3e </span>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
