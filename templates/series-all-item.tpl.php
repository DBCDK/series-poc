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
  <li class="ting-series-all-item">

    <div class="ting-series-all-object-cover">
      <?php print $item->cover ?>
    </div>

    <div class="ting-series-all-object-content">

      <div class="ting-series-all-object-title">
        <h2><?php print $item->title ?></h2>
        <div class="ting-series-all-object-type">
          <?php print $item->type ?>
        </div>
      </div>
      <div class="ting-series-all-object-creator">
        <?php print $item->creators ?>
      </div>
      <div class="ting-series-all-object-abstract">
        <?php print $item->abstract ?>
      </div>
      <div class="ting-series-all-object-universe">
        <?php print $item->universe ?>
      </div>
      <div class="ting-series-read-first">
        <?php if (isset($item->read_first)) : ?>
          <?php print $item->read_first ?>
        <?php endif; ?>
      </div>

    </div>
  </li>

<?php endif; ?>