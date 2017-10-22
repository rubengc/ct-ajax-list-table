<?php
/**
 * Functions
 *
 * @since 1.0.0
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Setup the global table
 *
 * @param CT_Table|string $object CT_Table object or CT_Table name
 * @return CT_Table $ct_table
 */
function ct_render_ajax_list_table( $object, $query_args = array(), $view_args = array() ) {

    global $ct_table, $ct_query, $ct_list_table;

    $ct_table = ct_setup_table( $object );

    if( is_object( $ct_table ) ) {

        // Setup this constant to allow from CT_List_Table meet that this render comes from this plugin
        @define( 'IS_CT_AJAX_LIST_TABLE', true );

        // Enqueue assets
        ct_ajax_list_table_enqueue_scripts();

        $query_args = wp_parse_args( $query_args, array(
            'paged' => 1,
            'items_per_page' => 20,
        ) );

        $view_args = wp_parse_args( $view_args, array(
            'views' => true,
            'search_box' => true,
        ) );

        // Set up vars
        $ct_query = new CT_Query( $query_args );
        $ct_list_table = new CT_List_Table();

        $ct_list_table->prepare_items();

        ?>

        <div class="wrap ct-ajax-list-table" data-object="<?php echo $ct_table->name; ?>" data-query-args="<?php echo str_replace( '"', "'", json_encode( $query_args ) ); ?>">

            <?php
            if( $view_args['views'] ) {
                $ct_list_table->views();
            }
            ?>

            <form id="ct-list-filter" method="get">

                <?php
                if( $view_args['search_box'] ) {
                    $ct_list_table->search_box( $ct_table->labels->search_items, $ct_table->name );
                }
                ?>

                <?php $ct_list_table->display(); ?>

            </form>

            <div id="ajax-response"></div>
            <br class="clear" />

        </div>

        <?php

        @define( 'IS_CT_AJAX_LIST_TABLE', false );

    }

}