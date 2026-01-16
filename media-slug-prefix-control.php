<?php
/**
 * Plugin Name:       Media Slug Prefix Control
 * Plugin URI:        https://seusite.com/plugins/media-slug-prefix
 * Description:       Adds a custom prefix base (e.g., /media/) to attachment URLs to prevent slug conflicts with Pages and Posts.
 * Version:           1.0.0
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * Author:            Seu Nome
 * Author URI:        https://seusite.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       media-slug-prefix-control
 */

// Impede acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. CONFIGURAÇÃO E SALVAMENTO (Painel Admin)
 */

// Adiciona o campo na tela de Links Permanentes
function mspc_add_settings_field() {
	add_settings_field(
		'mspc_base_slug',
		__( 'Base das Mídias (Attachments)', 'media-slug-prefix-control' ),
		'mspc_render_input_field',
		'permalink',
		'optional'
	);
}
add_action( 'admin_init', 'mspc_add_settings_field' );

// Renderiza o HTML do campo
function mspc_render_input_field() {
	$value = get_option( 'mspc_base_slug' );
	?>
	<input type="text" 
		name="mspc_base_slug" 
		value="<?php echo esc_attr( $value ); ?>" 
		class="regular-text code" 
		placeholder="ex: media"
	/>
	<p class="description">
		<?php esc_html_e( 'Defina um prefixo para URLs de anexos. Deixe vazio para usar o padrão do WordPress.', 'media-slug-prefix-control' ); ?>
	</p>
	<?php
}

// Salva manualmente a opção (necessário pois options-permalink.php não salva campos customizados nativamente)
function mspc_save_settings() {
	if ( isset( $_POST['mspc_base_slug'], $_POST['permalink_structure'] ) ) {
		
		// Verificação de segurança (Nonce)
		if ( function_exists( 'check_admin_referer' ) && check_admin_referer( 'update-permalink' ) ) {
			$clean_value = sanitize_text_field( $_POST['mspc_base_slug'] );
			
			// Evita usar palavras reservadas do sistema que quebrariam o site
			$forbidden = array( 'wp-admin', 'login', 'admin', 'dashboard' );
			if ( in_array( $clean_value, $forbidden ) ) {
				add_settings_error( 'permalink', 'mspc_error', __( 'Este slug é reservado pelo sistema.', 'media-slug-prefix-control' ) );
				return;
			}

			update_option( 'mspc_base_slug', $clean_value );
		}
	}
}
add_action( 'admin_init', 'mspc_save_settings' );

/**
 * 2. ESTRUTURA DE URL E REGRAS (Rewrite Rules)
 */

// Adiciona a regra de reescrita personalizada
function mspc_add_rewrite_rules( $rules ) {
	$base_slug = get_option( 'mspc_base_slug' );
	
	if ( ! empty( $base_slug ) ) {
		$new_rule = array(
			$base_slug . '/([^/]+)/?$' => 'index.php?attachment=$matches[1]',
		);
		return $new_rule + $rules;
	}
	return $rules;
}
add_filter( 'rewrite_rules_array', 'mspc_add_rewrite_rules' );

// Altera o link gerado pelo WordPress (Visual)
function mspc_modify_attachment_link( $link, $post_id ) {
	$base_slug = get_option( 'mspc_base_slug' );

	if ( empty( $base_slug ) ) {
		return $link;
	}

	$post = get_post( $post_id );
	if ( ! $post || 'attachment' !== $post->post_type ) {
		return $link;
	}

	return home_url( '/' . $base_slug . '/' . $post->post_name . '/' );
}
add_filter( 'attachment_link', 'mspc_modify_attachment_link', 20, 2 );

/**
 * 3. DESBLOQUEADOR DE SLUG (Permite Páginas com mesmo nome)
 */
function mspc_allow_duplicate_slugs( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) {
	
	// Só precisamos intervir se o slug foi alterado devido a um conflito
	if ( $slug !== $original_slug ) {
		
		// Verifica se o dono original do slug é um anexo
		$existing_post = get_page_by_path( $original_slug, OBJECT, 'attachment' );

		// Se o conflito for com um anexo, e estamos salvando Page ou Post
		if ( $existing_post && in_array( $post_type, array( 'page', 'post' ) ) ) {
			// Verifica se temos um prefixo configurado. Se tiver, liberamos o slug.
			if ( get_option( 'mspc_base_slug' ) ) {
				return $original_slug;
			}
		}
	}
	return $slug;
}
add_filter( 'wp_unique_post_slug', 'mspc_allow_duplicate_slugs', 10, 6 );

/**
 * 4. REDIRECIONADOR (Canonical Redirect)
 * Redireciona URLs antigas (sem prefixo) para a nova (com prefixo)
 */
function mspc_redirect_old_urls() {
	if ( is_attachment() ) {
		$base_slug = get_option( 'mspc_base_slug' );

		if ( empty( $base_slug ) ) {
			return;
		}

		$current_url = $_SERVER['REQUEST_URI'];

		// Se a URL não contém o prefixo, redireciona
		if ( strpos( $current_url, '/' . $base_slug . '/' ) === false ) {
			wp_safe_redirect( get_attachment_link(), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'mspc_redirect_old_urls' );

/**
 * 5. ATIVAÇÃO E DESATIVAÇÃO
 * Limpa o cache de rewrites para evitar erro 404
 */
function mspc_activation() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mspc_activation' );

function mspc_deactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'mspc_deactivation' );
