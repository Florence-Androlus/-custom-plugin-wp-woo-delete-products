<?php
namespace wdp;
use wdp\Classes\Readfile;

class WDPSettingsPage {
	public function __construct() {
		// Register the settings page.
		add_action( 'admin_menu', array( $this, 'register_settings' ) );

		// Register the sections.
		add_action( 'admin_init', array( $this, 'register_sections' ) );

		// Register the fields.
		add_action( 'admin_init', array( $this, 'register_fields' ) );

	}

	// Register settings.
	public function register_settings(){
		add_menu_page(
			'Settings', // The title of your settings page.
			'Woodeleteproduct', // The name of the menu item.
			'manage_options', // The capability required for this menu to be displayed to the user.
			'WDP-settings', // The slug name to refer to this menu by (should be unique for this menu).
			array( $this, 'render_settings_page' ), // The callback function used to render the settings page.
			'dashicons-database-remove', // The icon to be used for this menu.
			59 // The position in the menu order this one should appear.
		);
	}

	// Register sections.
	public function register_sections(){
		add_settings_section( 'WDP-settings-section', '', array(), 'MyMenuName' );
	}

	// Register fields.
	public function register_fields(){
		$fields = array(
			'WDP-custom-field' => array(
				'section' => 'WDP-settings-section',
				'label' => 'prenom',
				'description' => 'prenom',
				'type' => 'text',
			),
		);
		foreach( $fields as $id => $field ){
			$field['id'] = $id;
			add_settings_field( $id, $field['label'], array( $this, 'render_field' ), 'WDP-settings-section', $field['section'], $field );
			register_setting( 'WDP-settings-section', $id );
		}
	}

	// Render individual fields.
	public function render_field( $field ){
		$value = get_option( $field['id'] );

		switch ( $field['type'] ) {
			case 'textarea':{
				echo "<textarea name='{$field['id']}' id='{$field['id']}'>$value</textarea>";
				break;
			}
			case 'checkbox':{
				echo "<input type='checkbox' name='{$field['id']}' id='{$field['id']}' " . ($value === '1' ? 'checked' : '') . " />";
				break;
			}
			case 'wysiwyg':{
				wp_editor($value, $field['id']);
				break;
			}
			case 'select':{
				if( is_array( $field['options'] ) && !empty( $field['options'] ) ) {
					echo "<select name='{$field['id']}' id='{$field['id']}'>";
					foreach( $field['options'] as $key => $option ){
						echo "<option value='$key' ". ($value === $key ? 'selected' : '') .">$option</option>";
					}
					echo "</select>";
				}
				break;
			}
			default:{
				echo "<input name='{$field['id']}' id='{$field['id']}' type='{$field['type']}'>$value</textarea>";
				break;
			}
		}

		if( isset($field['description']) ){
			echo "<p class='description'>{$field['description']}</p>";
		}
	}

	// Render the settings page.
	public function render_settings_page(){

?>

	<div class="div_conteneur_parent">
			<div class="div_conteneur_page"  >
				<div class="div_int_page">			
				
					<div class="div_h1" >
					<h1>Suppression des produits fournisseur via fichier CSV</h1>
					</div>
											
		
					<div class="div_saut_ligne">
					</div>		
					
					<div style="width:100%;height:auto;text-align:center;">
								
					<div style="width:800px;display:inline-block;" id="conteneur">
					
						<div class="centre">
							<div class="titre_centre">
							<form id="formulaire" name="formulaire" enctype="multipart/form-data" method="post">

								<input name="fichier" type="file"  accept=".csv" id="fichier" size="200" class="liste">
								<div class="liste_div" >
									<input type="button" id="envoyer" name="envoyer" class="liste" style="width:100px;" value="Executer" onClick="document.getElementById('formulaire').submit();" />
								</div>						
							</form>					
							</div>	
						</div>

						
					</div>
					
					</div>
		
					<div class="div_saut_ligne" style="height:50px;">
					</div>

	<?php 

if(isset($_FILES["fichier"]["tmp_name"]))
{

    $fileName = $_FILES["fichier"]["tmp_name"];

    if ($_FILES["fichier"]["size"] > 0) {
		
		$compteur=Readfile::init($fileName);
			
				if($compteur>0){
					?>

						<div style="width:auto;display:block;height:auto;text-align:center;background-color:#ccccff;border:#7030a0 1px solid;padding-top:12px;box-shadow: 6px 6px 0px #aaa;color:#7030a0;">
										
							<?php echo "<h2>".$compteur." produits ont été mis en status brouillon et redirigé dans la categorie correspondante</h2>"; ?>

						</div>
																						
					</div>
				</div>
			</div>	

<?php
				}				
			}
		}
	}
}									
