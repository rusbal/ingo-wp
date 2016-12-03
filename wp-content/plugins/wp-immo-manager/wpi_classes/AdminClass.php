<?php
/**
 * Created by
 * User: Media-Store.net
 * Date: 21.08.2016
 * Time: 22:14
 */

namespace wpi_classes;

use \wpi_classes\WpOptionsClass;

class AdminClass
{

	public $versionStatus;
	public $versionName;

	public function __construct()
	{
		// Laden der Optinen aus DB Admin-Options
		$this->optionsClass = new WpOptionsClass;
		$this->options = $this->optionsClass->optionslist;

		$this->single_preise = get_option('wpi_single_preise');
		$this->single_flaechen = get_option('wpi_single_flaechen');
		$this->single_ausstattung = get_option('wpi_single_ausstattung');
		$this->html_inject = get_option('wpi_html_inject');
		$this->html = get_option('wpi_custom_html');
		$this->uploadUrl = get_option('wpi_upload_url');
		$this->html_inject = get_option('wpi_html_inject');
		$this->html = get_option('wpi_custom_html');
		$this->tabs = get_option('wpi_single_view_tabs');
		$this->smart_nav = get_option('wpi_smartnav');

		$this->versionStatus = $this->versionStatus();
		$this->versionName = $this->versionName();
	}

	private function versionStatus()
	{
		if ($this->options['wpi_pro'] === 'true') {
			return $this->versionStatus = true;
		} else {
			return $this->versionStatus = false;
		}
	}

	private function versionName()
	{
		if ($this->versionStatus) $this->versionName = 'PRO';
		else $this->versionName = 'FREE';

		return $this->versionName;
	}

	// Views & Functions Forms
	public function auto_sync_form()
	{
		ob_start();

		?>

		<form method="post" action="options.php">
			<?php settings_fields('wpi_shedule_group'); ?>
			<?php do_settings_sections('wpi_shedule_group'); ?>
			<fieldset>
				<label for="wpi_shedule_time">
					<?php echo __('Auswahl für die Automatische Steuerung der Synchronisation', WPI_PLUGIN_NAME); ?>
				</label>
				<table class="form-table">
					<tr valign="top" class="col-sm-12 col-md-4">
						<td>
							<input type="radio" name="wpi_shedule_time"
							       value="hourly"<?php echo(get_option('wpi_shedule_time') == 'hourly' ? 'checked="checked"' : ''); ?> />
						</td>
						<td>
							<?php echo __('Stündliche Synchronisation', WPI_PLUGIN_NAME); ?>
						</td>
					</tr>

					<tr valign="top" class="col-sm-12 col-md-4">
						<td>
							<input type="radio" name="wpi_shedule_time"
							       value="twicedaily"<?php echo(get_option('wpi_shedule_time') == 'twicedaily' ? 'checked="checked"' : ''); ?> />
						</td>
						<td>
							<?php echo __('Halbtägliche Synchronisation', WPI_PLUGIN_NAME); ?>
						</td>
					</tr>

					<tr valign="top" class="col-sm-12 col-md-4">
						<td>
							<input type="radio" name="wpi_shedule_time"
							       value="daily"<?php echo(get_option('wpi_shedule_time') == 'daily' ? 'checked="checked"' : ''); ?> />
						</td>
						<td>
							<?php echo __('Tägliche Synchronisation', WPI_PLUGIN_NAME); ?>
						</td>
					</tr>
				</table>

			</fieldset>
			<?php submit_button(); ?>
		</form>

		<?php
		return ob_get_clean();
	}

	// Single Seite Forms
	public function SingleViewSelectForm()
	{
		ob_start();

		$this->versionStatus === true ? $disable_radio_view = '' : $disable_radio_view = 'disabled';
		$this->versionStatus === true ? $accordion = 'accordion' : $accordion = 'tabs';
		$this->versionStatus === true ? $onepage = 'onepage' : $onepage = 'tabs';
		?>
		<div class="col-xs-12 list-group single-view">
			<div class="list-group-item col-xs-12">
				<h3 class="text-danger col-xs-12"><?php echo __('Aussehen der Single-Page', WPI_PLUGIN_NAME); ?></h3>
				<div class="clearfix"></div>
				<div id="radio-tabs" class="radio single-radio col-xs-12 col-md-4">
					<label>
						<input type="radio" name="wpi_single_view" id="wpi_single_view1"
						       value="tabs" <?php echo $this->options['wpi_single_view'] === 'tabs' ? 'checked="checked"' : ''; ?>>
						Tabs (<a data-toggle="modal" data-target="#tabsModal">Beispiel</a>)
					</label>
				</div>
				<!-- Modal -->
				<div class="modal fade" id="tabsModal" tabindex="-1" role="dialog"
				     aria-labelledby="meinModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"
								        aria-label="Schließen"><span
										aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="tabsModalLabel">Tabs</h4>
							</div>
							<div class="modal-body">
								<img class="img-responsive"
								     src="<?php echo WPI_PLUGIN_URI . 'images/snap_tabs.png' ?>"/>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default"
								        data-dismiss="modal">Schließen
								</button>
							</div>
						</div>
					</div>
				</div>
				<div id="radio-tabs" class="radio single-radio col-xs-12 col-md-4  <?php echo $disable_radio_view; ?>">
					<label class="<?php echo $disable_radio_view; ?>">
						<input type="radio" name="wpi_single_view" id="wpi_single_view2"
						       class="<?php echo $disable_radio_view; ?>"
						       value="<?php echo $accordion; ?>" <?php echo $this->options['wpi_single_view'] === 'accordion' ? 'checked="checked"' : ''; ?>>
						Accordion (<a data-toggle="modal"
						              data-target="#accordionModal">Beispiel</a>) <span
							class="badge">WPIM-Pro</span>
					</label>
				</div>
				<!-- Modal -->
				<div class="modal fade" id="accordionModal" tabindex="-1" role="dialog"
				     aria-labelledby="meinModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"
								        aria-label="Schließen"><span
										aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="accordionModalLabel">
									Accordion</h4>
							</div>
							<div class="modal-body">
								<img class="img-responsive"
								     src="<?php echo WPI_PLUGIN_URI . 'images/snap_accordion.png' ?>"/>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default"
								        data-dismiss="modal">Schließen
								</button>
							</div>
						</div>
					</div>
				</div>
				<div id="radio-onePage"
				     class="radio single-radio col-xs-12 col-md-4 <?php echo $disable_radio_view; ?>">
					<label class="<?php echo $disable_radio_view; ?>">
						<input type="radio" name="wpi_single_view" id="wpi_single_view3"
						       class="<?php echo $disable_radio_view; ?>"
						       value="<?php echo $onepage; ?>" <?php echo $this->options['wpi_single_view'] === 'onepage' ? 'checked="checked"' : ''; ?>>
						OnePage (<a data-toggle="modal" data-target="#onePageModal">Beispiel</a>)
						<span class="badge">WPIM-Pro</span>
					</label>
				</div>
				<!-- Modal -->
				<div class="modal fade" id="onePageModal" tabindex="-1" role="dialog"
				     aria-labelledby="meinModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"
								        aria-label="Schließen"><span
										aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="onePageModalLabel">OnePage</h4>
							</div>
							<div class="modal-body">
								<p class="lead">Ist der Zeit nicht Verfügbar...</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default"
								        data-dismiss="modal">Schließen
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		return ob_get_clean();
	}

	public function SingleTabsNames()
	{
		ob_start();
		$single_tabs = $this->options['wpi_single_view_tabs'];
		?>
		<div class="list-group radio-tabs hidden">
			<div class="col-xs-12 list-group-item">
				<h3 class="text-danger"><?php echo __('Beschriftung der Tabs', WPI_PLUGIN_NAME); ?></h3>
				<div class="clearfix"></div>
				<div class="form-group col-xs-6">
					<label for="details"><?php echo __('Details', WPI_PLUGIN_NAME); ?></label>
					<input type="text" class="form-control" name="wpi_single_view_tabs[details]"
					       id="wpi_single_view_tabs[details]"
					       value="<?php echo esc_html($single_tabs['details']); ?>">
				</div>
				<div class="form-group col-xs-6">
					<label
						for="beschreibung"><?php echo __('Beschreibung', WPI_PLUGIN_NAME); ?></label>
					<input type="text" class="form-control"
					       name="wpi_single_view_tabs[beschreibung]"
					       id="wpi_single_view_tabs[beschreibung]"
					       value="<?php echo esc_html($single_tabs['beschreibung']); ?>">
				</div>
				<div class="form-group col-xs-6">
					<label for="bilder"><?php echo __('Bilder', WPI_PLUGIN_NAME); ?></label>
					<input type="text" class="form-control" name="wpi_single_view_tabs[bilder]"
					       id="wpi_single_view_tabs[bilder]"
					       value="<?php echo esc_html($single_tabs['bilder']); ?>">
				</div>
				<div class="form-group col-xs-6">
					<label
						for="kontakt"><?php echo __('Kontaktperson', WPI_PLUGIN_NAME); ?></label>
					<input type="text" class="form-control" name="wpi_single_view_tabs[kontakt]"
					       id="wpi_single_view_tabs[kontakt]"
					       value="<?php echo esc_html($single_tabs['kontakt']); ?>">
				</div>
				<div class="clearfix"></div>
			</div>
		</div>

		<?php
		return ob_get_clean();
	}

	public function SingleOnePagePanels()
	{
		ob_start();
		?>
		<div class="list-group radio-onePage hidden">
			<div class="col-xs-12 list-group-item">
				<h3 class="text-danger"><?php echo __('Beschriftung OnePage Panels', WPI_PLUGIN_NAME); ?></h3>
				<div class="clearfix"></div>
				<?php
				foreach ($this->options['wpi_single_onePage'] as $key => $value):
					?>
					<div class="form-group col-xs-6">
						<label for="<?= $key; ?>"><?php echo __($value, WPI_PLUGIN_NAME); ?></label>
						<input type="text" class="form-control" name="wpi_single_onePage[<?= $key; ?>]"
						       id="wpi_single_onePage[<?= $key; ?>]"
						       value="<?php echo esc_html($this->options['wpi_single_onePage'][$key]); ?>">
					</div>
					<?php
				endforeach;

				?>

			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public function SingleAvatarForm()
	{
		ob_start();
		?>
		<div class="col-xs-12 list-group single-view">
			<div class="list-group-item col-xs-12">
				<h3 class="text-danger col-xs-12"><?php echo __('Avatar bei Kontaktdaten anzeigen', WPI_PLUGIN_NAME); ?></h3>
				<div class="clearfix"></div>
				<div class="radio">
					<label>
						<input type="radio" name="wpi_avatar[active]" id="wpi_avatar[active]1" value="true"
							<?php echo $this->options['wpi_avatar']['active'] == 'true' ? 'checked' : '' ?>>
						Aktivieren
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="wpi_avatar[active]" id="wpi_avatar[active]2" value="false"
							<?php echo $this->options['wpi_avatar']['active'] == 'false' ? 'checked' : '' ?>>
						Nicht aktivieren
					</label>
				</div>

				<div class="form-group">
					<label for="wpi_avatar[avatar_url]" class="col-sm-2 control-label">Avatar URL</label>
					<div class="col-sm-10">
						<input type="url" class="form-control" name="wpi_avatar[avatar_url]"
						       id="wpi_avatar[avatar_url]"
						       value="<?php echo $this->options['wpi_avatar']['avatar_url'] ?>">
					</div>
				</div>
				<div class="clearfix"></div>
				<br/>
				<div class="alert alert-info">
					Wird die Avatar URL leer gelassen, der Avatar jedoch aktiviert ist, wird versucht ein
					<a href="https://de.gravatar.com/" target="_blank" class="alert-link">Gravatar</a> aus der Email in
					der XML darzustellen.
				</div>
			</div>
		</div>

		<?php
		return ob_get_clean();
	}

	public function SingleActivateSmartNavigation()
	{
		ob_start();
		?>

		<div class="col-xs-12 list-group single-view">
			<div class="list-group-item col-xs-12">
				<h3 class="text-danger col-xs-12"><?php echo __('Smart Navigation aktivieren', WPI_PLUGIN_NAME); ?></h3>
				<div class="clearfix"></div>
				<div class="radio">
					<label>
						<input type="radio" name="wpi_show_smartnav" id="wpi_show_smartnav1" value="true"
							<?php echo $this->options['wpi_show_smartnav'] == 'true' ? 'checked' : '' ?>>
						Aktivieren
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="wpi_show_smartnav" id="wpi_show_smartnav2" value="false"
							<?php echo $this->options['wpi_show_smartnav'] == 'false' ? 'checked' : '' ?>>
						Nicht aktivieren
					</label>
				</div>
				<div class="alert alert-info">
					Die einzelnen Buttons der Navigation können unter <a href="#features" role="tab" data-toggle="tab">Features</a>
					festgelegt werden.
				</div>
			</div>
		</div>


		<?php
		return ob_get_clean();
	}

	// Features
	public function smart_navi_setup()
	{
		ob_start();
		?>
		<div class="alert alert-info">Wenn das Text / Icon Feld frei gelassen wird, werden diese nicht angezeigt.</div>

		<?php $offsetItem = 0; ?>

		<?php foreach ($this->smart_nav as $option) : ?>
		<div class="col-xs-12">
			<strong>Navigations-Link <?= $offsetItem + 1 ?></strong>
			<div class="clearfix"></div>
			<div class="form-group col-md-4">
				<?php //zeigen($option) ?>
				<label for="beispielFeldName2"> Text / Icon</label>
				<input type="text" class="form-control" name="wpi_smartnav[<?= $offsetItem; ?>][beschreibung]"
				       value="<?php echo esc_html($option['beschreibung']) ?>">
			</div>
			<div class="form-group col-md-4">
				<label for="beispielFeldEmail2"> Title</label>
				<input type="text" class="form-control" name="wpi_smartnav[<?= $offsetItem; ?>][title]"
				       value="<?php echo esc_html($option['title']) ?>">
			</div>
			<div class="form-group col-md-4">
				<label for="beispielFeldEmail2"> Link - Ziel</label>
				<input type="text" class="form-control" name="wpi_smartnav[<?= $offsetItem; ?>][link]"
				       value="<?php echo esc_html($option['link']) ?>">
			</div>
			<div class="clearfix"></div>
			<hr/>
		</div>
		<?php
		$offsetItem++;

	endforeach; ?>


		<?php
		return ob_get_clean();
	}

	// Dashboard Widget Immobilien
	static function wpi_dashboard_text()
	{
		$count_posts = wp_count_posts('wpi_immobilie');
		$terms = get_terms(array('vermarktungsart', 'objekttyp'), array(
			'hide_empty' => false,
		));
		$count_terms = wp_count_terms(array('objekttyp', 'vermarktungsart'));
		?>
		<div class="widget-container">
			<div class="quick-links">
				<h3>Quick-Links</h3>
				<hr>
				<ul>
					<li><a href="admin.php?page=wp-immo-manager%2Fwpi_admin.php">Settings</a></li>
					<li><a href="edit.php?post_type=wpi_immobilie">Alle Immobilien</a></li>
					<li><a href="edit-tags.php?taxonomy=vermarktungsart&post_type=wpi_immobilie">Vermarktungsarten</a>
					</li>
					<li><a href="edit-tags.php?taxonomy=objekttyp&post_type=wpi_immobilie">Objekttypen</a></li>
				</ul>
			</div>
			<div class="statistik">
				<h3>Statistik</h3>
				<hr>
				<ul>
					<li><strong>Anzahl veröffentlichte Immobilien gesamt:</strong>
						<span class="count"><?php echo $count_posts->publish; ?></span></li>
				</ul>
				<ul>
					<?php

					foreach ($terms as $term):
						echo '<li>';
						echo '<strong>' . $term->name.':</strong>' ;
						echo '<span class="count">' . $term->count . '</span>';
						echo '</li>';
					endforeach;

					?>
				</ul>
			</div>
		</div>
		<?php

	}


}