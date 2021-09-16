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
  <li class="ting-series-object-item">

    <div class="ting-series-object-cover">
      <?php print $item->cover ?>
    </div>

    <div class="ting-series-object-title">
      <h2><?php print $item->title ?></h2>
    </div>
    <div class="ting-series-read-first">
      <?php if (isset($item->read_next)) : ?>
        <?php print $item->read_next ?>
      <?php endif; ?>
    </div>
  </li>

<?php endif; ?>