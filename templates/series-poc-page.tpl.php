<?php ?>
<div class="ting-object-tree-page">
    <aside class="secondary-content">
        <div class="ting-object-tree-menu">
            <h2 class="sub-menu-title">
                <a href="/søgning" class="active-trail">Serier</a>
            </h2>
            <?php print $menu; ?>
        </div>
    </aside>
    <div class="ting-object-tree-main">
        <div class="ting-object-tree-breadcrumb">
            <?php print $breadcrumb; ?>
        </div>
        <!-- <h2 class="pane-title"><?php print $title; ?> </h2> -->
        <?php print $items; ?>

    </div>
</div>