<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap">
    <h1>Cred App</h1>
    <hr>
    <div>
        <!-- <p><strong>Today:</strong> <?php echo TOCHATBE_Log::get_total_day_click(); ?> | <strong>This Week:</strong> <?php echo TOCHATBE_Log::get_this_week_click(); ?></p> -->
    </div>
    <?php
        $table = new ADDRESSYA_Admin_Cred_App_Table;
        $table->prepare_items();
    ?>
    <form method="post" action="#">
        <?php $table->search_box( 'Search', 'tochatbe-search' ); ?>
    </form>
    <?php
        $table->display();
    ?>
</div>

<?php //require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/notifications/html-purchase-premium.php'; ?>