<?php

/**
 * @file
 * Modified version of the default theme implementation for displaying a single
 * search result. The h3 title have been removed.
 *
 *
 * @see template_preprocess()
 * @see template_preprocess_search_result()
 * @see template_process()
 */
?>

<?php if ($item) : ?>
  <div class="ting-series-object">
    <div class="ting-series-object-content">
      <div class="ting-series-object-title">
        <h2><?php print $item->title ?></h2>
      </div>
      <div class="ting-series-object-abstract">
        <?php print $item->abstract ?>
      </div>
      <div class="ting-series-object-universe">
        <?php print $item->universe ?>
      </div>
    </div>

    <?php if (!empty($item->covers)) : ?>
      <ul class="ting-series-object-items">
        <?php foreach ($item->covers as $cover) : ?>
          <?php print $cover; ?>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <div class="ting-series-object-type">
      <?php print $item->type ?>
    </div>
  </div>
<?php endif; ?>

