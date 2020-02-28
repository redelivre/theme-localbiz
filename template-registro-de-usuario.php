<?php
	function submit_template() {?>
		<div class="row">
			<div class="Rectangle-Copy-2" onclick="submitpasso2();">
				<div class="Title-Copy-4">Continuar</div>
			</div>
			<button id="submit" type="submit" class="hide"></button>
		</div><?php
	}
?>
<?php do_action( 'before_signup_form' ); ?>

<?php
	$stage=array_key_exists('estagio', $_REQUEST) ? sanitize_text_field( $_REQUEST['estagio'] ) : 1;
	$wpc_captcha = class_exists('WP_Captcha') ? new WP_Captcha() : false;
	
	function localbiz_hidden_fields() {
		$stage=array_key_exists('estagio', $_REQUEST) ? sanitize_text_field( $_REQUEST['estagio'] ) : 1;?>
		<input type="hidden" name="estagio" value="<?php echo $stage+1; ?>"/><?php
		if($stage > 1) wp_nonce_field('localbiz_registro_action', 'localbiz_registro_nonce_field');
		if( array_key_exists('tipo-registro', $_REQUEST) ): ?>
		 	<input type="hidden" id="tipo-registro" name="tipo-registro" value="<?php echo sanitize_text_field( $_REQUEST['tipo-registro'] )?>"/><?php
		elseif ($stage == 1):?>
			<input type="hidden" id="tipo-registro" name="tipo-registro" value=""/><?php
		endif;
		if(array_key_exists('post_id', $_REQUEST) && sanitize_text_field( $_REQUEST['post_id'] ) > 0) : ?>
			<input type="hidden" id="post_id" name="post_id" value="<?php echo sanitize_text_field( $_REQUEST['post_id'] )?>"/>
		<?php
		endif;
	}
?>
<?php switch ($stage) { 
	case 1: ?>
		<div class="Onboarding">
			<div class="row">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/group-30.svg" class="Group-30">
			</div>
			<?php LocalBiz::display_error(); ?>
			<div class="row">
				<div class="Title-Copy">Quem é você neste movimento local?</div>
			</div>
			<div class="row">
				<div class="Title-Copy-2">Juntos podemos fortalecer nossa sociedade e economia</div>
			</div>
			<form class="localbiz_registerform" id="localbiz_registerform1" method="post" >
				<?php localbiz_hidden_fields(); ?>
				<div class="row">
					<div class="Rectangle-Copy-3" onclick="jQuery('#tipo-registro').val('empresa');jQuery('#localbiz_registerform1').submit();">
						<div class="Rectangle-img">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/group-2-copy-3.svg"
								class="Group-2-Copy-3"/>
						</div>
						<div class="Title-Copy-6">
							Sou Empresa
						</div>
					</div>
					<div class="Rectangle-Copy-4" onclick="jQuery('#tipo-registro').val('consumidor');jQuery('#localbiz_registerform1').submit();">
						<div class="Rectangle-img">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/group-2-copy-4.svg"
			     				class="Group-2-Copy-4"/>
			     		</div>
			     		<div class="Title-Copy-6">
							Sou consumidor
						</div>
					</div>
				</div>
				<div class="row">
				
				</div>
			</form>
		</div><?php
	break;
	case 2:
		$tipo_registro = array_key_exists('tipo-registro', $_REQUEST) ? sanitize_text_field( $_REQUEST['tipo-registro'] ) : false;
		if($tipo_registro == false) {
			wp_redirect('/registro-de-usuario');
		}
		switch($tipo_registro) {
			case 'empresa':?>
				<div class="passo-2">
					<div class="Onboarding-2">
						<div class="row">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/group-30.svg" class="Group-30">
						</div>
						<div class="row">
							<div class="Rectangle-2">
								<div class="row">
									<div class="Title-Copy">
										Cadastrando seu negócio
									</div>
								</div>
								<div class="row">
									<div class="Oval">
										<span>1</span>
									</div>
									<div class="Oval-Copy">
										<span>2</span>
									</div>
									<div class="Oval-Copy">
										<span>3</span>
									</div>
									<div class="Oval-Copy">
										<span>4</span>
									</div>
									<div class="Oval-Copy">
										<span>5</span>
									</div>
								</div>
							</div>
						</div>
						<?php LocalBiz::display_error(); ?>
						<form class="localbiz_registerform" id="localbiz_registerform2" method="post">
							<?php wp_nonce_field('localbiz_registro_action', 'localbiz_registro_nonce_field'); ?>
							<?php localbiz_hidden_fields(); ?>
							<div class="row">
								<div class="Title-Copy-2">
									Dados do negócio
								</div>
							</div>
							<div class="row">
								<div class="title">
									Nome do Negócio
								</div>
							</div>
							<div class="row">
								<input type="text" name="nome-negocio" class="Rectangle" value="<?php echo array_key_exists('nome-negocio', $_REQUEST) ? sanitize_text_field($_REQUEST['nome-negocio']) : ''; ?>" required />
							</div>
							<div class="row">
								<div class="title">
									Nome do Responsável
								</div>
							</div>
							<div class="row">
								<input type="text" name="nome-responsavel" class="Rectangle" value="<?php echo array_key_exists('nome-responsavel', $_REQUEST) ? sanitize_text_field($_REQUEST['nome-responsavel']) : ''; ?>" required />
							</div>
							<?php if(! is_user_logged_in() ) : ?>
								<div class="row">
									<div class="title">
										Email do Responsável
									</div>
								</div>
								<div class="row">
									<input type="email" name="email" class="Rectangle" value="<?php echo array_key_exists('email', $_REQUEST) ? sanitize_text_field($_REQUEST['email']) : ''; ?>" required />
								</div>
								<div class="row">
									<div class="title">
										Senha
									</div>
								</div>
								<div class="row">
									<input type="password" name="senha" class="Rectangle" value="" required />
								</div>
								<div class="row">
									<div class="title">
										Repetir Senha
									</div>
								</div>
								<div class="row">
									<input type="password" name="senha2" class="Rectangle" value="" required />
								</div>
								<div class="row">
									<div class="title">
										Captcha
									</div>
								</div>
								<?php if(is_object($wpc_captcha)) : ?>
									<div class="row">
										<?php
											$wpc_captcha->wpc_display_captcha();
										?>
									</div>
								<?php endif; ?>
							<?php endif; ?>
							<?php submit_template(); ?>
						</form>
					</div>
				</div><?php
			break;
		}
	break;
	case 3:
		$user = wp_get_current_user();
		$check_captcha = is_object($wpc_captcha) ? $wpc_captcha->wpc_captcha_login_check($user) : true;
		if(
			isset($_REQUEST['senha']) &&
			isset($_REQUEST['senha2']) &&
			$_REQUEST['senha'] == $_REQUEST['senha2'] &&
			isset($_REQUEST['email']) &&
			! ($check_captcha instanceof WP_Error) &&
			wp_verify_nonce($_REQUEST['localbiz_registro_nonce_field'], 'localbiz_registro_action')
		) {
			$password = sanitize_text_field($_REQUEST['senha']);
			$email = sanitize_text_field($_REQUEST['email']);
			if($wp_user_id = email_exists($email) ) {
				$wpuser = wp_authenticate($email, $password);
				if($wpuser instanceof WP_User) {
					$wpuser = wp_set_current_user($wpuser->ID);
					wp_set_auth_cookie($wpuser->ID);
					do_action('wp_login', $wpuser->user_login, $wpuser);
				} else {
					$sites = get_blogs_of_user($wp_user_id);
					$_REQUEST['estagio'] = 2;
					$_REQUEST['error'] = 'O seu e-mail já está cadastrado em um dos portais da RedeLivre (abaixo), utilize a mesma senha';
					$limit = 3;
					foreach ($sites as $site) {
						$limit--;
						$_REQUEST['error'] .= '<br/><a href="'.$site->siteurl.'">'.$site->blogname.'</a>';
						if($limit < 1) break;
					}
					$_REQUEST['WP_Error'] = $wpuser;
					$url = '/registro-de-usuario' . '?' . http_build_query($_REQUEST);
					wp_redirect($url);
				}
			} else {
				$username_base = substr($email, 0, strpos($email, '@'));
				$username = $username_base;
				$sulfix = 0;
				while (username_exists($username) && $sulfix < 20) {
					$sulfix++;
					$username = $username_base.'-'.$sulfix;
				}
				$wp_user_id = wp_create_user($username, $password, $email);
				wp_update_user( array( 'ID' => $wp_user_id, 'display_name' => sanitize_text_field($_REQUEST['nome-responsavel'])));
				if(is_int($wp_user_id)) {
					$wpuser = wp_set_current_user($wp_user_id);
					wp_set_auth_cookie($wpuser->ID);
					do_action('wp_login', $wpuser->user_login, $wpuser);
				}
			}
		} elseif( !is_user_logged_in() ) {
			$_REQUEST['estagio'] = 2;
			$_REQUEST['error'] = 'São necessários os dados de contato e login';
			if($check_captcha instanceof WP_Error) {
				$_REQUEST['WP_Error'] = $check_captcha;
				$_REQUEST['error'] .= 'Valor do captcha errado';
			}
			$url = '/registro-de-usuario' . '?' . http_build_query($_REQUEST);
			wp_redirect($url);
		} elseif(!wp_verify_nonce($_REQUEST['localbiz_registro_nonce_field'], 'localbiz_registro_action')) {
			$_REQUEST['estagio'] = 2;
			$_REQUEST['error'] = 'Formulário fora de validade, favor refazer o cadastro';
			$url = '/registro-de-usuario' . '?' . http_build_query($_REQUEST);
			wp_redirect($url);
		}
		if(is_user_logged_in()) {
			$post_id = false;
			$post = false;
			if(isset($_REQUEST['post_id'])) {
				$post_id = intval(sanitize_text_field($_REQUEST['post_id']));
				$post_tmp = get_post($post_id);
				if(get_current_user_id() == $post_tmp->post_author) {
					$post = $post_tmp;
				} else {
					$_REQUEST['estagio'] = 2;
					$_REQUEST['error'] = 'Você não pode editar esse post';
					unset($_REQUEST['post_id']);
					$url = '/registro-de-usuario' . '?' . http_build_query($_REQUEST);
					wp_redirect($url);
				}
			} else {
				$post = array();
				$post['post_title'] = sanitize_text_field($_REQUEST['nome-negocio']);
				$post['post_type'] = 'localbiz';
				$post['post_status'] = 'pending';
				$post_id = wp_insert_post($post);
				update_post_meta($post_id, 'nome-responsavel', sanitize_text_field($_REQUEST['nome-responsavel']));
				update_post_meta($post_id, 'estagio', sanitize_text_field($_REQUEST['estagio']));
			}
			$_REQUEST['post_id'] = $post_id; ?>
			<div class="passo-3">
				<div class="Onboarding-2">
					<div class="row">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/group-30.svg" class="Group-30">
					</div>
					<?php LocalBiz::display_error(); ?>
					<div class="row">
						<div class="Rectangle-2">
							<div class="row">
								<div class="Title-Copy">
									Cadastrando seu negócio
								</div>
							</div>
							<div class="row">
								<div class="Oval-Copy">
									<span>1</span>
								</div>
								<div class="Oval">
									<span>2</span>
								</div>
								<div class="Oval-Copy">
									<span>3</span>
								</div>
								<div class="Oval-Copy">
									<span>4</span>
								</div>
								<div class="Oval-Copy">
									<span>5</span>
								</div>
							</div>
						</div>
					</div>
					<form class="localbiz_registerform" id="localbiz_registerform2" method="post">
						<?php localbiz_hidden_fields(); ?>
						<div class="row">
							<div class="Title-Copy-2">
								Sua Região
							</div>
						</div>
						<div class="row">
							<div class="title">
								CEP do seu negócio
							</div>
						</div>
						<div class="row">
							<input type="text" id="cep" name="cep" class="Rectangle" value="<?php echo array_key_exists('cep', $_REQUEST) ? sanitize_text_field($_REQUEST['cep']) : ''; ?>" required />
						</div>
						<div class="row">
							<div class="title title-endereco">
								Endereço
							</div>
						</div>
						<div class="row">
							<input name="endereco" id="endereco"  class="Rectangle" data-autocomplete-address required>
						</div>
						<div class="row">
							<div class="title-bairro">
								Bairro
							</div>
							<div class="title-numero">
								Número
							</div>
						</div>
						<div class="row">
							<input name="bairro" id="bairro" class="Rectangle-bairro" data-autocomplete-neighborhood required>
							<input type="number" name="numero" id="numero" class="Rectangle-numero" required >
						</div>
						<div class="row">
							<div class="title title-complemento">
								Complemento
							</div>
						</div>
						<div class="row">
							<input name="complemento" id="complemento" class="Rectangle" >
						</div>
						<div class="row">
							<div class="title-estado">
								Estado
							</div>
							<div class="title-cidade">
								Cidade
							</div>
						</div>
						<div class="row">
							<input name="estado" id="estado" class="Rectangle-estado" data-autocomplete-state required>
							<input name="cidade" id="cidade" class="Rectangle-cidade" data-autocomplete-city required>
						</div>
						<?php submit_template(); ?>
					</form>
				</div>
			</div>
				
			<?php
		}
	break;
	case 4:
		if(is_user_logged_in()) {
			if(
					isset($_REQUEST['post_id']) &&
					LocalBiz::check_post_owner($_REQUEST['post_id']) &&
					isset($_REQUEST['cep']) &&
					isset($_REQUEST['endereco']) &&
					isset($_REQUEST['numero'])
			) {
				$post_id = sanitize_text_field($_REQUEST['post_id']);
				update_post_meta($post_id, 'cep', sanitize_text_field($_REQUEST['cep']));
				update_post_meta($post_id, 'endereco', sanitize_text_field($_REQUEST['endereco']));
				update_post_meta($post_id, 'bairro', sanitize_text_field($_REQUEST['bairro']));
				update_post_meta($post_id, 'numero', sanitize_text_field($_REQUEST['numero']));
				update_post_meta($post_id, 'complemento', sanitize_text_field($_REQUEST['complemento']));
				update_post_meta($post_id, 'estado', sanitize_text_field($_REQUEST['estado']));
				update_post_meta($post_id, 'cidade', sanitize_text_field($_REQUEST['cidade']));
				update_post_meta($post_id, 'estagio', sanitize_text_field($_REQUEST['estagio']));
			}
		?>
			<div class="passo-4">
				<div class="Onboarding-2">
					<div class="row">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/group-30.svg" class="Group-30">
					</div>
					<?php LocalBiz::display_error(); ?>
					<div class="row">
						<div class="Rectangle-2">
							<div class="row">
								<div class="Title-Copy">
									Cadastrando seu negócio
								</div>
							</div>
							<div class="row">
								<div class="Oval-Copy">
									<span>1</span>
								</div>
								<div class="Oval-Copy">
									<span>2</span>
								</div>
								<div class="Oval">
									<span>3</span>
								</div>
								<div class="Oval-Copy">
									<span>4</span>
								</div>
								<div class="Oval-Copy">
									<span>5</span>
								</div>
							</div>
						</div>
					</div>
					<form class="localbiz_registerform" id="localbiz_registerform2" method="post">
						<?php localbiz_hidden_fields(); ?>
						<div class="row">
							<div class="Title-Copy-2">
								Tipo e tamanho do seu negócio
							</div>
						</div>
						<div class="retangle-left">
							<div class="row">
								<div class="Seu-negcio-possui-f">
									Seu negócio possui fins lucrativos?
								</div>
							</div>
							<div class="row">
								<input type="radio" id="fins-sim" name="fins" value="sim" <?php echo array_key_exists('fins', $_REQUEST) && sanitize_text_field($_REQUEST['fins']) == 'sim' ? 'checked="checked"' : ''; ?> />
									<label class="Lorem-Ipsum" for="fins-sim">Sim, possui fins lucrativos.</label>
							</div>
							<div class="row">
								<input type="radio" id="fins-nao" name="fins" value="nao" <?php echo array_key_exists('fins', $_REQUEST) && sanitize_text_field($_REQUEST['fins']) == 'nao' ? 'checked="checked"' : ''; ?> />
									<label class="Lorem-Ipsum" for="fins-nao">Não possui fins lucrativos.</label>
							</div>
							<div class="row">
								<div class="Qual-o-tamanho-do">
									Qual é o tamanho do seu negócio?
								</div>
							</div>
							<div class="row">
								<input type="radio" id="tamanho-individual" name="tamanho" value="Individual" <?php echo array_key_exists('tamanho', $_REQUEST) && sanitize_text_field($_REQUEST['tamanho']) == 'Individual' ? 'checked="checked"' : ''; ?> />
									<label class="Lorem-Ipsum" for="tamanho-individual">Individual</label>
							</div>
							<div class="row">
								<input type="radio" id="tamanho-4" name="tamanho" value="4" <?php echo array_key_exists('tamanho', $_REQUEST) && sanitize_text_field($_REQUEST['tamanho']) == '4' ? 'checked="checked"' : ''; ?> />
									<label class="Lorem-Ipsum" for="tamanho-4">1 - 4 Funcionários</label>
							</div>
							<div class="row">
								<input type="radio" id="tamanho-5" name="tamanho" value="5" <?php echo array_key_exists('tamanho', $_REQUEST) && sanitize_text_field($_REQUEST['tamanho']) == '5' ? 'checked="checked"' : ''; ?> />
									<label class="Lorem-Ipsum" for="tamanho-5">5 - 10 Funcionários</label>
							</div>
							<div class="row">
								<input type="radio" id="tamanho-11" name="tamanho" value="11" <?php echo array_key_exists('tamanho', $_REQUEST) && sanitize_text_field($_REQUEST['tamanho']) == '11' ? 'checked="checked"' : ''; ?> />
									<label class="Lorem-Ipsum" for="tamanho-11">11 - 20 Funcionários</label>
							</div>
							<div class="row">
								<input type="radio" id="tamanho-21" name="tamanho" value="21" <?php echo array_key_exists('tamanho', $_REQUEST) && sanitize_text_field($_REQUEST['tamanho']) == '21' ? 'checked="checked"' : ''; ?> />
									<label class="Lorem-Ipsum" for="tamanho-21">21 - 50 Funcionários</label>
							</div>
							<div class="row">
								<input type="radio" id="tamanho-mais" name="tamanho" value="mais" <?php echo array_key_exists('tamanho', $_REQUEST) && sanitize_text_field($_REQUEST['tamanho']) == 'mais' ? 'checked="checked"' : ''; ?> />
									<label class="Lorem-Ipsum" for="tamanho-mais">Mais de 50 Funcionários</label>
							</div>
						</div>
						<?php submit_template(); ?>
					</form>
				</div>
			</div>
	<?php
		} else {
			$_REQUEST['estagio'] = 2;
			$_REQUEST['error'] = 'É necessario estar logado ou criar uma conta na primeira parte do cadastro';
			$url = '/registro-de-usuario' . '?' . http_build_query($_REQUEST);
			wp_redirect($url);
		}
	break;
	case 5:
		if(is_user_logged_in()) {
			if(
					isset($_REQUEST['post_id']) &&
					LocalBiz::check_post_owner($_REQUEST['post_id']) &&
					isset($_REQUEST['fins']) &&
					isset($_REQUEST['tamanho'])
				) {
					$post_id = sanitize_text_field($_REQUEST['post_id']);
					update_post_meta($post_id, 'fins', sanitize_text_field($_REQUEST['fins']));
					update_post_meta($post_id, 'tamanho', sanitize_text_field($_REQUEST['tamanho']));
					update_post_meta($post_id, 'estagio', sanitize_text_field($_REQUEST['estagio']));
			}
			?>
			<div class="passo-5">
				<div class="Onboarding-2">
					<div class="row">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/group-30.svg" class="Group-30">
					</div>
					<?php LocalBiz::display_error(); ?>
					<div class="row">
						<div class="Rectangle-2">
							<div class="row">
								<div class="Title-Copy">
									Cadastrando seu negócio
								</div>
							</div>
							<div class="row">
								<div class="Oval-Copy">
									<span>1</span>
								</div>
								<div class="Oval-Copy">
									<span>2</span>
								</div>
								<div class="Oval-Copy">
									<span>3</span>
								</div>
								<div class="Oval">
									<span>4</span>
								</div>
								<div class="Oval-Copy">
									<span>5</span>
								</div>
							</div>
						</div>
					</div>
					<form class="localbiz_registerform" id="localbiz_registerform2" method="post">
						<?php localbiz_hidden_fields(); ?>
						<div class="row">
							<div class="Title-Copy-2">
								Identificando o Seu Negócio Local
							</div>
						</div>
						<div class="row">
							<div class="title">
								CNPJ
							</div>
						</div>
						<div class="row">
							<input name="cnpj" id="cnpj"  class="Rectangle" value="<?php echo array_key_exists('cnpj', $_REQUEST) ? sanitize_text_field($_REQUEST['cnpj']) : ''; ?>" required="required" >
						</div>
						<div class="row">
							<div class="Check-box-vazio">
								<input type="checkbox" name="tem_cnpj" id="tem_cnpj" class="" value="N" >
								<label for="tem_cnpj">Ainda não possuo CNPJ</label>
							</div>
							<input type="checkbox" name="tem_cnpj" id="tem_cnpj_sim" class="hide" value="S" checked="checked" >
						</div>
						<div class="row">
							<div class="title">
								Razão Social
							</div>
						</div>
						<div class="row">
							<input name="razao" id="razao"  class="Rectangle" value="<?php echo array_key_exists('razao', $_REQUEST) ? sanitize_text_field($_REQUEST['razao']) : ''; ?>" required="required" >
						</div><?php
						LocalBiz::category_subcategory_select_form();?>
						<?php submit_template(); ?>
					</form>
				</div>
			</div><?php
		} else {
			$_REQUEST['estagio'] = 2;
			$_REQUEST['error'] = 'É necessario estar logado ou criar uma conta na primeira parte do cadastro';
			$url = '/registro-de-usuario' . '?' . http_build_query($_REQUEST);
			wp_redirect($url);
		}
	break;
	case 6:
		if(is_user_logged_in()) {
			if(
				isset($_REQUEST['post_id']) &&
				LocalBiz::check_post_owner($_REQUEST['post_id']) &&
				( isset($_REQUEST['cnpj']) || 'N' == sanitize_text_field($_REQUEST['tem_cnpj']) ) &&
				isset($_REQUEST['razao']) &&
				isset($_REQUEST['tem_cnpj'])
			) {
				$post_id = sanitize_text_field($_REQUEST['post_id']);
				$tem_cnpj = sanitize_text_field($_REQUEST['tem_cnpj']);
				if($tem_cnpj == 'S') {
					update_post_meta($post_id, 'cnpj', sanitize_text_field($_REQUEST['cnpj']));
				}
				update_post_meta($post_id, 'tem_cnpj', $tem_cnpj);
				update_post_meta($post_id, 'razao', sanitize_text_field($_REQUEST['razao']));
				update_post_meta($post_id, 'estagio', sanitize_text_field($_REQUEST['estagio']));
				$cat_id = isset($_REQUEST['cat']) && !empty($_REQUEST['cat']) ? sanitize_text_field($_REQUEST['cat']) : -1;
				$subcat_id = isset($_REQUEST['subcat']) && !empty($_REQUEST['subcat']) ? sanitize_text_field($_REQUEST['subcat']) : -1;
				$cats = array();
				if($cat_id > 0) $cats[] = $cat_id;
				if($subcat_id > 0) $cats[] = $subcat_id;
				if(!empty($cats)) wp_set_post_terms($post_id, $cats, 'category');?>
				<div class="passo-6">
					<div class="Onboarding-2">
						<div class="row">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/group-30.svg" class="Group-30">
						</div>
						<?php LocalBiz::display_error(); ?>
						<div class="row">
							<div class="Rectangle-2">
								<div class="row">
									<div class="Title-Copy">
										Cadastrando seu negócio
									</div>
								</div>
								<div class="row">
									<div class="Oval-Copy">
										<span>1</span>
									</div>
									<div class="Oval-Copy">
										<span>2</span>
									</div>
									<div class="Oval-Copy">
										<span>3</span>
									</div>
									<div class="Oval-Copy">
										<span>4</span>
									</div>
									<div class="Oval">
										<span>5</span>
									</div>
								</div>
							</div>
						</div>
						<form class="localbiz_registerform" id="localbiz_registerform2" method="post">
							<?php localbiz_hidden_fields(); ?>
							<div class="row">
								<div class="Title-Copy-2">
									Finalizando cadastro
								</div>
							</div>
							<div class="row">
								<div class="subtitle">
									<?php echo sanitize_text_field($_REQUEST['razao']); ?>
								</div>
							</div>
							<div class="row">
								<div class="subtitle-2">
									Sobre seu negócio
								</div>
							</div>
							<div class="row">
								<div class="form-field term-group-wrap">
									<div>
										<div class="row aleft image-title"><label class=""><?php _e( 'Imagem do perfil', 'localbiz' ); ?></label></div>
										<?php $image_id = get_post_meta ( $post_id, 'localbiz-perfil-image-id', true ); ?>
										<input type="hidden" id="localbiz-perfil-image-id"
											name="localbiz-perfil-image-id" value="<?php echo $image_id; ?>">
										<?php
										$imgurl = false;
										if ( $image_id ) {
											$imgurl = wp_get_attachment_image_src ( $image_id, 'thumbnail' );
											if($imgurl) {
												$imgurl = $imgurl[0];
											}
										} 
										if($imgurl == false){
											$imgurl = get_stylesheet_directory_uri().'/img/invalid-name.svg';
										} ?>
										<div id="localbiz-perfil-image-wrapper">
											<div class="img-Oval" style="background-image: url('<?php echo $imgurl; ?>');background-size: auto;">
											</div>
										</div>
										<div class="row col-1">
											<div class="row marginb05">
												<input
													type="button"
													class="button button-secondary ct_tax_media_button Title-Copy-4"
													id="ct_tax_media_button"
													name="ct_tax_media_button"
													value="<?php _e( 'Carregar Imagem', 'localbiz' ); ?>"
												/>
											</div>
											<div class="row">
												<input
													type="button"
													class="button button-secondary ct_tax_media_remove Title-Copy-4"
													id="ct_tax_media_remove"
													name="ct_tax_media_remove"
													value="<?php _e( 'Remover Imagem', 'localbiz' ); ?>"
												/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="title">
									Resumo sobre seu negócio
								</div>
							</div>
							<div class="row">
								<textarea name="resumo" id="resumo"  class="Rectangle" required="required" ><?php echo array_key_exists('resumo', $_REQUEST) ? sanitize_text_field($_REQUEST['resumo']) : ''; ?></textarea>
							</div>
							<div class="row">
								<div class="title">
									Descrição Completa do negócio
								</div>
							</div>
							<div class="row">
								<textarea name="desc" id="desc"  class="Rectangle" required="required" ><?php echo array_key_exists('desc', $_REQUEST) ? sanitize_text_field($_REQUEST['desc']) : ''; ?></textarea>
							</div>
							<div class="row">
								<div class="title">
									Produtos e Serviços
								</div>
							</div>
							<div class="row">
								<input name="produtos" id="produtos"  class="Rectangle" value="<?php echo array_key_exists('produtos', $_REQUEST) ? sanitize_text_field($_REQUEST['produtos']) : ''; ?>" >
							</div>
							<div class="row">
								<div class="subtitle-2">
									Contato do seu negócio
								</div>
							</div>
							<div class="row">
								<div class="title">
									Telefone do negócio
								</div>
							</div>
							<div class="row">
								<input type="tel" name="tel" id="tel"  class="Rectangle" value="<?php echo array_key_exists('tel', $_REQUEST) ? sanitize_text_field($_REQUEST['tel']) : ''; ?>" >
							</div>
							<div class="row">
								<div class="title">
									Email do Negócio
								</div>
							</div>
							<div class="row">
								<input type="email" name="email_localbiz" id="email_localbiz"  class="Rectangle" value="<?php echo array_key_exists('email_localbiz', $_REQUEST) ? sanitize_text_field($_REQUEST['email_localbiz']) : ''; ?>" >
							</div>
							<div class="row">
								<div class="title">
									Site do negócio
								</div>
							</div>
							<div class="row">
								<input type="url" name="site" id="site"  class="Rectangle" value="<?php echo array_key_exists('site', $_REQUEST) ? sanitize_text_field($_REQUEST['site']) : ''; ?>" >
							</div>
							<div class="row">
								<div class="title">
									Instagram do negócio
								</div>
							</div>
							<div class="row">
								<input name="insta" id="insta"  class="Rectangle" value="<?php echo array_key_exists('insta', $_REQUEST) ? sanitize_text_field($_REQUEST['insta']) : ''; ?>" >
							</div>
							<div class="row">
								<div class="title">
									Facebook do negócio
								</div>
							</div>
							<div class="row">
								<input name="facebook" id="facebook"  class="Rectangle" value="<?php echo array_key_exists('facebook', $_REQUEST) ? sanitize_text_field($_REQUEST['facebook']) : ''; ?>" >
							</div>
							<div class="row">
								<div class="title">
									Linkedin do negócio
								</div>
							</div>
							<div class="row">
								<input name="linkedin" id="linkedin"  class="Rectangle" value="<?php echo array_key_exists('linkedin', $_REQUEST) ? sanitize_text_field($_REQUEST['linkedin']) : ''; ?>" >
							</div>
							<?php submit_template(); ?>
						</form>
					</div>
				</div><?php
			}
		} else {
			$_REQUEST['estagio'] = 2;
			$_REQUEST['error'] = 'É necessario estar logado ou criar uma conta na primeira parte do cadastro';
			$url = '/registro-de-usuario' . '?' . http_build_query($_REQUEST);
			wp_redirect($url);
		}
	break;
	case 7:
		if(is_user_logged_in()) {
			if(
				isset($_REQUEST['post_id']) &&
				LocalBiz::check_post_owner($_REQUEST['post_id']) &&
				isset($_REQUEST['resumo']) &&
				isset($_REQUEST['desc']) &&
				isset($_REQUEST['produtos'])
			) {
				$post_id = sanitize_text_field($_REQUEST['post_id']);
				$post_content = sanitize_textarea_field($_REQUEST['desc']);
				$post_excerpt = sanitize_textarea_field($_REQUEST['resumo']);
				wp_update_post(array(
					'ID' => $post_id,
					'post_content' => $post_content,
					'post_excerpt' => $post_excerpt
				));
				update_post_meta($post_id, 'estagio', sanitize_text_field($_REQUEST['estagio']));
				if(isset($_REQUEST['localbiz-perfil-image-id']) && ! empty($_REQUEST['localbiz-perfil-image-id']))
					update_post_meta($post_id, 'localbiz-perfil-image-id', sanitize_text_field($_REQUEST['localbiz-perfil-image-id']));
				if(isset($_REQUEST['tel']) && ! empty($_REQUEST['tel']))
					update_post_meta($post_id, 'tel', sanitize_text_field($_REQUEST['tel']));
				if(isset($_REQUEST['email_localbiz']) && ! empty($_REQUEST['email_localbiz']))
					update_post_meta($post_id, 'email_localbiz', sanitize_text_field($_REQUEST['email_localbiz']));
				if(isset($_REQUEST['site']) && ! empty($_REQUEST['site']))
					update_post_meta($post_id, 'site', sanitize_text_field($_REQUEST['site']));
				if(isset($_REQUEST['insta']) && ! empty($_REQUEST['insta']))
					update_post_meta($post_id, 'insta', sanitize_text_field($_REQUEST['insta']));
				if(isset($_REQUEST['facebook']) && ! empty($_REQUEST['facebook']))
					update_post_meta($post_id, 'facebook', sanitize_text_field($_REQUEST['facebook']));
				if(isset($_REQUEST['linkedin']) && ! empty($_REQUEST['linkedin']))
					update_post_meta($post_id, 'linkedin', sanitize_text_field($_REQUEST['linkedin']));
				if(isset($_REQUEST['produtos']) && ! empty($_REQUEST['produtos'])) {
					$pEs_text = sanitize_text_field($_REQUEST['produtos']);
					$pEs = explode(",", $pEs_text);
					$pEs_terms = array();
					foreach($pEs as $produto) {
						$pterm = get_term_by('name', esc_attr($produto), 'produtoservico');
						if($pterm !== false) {
							$pEs_terms[] = $pterm->term_id;
						} else {
							$pterm = wp_insert_term($produto, 'produtoservico');
							if(! $pterm instanceof WP_Error) {
								$pEs_terms[] = $pterm['term_id'];
							}
						}
					}
					wp_set_post_terms($post_id, $pEs_terms, 'produtoservico');
				}
			}?>
			<div class="passo-7 Cadastro-Efetuado">
				<div class="Rectangle">
					<div class="row">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/group-30.svg" class="Group-30">
					</div>
					<?php LocalBiz::display_error(); ?>
					<div class="row">
						<!-- svg -->
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/noun-check-1934251.svg"
	     					class="noun_Check_1934251">
					</div>
					<div class="row">
						<div class="Title-Copy-2">
							Cadastro Realizado 
							com Sucesso!
						</div>
					</div>
				</div>
			</div><?php
		} else {
			$_REQUEST['estagio'] = 2;
			$_REQUEST['error'] = 'É necessario estar logado ou criar uma conta na primeira parte do cadastro';
			$url = '/registro-de-usuario' . '?' . http_build_query($_REQUEST);
			wp_redirect($url);
		}
	break;
} ?>


<?php do_action( 'after_signup_form' ); ?>