<?php
// Se o uninstall não for chamado pelo WordPress, sai.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove a opção do banco de dados
delete_option( 'mspc_base_slug' );
