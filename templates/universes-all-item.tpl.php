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
  <li class="ting-universe-all-item">

      <div class="ting-universe-all-object-cover">
         <?php print $item->cover ?>
      </div>

      <div class="ting-universe-all-object-content">

        <div class="ting-universe-all-object-title">
          <h2><?php print $item->title ?></h2>
          <div class="ting-universe-all-object-type">
           <?php print $item->type ?>
        </div>
        </div>

        <div class="ting-universe-all-object-abstract">
           <?php print $item->abstract ?>
        </div>
        <div class="ting-universe-all-object-series">
          <h2><?php print $item->series ?></h2>
        </div>

      </div>
  </li>

<?php endif; ?>