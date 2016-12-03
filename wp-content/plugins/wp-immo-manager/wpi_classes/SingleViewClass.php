<?php
/**
 * User: Media-Store.net
 * Date: 13.09.2016
 * Time: 09:35
 */

namespace wpi_classes;

use wpi_classes\AdminClass;
use wpi_classes\WpOptionsClass;

class SingleViewClass
{

	function __construct()
	{

		// auslesen der globalen Post Meta
		global $post;
		$this->post = $post;
		$this->meta = get_post_meta($post->ID);

		// Auslesen der Kategorien
		$taxonomies = get_the_taxonomies();
		// Objekttypen
		$objekttyp = strstr($taxonomies['objekttyp'], ' ');
		$objekttyp = trim($objekttyp, '.');
		// Vermarktungsarten
		$vermarktung = strstr($taxonomies['vermarktungsart'], ' ');
		$vermarktung = trim($vermarktung, '.');
		/**
		 * Firmenangaben -> if serialized
		 * @return array
		 * else
		 * @return string
		 */
		$firma = $this->meta['anbieterfirma'][0];
		if (strpos($firma, ";")) {
			$firma = explode(';', $firma);
		}
		// Laden der Optinen aus DB
		$optionsClass = new WpOptionsClass();
		$this->options = $optionsClass->wpi_get_options();
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

		// Info über Versionsstaus
		$admin = new AdminClass;
		$this->pro = $admin->versionStatus;

		$this->objekttyp = $objekttyp;
		$this->vermarktung = $vermarktung;
		$this->anbieterkennung = $this->meta['anbieterkennung'][0];
		$this->firma = $firma;
		$this->objektkategorie_array = unserialize($this->meta['objektkategorie'][0]);
		$this->geodaten = unserialize($this->meta['geodaten'][0]);
		$this->kontaktperson = unserialize($this->meta['kontaktperson'][0]);
		$this->preise = unserialize($this->meta['preise'][0]);
		$this->flaechen = unserialize($this->meta['flaechen'][0]);
		$this->ausstattung = unserialize($this->meta['ausstattung'][0]);
		$this->zustand_angaben = unserialize($this->meta['zustand_angaben'][0]);
		$this->anhaenge = unserialize($this->meta['anhaenge'][0]);
		$this->freitexte = unserialize($this->meta['freitexte'][0]);
		$this->verwaltung_objekt = unserialize($this->meta['verwaltung_objekt'][0]);
		$this->verwaltung_techn = unserialize($this->meta['verwaltung_techn'][0]);
		$this->anhang = $this->help_handle_array($this->anhaenge, 'anhang');
		$this->bilder = $this->anhang['bilder'];

		// Gravatar
		if (isset($this->kontaktperson['email_zentrale'])):
			$grav_hash = md5($this->kontaktperson['email_zentrale']);
		elseif (isset($this->kontaktperson['email_direkt'])):
			$grav_hash = md5($this->kontaktperson['email_direkt']);
		endif;
		$this->gravatar = 'https://www.gravatar.com/avatar/' . $grav_hash . '?s=200&d=mm';


		// Überprüfung ob die Optionen für Preise und Flächen aus DB in den Meta vorhanden sind
		$this->preis = array();
		$this->flaeche = array();

		if (!empty($this->single_preise)):
			foreach ($this->single_preise as $preis_key => $preis_value) {
				if (array_key_exists($preis_key, $this->preise)):
					$this->preis[$preis_value] = $this->preise[$preis_key];
				endif;
			}
		endif;

		if (!empty($this->single_flaechen)):
			foreach ($this->single_flaechen as $fl_key => $fl_value) {
				if (array_key_exists($fl_key, $this->flaechen)):
					$this->flaeche[$fl_value] = $this->flaechen[$fl_key];
				endif;
			}
		endif;
	}

	/**
	 * Templates...
	 * Tabs, Accordion, OnePage
	 */
	//TODO Tabs Template
	//TODO Accordion Template

	/**
	 * Function to Render the OnePage View
	 * @return \html
	 */
	public function onePage()
	{
		ob_start();
		?>

		<section id="top" <?php post_class('single-immobilie-onepage'); ?>>

			<?php if ($this->options['wpi_show_smartnav'] == 'true'): ?>
				<div class="row">
					<div class="smart-navi col-xs-12">
						<?php echo $this->smart_navigation(); ?>
					</div>
				</div>
			<?php endif; ?>
			<div id="wpi-main" class="site-main col-xs-12" role="main">

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="row">
						<div class="col-xs-12 col-sm-8">
							<?php echo $this->imageslider(); ?>
						</div>
						<div class="col-xs-12 col-sm-4">
							<?php echo $this->eckdaten(); ?>
							<div class="meta">
								<em>
									Erstellt am: <?php echo $this->post->post_date; ?> <br/>
									Zuletzt geändert: <?php echo $this->post->post_modified; ?>
								</em>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 title">
							<h2><?php echo $this->wpim_post_title(); ?></h2>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 col-md-8">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?= __($this->options['wpi_single_onePage']['beschreibung'], WPI_PLUGIN_NAME); ?></h3>
								</div>
								<div class="panel-body">
									<div
										class="beschreibung"><?php echo $this->get_freitext('objektbeschreibung'); ?></div>
									<?php $arg = $this->get_freitext('lage'); ?>
									<?php if (!empty($arg)): ?>
										<h3 class="lage"><?= __('Lage', WPI_PLUGIN_NAME); ?></h3>
										<div class="lage"><?php echo $this->get_freitext('lage'); ?></div>
									<?php endif; ?>
									<?php $arg = $this->get_freitext('austatt_beschr'); ?>
									<?php if (!empty($arg)): ?>
										<h3 class="ausstattungsbeschreibung"><?= __('Ausstattungsbeschreibung', WPI_PLUGIN_NAME); ?></h3>
										<div class="ausstattung"><?php
											echo $this->get_freitext('austatt_beschr'); ?>
										</div>
									<?php endif; ?>
									<div id="modal-button" class="text-right">
										<!-- Button, der das Modal aufruft -->
										<button type="button" class="btn btn-default btn-lg" data-toggle="modal"
										        data-target="#post">
											Ganze Beschreibung ansehen
										</button>
									</div>
									<div class="custom-beschreibung-div"><?php
										if ('' != $this->html_inject && $this->html_inject === 'beschreibung'):
											echo do_shortcode($this->html);
										endif;
										?>
									</div>
								</div>
							</div>
							<!-- Objektbeschreibung -->
							<div id="kontakt" class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?= __($this->options['wpi_single_onePage']['kontakt'], WPI_PLUGIN_NAME); ?></h3>
								</div>
								<div class="panel-body">
									<div class="col-xs-12 col-md-6">
										<?php echo $this->get_kontakt(); ?>
									</div>
									<div class="col-xs-12 col-md-6">
										<?php if ($this->options['wpi_avatar']['active'] === 'true'): ?>
											<div class="avatar-div text-center">
												<img alt="Gravatar"
												     src="<?php echo !empty($this->options['wpi_avatar']['avatar_url']) ? $this->options['wpi_avatar']['avatar_url'] : $this->gravatar; ?>"
												     class="avatar">
											</div>
										<?php endif; ?>
										<div class="firma">
											<?php echo $this->get_firma(); ?>
										</div>
									</div>
									<div class="custom-kontakt-div"><?php
										if ('' != $this->html_inject && $this->html_inject === 'kontaktperson'):
											echo do_shortcode($this->html);
										endif;
										?>
									</div>
								</div>
							</div>
							<!-- kontakt -->
							<?php $arg = $this->ausstattungs_panel(); ?>
							<?php if (!empty($arg)): ?>
								<div id="ausstattung" class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><?= __($this->options['wpi_single_onePage']['ausstattung'], WPI_PLUGIN_NAME); ?></h3>
									</div>
									<div class="panel-body">
										<?php echo $this->ausstattungs_panel(); ?>
									</div>
								</div>
							<?php endif; ?>

						</div>
						<!-- hauptcontent -->
						<div id="details" class="col-xs-12 col-md-4">
							<div id="immo-art" class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?= __($this->options['wpi_single_onePage']['details'], WPI_PLUGIN_NAME); ?></h3>
								</div>
								<div class="panel-body">
									<?php echo $this->tax_anbieter_panel(); ?>
								</div>
							</div>
							<?php $arg = $this->preis_panel(); ?>
							<?php if (!empty($arg)): ?>
								<div id="preise" class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><?= __($this->options['wpi_single_onePage']['preise'], WPI_PLUGIN_NAME); ?></h3>
									</div>
									<div class="panel-body">
										<?php echo $this->preis_panel(); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php $arg = $this->flaechen_panel(); ?>
							<?php if (!empty($arg)): ?>
								<div id="flaechen" class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><?= __($this->options['wpi_single_onePage']['flaechen'], WPI_PLUGIN_NAME); ?></h3>
									</div>
									<div class="panel-body">
										<?php echo $this->flaechen_panel(); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php $arg = $this->zustands_panel(); ?>
							<?php if (!empty($arg)): ?>
								<div id="zustand" class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><?= __($this->options['wpi_single_onePage']['zustand'], WPI_PLUGIN_NAME); ?></h3>
									</div>
									<div class="panel-body">
										<?php echo $this->zustands_panel(); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if ($this->options['wpi_pro'] === 'true'): ?>
								<div id="energiepass" class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><?= __($this->options['wpi_single_onePage']['energiepass'], WPI_PLUGIN_NAME); ?></h3>
									</div>
									<div class="panel-body">
										<?php echo $this->energiepass(); ?>
									</div>
								</div>
							<?php endif; ?>
							<div id="dokumente" class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?= __($this->options['wpi_single_onePage']['dokumente'], WPI_PLUGIN_NAME); ?></h3>
								</div>
								<div class="panel-body">
									<?php echo $this->get_documents(); ?>
								</div>
							</div>
							<div id="map" class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?= __($this->options['wpi_single_onePage']['map'], WPI_PLUGIN_NAME); ?></h3>
								</div>
								<div class="panel-body">
									<?php echo $this->get_map(); ?>
								</div>
							</div>
						</div>
						<!-- Side Content -->
					</div>
				</article>
			</div>

		</section>

		<section id="modals">
			<!-- Modal Post -->
			<div class="modal fade" id="post" tabindex="-1" role="dialog" aria-labelledby="postLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label=">hließen"><span
									aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="postLabel"><?php echo $this->wpim_post_title(); ?></h4>
						</div>
						<div class="modal-body">
							<div class="meta">
								<em>Erstellt am: <?php echo $this->post->post_date; ?> |
									Zuletzt geändert: <?php echo $this->post->post_modified; ?></em>
							</div>
							<p>&nbsp;</p>
							<div><?php echo $this->wpim_post_content(); ?></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
						</div>
					</div>
				</div>
			</div>
			<!-- / Modal Post -->
			<!-- Modal Search -->
			<div class="modal fade" id="searchModal" tabindex="-1" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<h3>Volltextsuche:</h3>
							<p><em>Suche nach einem Kreis, Stadt, Ort etc.</em></p>
							<?php echo $this->view_searchfield_wpmi(); ?><br/><br/>
							<h3>Umkreissuche:</h3>
							<P><em>Suche nach einer Postleitzahl und Entfernung.</em></P>
							<?php echo do_shortcode('[umkreissuche]'); ?>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		</section>

		<?php
		return ob_get_clean();
	}

	/**
	 * Searchfield HTML
	 * @return string
	 */
	public function view_searchfield_wpmi()
	{
		ob_start(); ?>
		<form class="search form-inline" role="search" action="<?php echo home_url('/'); ?>" method="get">
			<div class="input form-group">
				<label>
					<span class="screen-reader-text"><?php echo _x('Suche:', 'label') ?></span>
				</label>
				<input type="search" class="form-control searchfield"
				       placeholder="<?= __('Suche...', WPI_PLUGIN_NAME); ?>"
				       value="<?php echo get_search_query() ?>" name="s"
				       title="<?php echo esc_attr_x('Suche:', 'label') ?>"/>
			</div>
			<div class="submit form-group">
				<button type="submit"
				        class="btn btn-default searchbutton"><?= __('Los', WPI_PLUGIN_NAME); ?></button>
			</div>
		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Imageslider HTML
	 * @return string
	 */
	public function imageslider()
	{
		ob_start();
		?>
		<div id="media" class="imageslider"><?php

			if ($this->bilder): ?>
				<div id="image-carousel"
				     class="carousel slide"
				     data-ride="carousel">
				<!-- Positionsanzeiger -->
				<ol class="carousel-indicators">
					<?php
					for ($i = 0;
					     $i < count($this->bilder);
					     $i++) {
						foreach ($this->bilder[$i] as $alt => $pfad) {
							echo '<li data-target="#image-carousel" data-slide-to="' . $pfad . '"></li>';
						}
					}
					?>
				</ol>

				<!-- Verpackung für die Elemente -->
				<div class="carousel-inner" role="listbox"><?php
					for ($j = 0; $j < count($this->bilder); $j++) {
						foreach ($this->bilder[$j] as $alt => $pfad) {
							!empty($alt) ? $alt : $alt = '';
							if ($j === 0):
								$str = '<div class="item active">';
							else:
								$str = '<div class="item">';
							endif;

							$str .= '<img src="' . $this->uploadUrl . $pfad . '" alt="' . $alt . '">';
							$str .= '<div class="carousel-caption">';
							$str .= '';
							$str .= '</div> </div>';
							echo $str;
						}
					}
					?>
				</div>

				<!-- Schalter -->
				<a class="left carousel-control" href="#image-carousel" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Zurück</span>
				</a>
				<a class="right carousel-control" href="#image-carousel" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Weiter</span>
				</a>
				</div><?php
			else:
				echo '<img src="' . get_option('wpi_img_platzhalter') . '"/>';
			endif;
			?>
		</div>
		<!-- Ende Media Imageslider -->
		<?php
		return ob_get_clean();
	}

	/**
	 * Eckdaten HTML
	 * @return string
	 */

	public function eckdaten()
	{
		ob_start();
		?>
		<div id="eckdaten" class="eckdaten">
			<ul class="list-unstyled eckdaten">
				<li>
                    <span
	                    class="eckdaten_ort"><?php echo $this->geodaten['plz'] . ' ' . $this->geodaten['ort'] ?></span>
				</li><?php

				if (false != $this->verwaltung_objekt['objektadresse_freigeben']):
					?>
					<li>
                        <span
	                        class="eckdaten_strasse"><?php echo ucfirst($this->geodaten['strasse']) . ' ' . $this->geodaten['hausnummer']; ?></span>
					</li>
					<?php
				endif;
				if (!empty($this->preis)):
					// Tabellen-Array ohne leere Werte in die Tabelle schreiben
					foreach (array_filter($this->preis) as $key => $wert) {
						if ($key != 'Faktor') {
							echo '<li>' . $key . ' in  (' . $this->preise['waehrung']['@attributes']['iso_waehrung'] . ') ';
							if (is_numeric($wert)):
								echo '<span class="price value">' . number_format($wert, 2, ",", ".") . '</span></li>';
							else:
								echo '<span class="price value">' . $wert . '</span></li>';
							endif;
						} else {
							echo '<li>' . $key;
							if (is_numeric($wert)):
								echo '<span class="price value">' . number_format($wert, 2, ",", ".") . '</span></li>';
							else:
								echo '<span class="price value">' . $wert . '</span></li>';
							endif;
						}
					}
				endif;
				if (!empty($this->zustand_angaben['baujahr'])):
					echo '<li>' . __("Baujahr", WPI_PLUGIN_NAME) . ' <span class="baujahr value"> ' . $this->zustand_angaben['baujahr'] . '</span></li>';
				endif;
				if (!empty($this->flaechen['wohnflaeche'])):
					echo '<li>' . __("Wohnfläche in m²", WPI_PLUGIN_NAME) . ' <span class="wohnflaeche value">' . number_format($this->flaechen['wohnflaeche'], 1, ",", "") . '</span></li>';
				endif;
				if (!empty($this->flaechen['grundstuecksflaeche'])):
					echo '<li>' . __("Grundstück in m²", WPI_PLUGIN_NAME) . ' <span class="grundsteuck value">' . number_format($this->flaechen['grundstuecksflaeche'], 1, ",", "") . '</span></li>';
				endif;
				if (!empty($this->flaechen['anzahl_zimmer'])):
					echo '<li>' . __("Anzahl Zimmer", WPI_PLUGIN_NAME) . ' <span class="zimmerzahl value">' . number_format($this->flaechen['anzahl_zimmer'], 1, ",", "") . '</span></li>';
				endif;
				if (!empty($this->verwaltung_techn["objektnr_extern"])):
					echo '<li>' . __("Objektnummer", WPI_PLUGIN_NAME) . ' <span class="objektnummer value">' . $this->verwaltung_techn["objektnr_extern"] . '</span></li>';
				endif;
				?>
			</ul>
		</div><!-- ende Eckdaten -->
		<?php
		return ob_get_clean();
	}

	/**
	 * Taxonomie und Anbieter Details Panel
	 * @return string
	 */

	public function tax_anbieter_panel()
	{
		ob_start();
		?>

		<div class="taxdetails">

			<table class="table">
				<tr>
					<td><?= __('Vermarktung', WPI_PLUGIN_NAME); ?></td>
					<td><?php echo $this->vermarktung ?></td>
				</tr>
				<tr>
					<td><?= __('Objektart', WPI_PLUGIN_NAME); ?></td>
					<td><?php echo $this->objekttyp ?></td>
				</tr>
				<tr>
					<td><?= __('Anbieterkennung', WPI_PLUGIN_NAME); ?></td>
					<td><?php echo $this->anbieterkennung; ?></td>
				</tr>
				<tr>
					<td><?= __('Objekt / Online - Nr.', WPI_PLUGIN_NAME); ?></td>
					<td><?php echo $this->verwaltung_techn["objektnr_extern"] ?></td>
				</tr>
			</table>

		</div><!-- .objektdetails -->

		<?php
		return ob_get_clean();
	}

	/**
	 * Preise Panel
	 * @return string
	 */

	public function preis_panel()
	{
		ob_start();

		if (!empty($this->preis)): ?>
			<div class="preise">
				<table class="table">
					<?php
					// Tabellen-Array ohne leere Werte in die Tabelle schreiben
					foreach (array_filter($this->preis) as $key => $wert) {
						echo '<tr>';
						if ($key != 'Faktor') {
							echo '<td>' . $key . ' in  (' . $this->preise['waehrung']['@attributes']['iso_waehrung'] . ')</td>';
							if (is_numeric($wert)):
								echo '<td>' . number_format($wert, 2, ",", ".") . '</td>';
							else:
								echo '<td>' . $wert . '</td>';
							endif;
						} else {
							echo '<td>' . $key . '</td>';
							if (is_numeric($wert)):
								echo '<td>' . number_format($wert, 2, ",", ".") . '</td>';
							else:
								echo '<td>' . $wert . '</td>';
							endif;
						}
						echo '</tr>';

					}
					?>
				</table>
			</div>
			<!-- Div .Preise-->
		<?php endif;

		return ob_get_clean();
	}

	/**
	 * Flächen Panel
	 * @return string
	 */

	public function flaechen_panel()
	{
		ob_start();

		if (!empty($this->flaeche)): ?>
			<div class="flaechen">
				<table class="table">
					<?php
					// Tabellen-Array ohne leere Werte in die Tabelle schreiben
					foreach (array_filter($this->flaeche) as $fl_key => $fl_wert) {
						echo '<tr>';
						echo '<td>' . $fl_key . '</td>';
						if (is_numeric($fl_wert)):
							echo '<td>' . number_format($fl_wert, "1", ",", "") . '</td>';
						else:
							echo '<td>' . $fl_wert . '</td>';
						endif;
						echo '</tr>';
					}
					?>
				</table>
			</div>
			<!-- Div .Flaechen -->
		<?php endif;

		return ob_get_clean();
	}

	/**
	 * Ausstattungs Panel
	 * @return string
	 */

	public function ausstattungs_panel()
	{
		ob_start();

		if (!empty($this->ausstattung)): ?>
			<div class="ausstattung">
				<table class="table">
					<?php
					$ausstatt = $this->help_handle_array($this->ausstattung, 'ausstattung');
					// Tabellen-Array ohne leere Werte in die Tabelle schreiben
					foreach (array_filter($ausstatt) as $aus_key => $aus_wert) {
						echo '<tr>';
						echo '<td>' . ucfirst($aus_key) . '</td>';
						echo '<td>' . $aus_wert . '</td>';
						echo '</tr>';
					}
					?>
				</table>
			</div>
			<!-- Div .Ausstattung -->
		<?php endif;

		return ob_get_clean();
	}

	/**
	 * Zustand Panel
	 * @return string
	 */

	public function zustands_panel()
	{
		ob_start();

		$zustand = $this->help_handle_array($this->zustand_angaben, 'zustand');

		if (!empty($zustand)): ?>
			<div class="zustand">

				<table class="table"><?php
					if (array_key_exists('baujahr', $zustand)):
						echo '<tr><td>' . __("Baujahr", WPI_PLUGIN_NAME) . '</td>';
						echo "<td>" . $zustand['baujahr'] . "</td></tr>";
					endif;
					if (array_key_exists('zustand', $zustand)):
						echo '<tr><td>' . __("Zustand", WPI_PLUGIN_NAME) . '</td>';
						echo "<td>" . $zustand['zustand'] . "</td></tr>";
					endif;
					if (array_key_exists('letztemodernisierung', $zustand)):
						echo '<tr><td>' . __("Letzte Modernisierung", WPI_PLUGIN_NAME) . '</td>';
						echo "<td>" . $zustand['letztemodernisierung'] . "</td></tr>";
					endif;
					?>
				</table>

			</div>
			<!-- Div .Zustand -->
		<?php endif;

		return ob_get_clean();
	}

	/**
	 * Energiepass Panel
	 * @return string
	 */

	public function energiepass()
	{
		ob_start();

		$zustand = $this->help_handle_array($this->zustand_angaben, 'zustand');

		if (array_key_exists('energiepass', $zustand)):
			echo '<ul class="list-unstyled">';
			if (is_array($zustand['energiepass'])):
				foreach ($zustand['energiepass'] as $en_key => $en_value) {
					empty($en_value) ? $en_value = 'n.A.' : $en_value = $en_value;
					echo '<li>';
					echo ucfirst($en_key) . ' - <strong>' . $en_value . '</strong>';
					echo '</li>';
				}
			else:
				echo '<li>' . $zustand['energiepass'] . '</li>';
			endif;
			echo '</ul>';
		endif;

		return ob_get_clean();
	}

	/**
	 * Content Panel
	 * @return string
	 */

	public function wpim_post_content()
	{
		ob_start();
		?>

		<div id="beschreibung"><?php echo $this->post->post_content; ?></div>
		<div class="custom-beschreibung-div"><?php
			if ('' != $this->html_inject && $this->html_inject === 'beschreibung'):
				echo do_shortcode($this->html);
			endif;
			?>
		</div>

		<?php
		return ob_get_clean();
	}

	/**
	 * Post Excerpt
	 * @return string
	 */

	public function wpim_post_excerpt()
	{
		ob_start();
		?>
		<div id="excerpt"><?php echo $this->post->post_excerpt; ?></div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Post Title
	 * @return string
	 */

	public function wpim_post_title()
	{
		ob_start();

		echo $this->post->post_title;

		return ob_get_clean();
	}

	/**
	 * Freitext
	 * @param name
	 * @return string
	 */

	public function get_freitext($name)
	{
		ob_start();

		if (!empty($this->freitexte[$name])):
			echo $this->freitexte[$name];
		endif;

		return ob_get_clean();
	}

	/**
	 * Dokumente falls welche vorhanden
	 * @return string
	 */

	public function get_documents()
	{
		ob_start();

		if (@$this->anhang['dokumente'] > 0): ?>
			<div class="dokumente">

			<ul class="list-unstyled"><?php
				for ($i = 0; $i < count($this->anhang['dokumente']); $i++) {
					foreach ($this->anhang['dokumente'][$i] as $name => $link) {
						$offset = 1;
						$ext = '<span class="glyphicon glyphicon-new-window"></span>';
						if ($name != ''):
							$name = $name;
						else:
							$name = __('Dokument', WPI_PLUGIN_NAME) . $offset;
							$offset++;
						endif;
						echo '<li><a target="_blank" href="' . $this->uploadUrl . $link . '">' . $ext . ' ' . $name . '</a></li>';
					}
				}
				?>
			</ul>
			</div><?php
		else:
			echo '<p>' . __("Keine Dokumente vorhanden", WPI_PLUGIN_NAME) . '</p>';
			?>
		<?php endif;

		return ob_get_clean();
	}

	/**
	 * Firmenangaben / Anbieter
	 * @return string
	 */

	public function get_firma()
	{
		ob_start();

		if (!empty($this->firma)):
			?>
			<div id="firma"><?php

			// Abfrage wenn Firma ein String ist
			if (!is_array($this->firma)) {
				echo $this->firma;
			} else {
				$count = 1;
				echo '<ul class="list-unstyled">';
				foreach ($this->firma as $value) {
					echo '<li id="li-' . $count . '">' . $value . '</li>';
					$count++;
				}
				echo '</ul>';
			}
			?>

			</div><?php
		endif;

		return ob_get_clean();
	}

	/**
	 * Kontaktperson / Anbieter
	 * @return string
	 */

	public function get_kontakt()
	{
		ob_start();

		if (!empty($this->kontaktperson)):
			?>
			<div id="ansprechpartner">

			<?php $kontakt = $this->help_handle_array($this->kontaktperson, 'kontakt'); ?>

			<dl class="dl-horizontal"><?php

				foreach ($kontakt as $bez => $value) {
					echo '<dt>' . ucfirst($bez) . '</dt>';
					echo '<dd>' . $value . '</dd>';
				}
				?>

			</dl>

			</div><?php
		endif;

		return ob_get_clean();
	}

	/**
	 * Artikel Navigation
	 * @return string
	 */

	public function article_navigation()
	{
		ob_start();

		$link_to_immo = get_post_type_archive_link("wpi_immobilie");
		$button_middle = '<a href="' . $link_to_immo . ' ">';
		$button_middle .= __("Immobilien-Übersicht", WPI_PLUGIN_NAME);
		$button_middle .= '</a>';

		?>
		<div class="article-navigation">
			<nav class="navigation immo-navigation bottom-navi" role="navigation">
				<div class="btn-group col-xs-12 text-center">
					<div class="btn btn-default col-xs-4 btn-down">
						<?php previous_post_link('%link', 'Zurück'); ?>
					</div>
					<div class="btn btn-default col-xs-4 btn-overview">
						<?php echo $button_middle; ?>
					</div>
					<div class="btn btn-default col-xs-4 btn-up">
						<?php next_post_link('%link', 'Nächste'); ?>
					</div>
				</div>
			</nav>
			<!-- Loop-Navigation -->
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Seiten Schnellnavigation
	 * @return string
	 */

	public function smart_navigation()
	{
		ob_start();
		?>
		<nav id="smart-navigation" class="navbar navbar-default alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Schließen">
				<span aria-hidden="true">&times;</span>
			</button>
			<div class="container-fluid">
				<!-- Titel und Schalter werden für eine bessere mobile Ansicht zusammengefasst -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
					        data-target="#smart-navi" aria-expanded="false">
						<span class="sr-only">Navigation ein-/ausblenden</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<!-- Alle Navigationslinks, Formulare und anderer Inhalt werden hier zusammengefasst und können dann ein- und ausgeblendet werden -->
				<div class="collapse navbar-collapse" id="smart-navi">
					<ul class="nav navbar-nav">
						<?php
						foreach ($this->smart_nav as $navItem) :
							if (!empty($navItem['beschreibung'])): ?>
								<li><a title="<?= $navItem['title']; ?>" href="<?= $navItem['link']; ?>">
										<?= $navItem['beschreibung']; ?> <span
											class="visible-xs"><?= $navItem['title']; ?></span>
									</a>
								</li>
								<?php
							endif;
						endforeach;
						?>
					</ul>

					<ul class="nav navbar-nav navbar-right">
						<li><a role="button" href="#" data-toggle="modal" data-target="#searchModal">
								<i class="fa fa-search" aria-hidden="true"></i></a></li>
						<span class="visible-xs">Suche</span>
					</ul>

				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
		<?php
		return ob_get_clean();
	}

	/**
	 * Google Maps Karte
	 * @return string
	 */

	public function get_map()
	{
		ob_start();
		$strasse = $this->geodaten['strasse'];
		$ort = $this->geodaten['ort'];
		?>
		<iframe
			width="100%"
			height="auto"
			frameborder="0" style="border:0"
			src="https://www.google.com/maps/embed/v1/search?key=AIzaSyDAHVKFbW-cflVB7Ve212yJBLAeoKXJLJw
    &q=<?= $strasse; ?>,<?= $ort; ?>" allowfullscreen>
		</iframe>
		<?php
		return ob_get_clean();
	}


// Helper Functions

	/**
	 * @param $arg = array to handle
	 * @param $arg2 = argument to handle
	 *
	 * @return array rendered
	 */

	private function help_handle_array($arg, $arg2)
	{
		$array = $arg;
		$new_array = array();

// Vorbereiten des Ausstattung Arrays
		if ($arg2 === 'ausstattung') {
			$glyph = '<span class="glyphicon glyphicon-ok"></span>';
			$glyph_x = '<span class="glyphicon glyphicon-remove"></span>';

			$texte = array(
				'ausstatt_kategorie' => 'Ausstattungskategorie',
				'wg_geeignet' => 'WG-Geeignet',
				'raeume_veraenderbar' => 'Räume veränderbar',
				'kueche' => 'Küche',
				'ausricht_balkon_terrasse' => 'Ausrichtung Balkon/ Terrasse',
				'moebliert' => 'Möbeliert',
				'kabel_sat_tv' => 'Kabel/SAT TV',
				'wasch_trockenraum' => 'Wasch / Trockenraum',
				'dv_verkabelung' => 'DV Verkabelung',
				'hebebuehne' => 'Hebebühne',
				'kantine_cafeteria' => 'Kantine/Cafeteria',
				'teekueche' => 'Teeküche',
				'hallenhoehe' => 'Hallenhöhe',
				'angeschl_gastronomie' => 'Mit Gastronomie',
				'telefon_ferienimmobilie' => 'Telefon Ferienimmobilie',
				'gaestewc' => 'Gäste-WC',
				'kabelkanaele' => 'Kabelkanäle',
				'breitband_zugang' => 'Breitband Internet',
				'umts_empfang' => 'UMTS Empfang'
			);

			$textchange = changeKeyNames($array, $texte);

			foreach ($textchange as $key1 => $value1) {
//TODO Das "user_defined_simplefield" vorerst ausgeblendet!!!
				if ($key1 != 'user_defined_simplefield') {
					if (!is_array(@$array[$key1])) {
						$key1 = str_replace('_', '-', $key1);
						@$new_array[$key1] = ucfirst(strtolower($value1));
					} else {
						foreach ($array[$key1] as $key2 => $value2) {
							unset($values);
							foreach ($array[$key1][$key2] as $key3 => $value3) {
								$values[] = ucfirst(strtolower($key3));
							}
							$key1 = str_replace('_', '-', $key1);
							@$new_array[$key1] = implode(', ', $values);
						}
					}
				}
			}
// Prüfen der Values mit True oder 1 und ausgeben als Glyphicon
			foreach ($new_array as $key => $item) {
				if (strtolower($item) === 'true' || $item === '1') {
					$new_array[$key] = $glyph;
				} elseif (strtolower($item) === 'false' || $item === '0') {
					$new_array[$key] = $glyph_x;
				}
			}
			return @$new_array;

		} // Vorbereiten des Zustand-Arrays
		elseif ($arg2 === 'zustand') {

			$new_array = array();
			$epart_keys = array(
				'epart' => __('Energieausweistyp'),
				'art' => __('Energieausweistyp'),
				'gueltig_bis' => __('Gültig bis'),
				'energieverbrauchkennwert' => __('Energieverbrauchkennwert'),
				'mitwarmwasser' => __('Mit Warmwasser'),
				'endenergiebedarf' => __('Energiebedarf'),
				'primaerenergietraeger' => __('Wesentlicher Energieträger'),
				'stromwert' => __('Stromwert'),
				'waermewert' => __('Wärmewert'),
				'wertklasse' => __('Wertklasse'),
				'baujahr' => __('Baujahr'),
				'ausstelldatum' => __('Ausstelldatum'),
				'jahrgang' => __('Jahrgang des Energieausweises'),
				'gebaeudeart' => __('Gebäudeart')
			);

			foreach ($array as $key1 => $value1) {

				if (!is_array($array[$key1])) {
					$new_array[$key1] = ucfirst(strtolower($value1));
				} else {
					foreach ($array[$key1] as $key2 => $value2) {
// Wenn Attribute vorhanden
						if (is_array($array[$key1]) && array_key_exists('@attributes', $array[$key1])) {
							foreach ($array[$key1]['@attributes'] as $key3 => $value3) {
								unset($values);
								$values[] = ucfirst(strtolower($value3));
							}
							$new_array[$key1] = implode(', ', $values);
						} else {
							@$new_array[$key1][$key2] = ucfirst(strtolower($value2));
						}
					}
				}
			}
//zeigen($new_array['energiepass']);
			/**
			 * Anpassung der Energieausweisdaten...
			 **/
// Options-Texte
			$epass_texte = $this->options['wpi_single_epass'];
// Wenn kein Energiepass übergeben wurde
			if (!@$new_array['energiepass'] || @$new_array['energiepass']['jahrgang'] === 'Ohne') {
				$new_array['energiepass'] = $epass_texte['nicht_vorhanden'];
			} // Wenn Energieausweis nicht erforderlich z.B. bei Denkmalschutz
			elseif (@$new_array['enrgiepass']['jahrgang'] === 'Nicht_noetig') {
				$new_array['energiepass'] = $epass_texte['nicht_benoetigt'];
			} // Bei übergebenem Energieausweis anpassen der Values
			else {
// Value für "epart"
				$einheit = ' kWh/(m²*a)';
				switch (@$new_array['energiepass']['epart']) {
					case 'Verbrauch':
						$new_array['energiepass']['epart'] = __('Verbrauchsausweis');
						if (@$new_array['energiepass']['energieverbrauchkennwert'] > 0) {
							$new_array['energiepass']['energieverbrauchkennwert'] = $new_array['energiepass']['energieverbrauchkennwert'] . $einheit;
						}
						if (@$new_array['energiepass']['mitwarmwasser'] === 'True') {
							$new_array['energiepass']['mitwarmwasser'] = __('Energieverbrauch für Warmwasser enthalten');
						} elseif (@$new_array['energiepass']['mitwarmwasser'] === 'False') {
							$new_array['energiepass']['mitwarmwasser'] = __('Energieverbrauch für Warmwasser enthalten');
						} else {
							unset($new_array['energiepass']['mitwarmwasser']);
						}
						break;
					case 'Bedarf':
						$new_array['energiepass']['epart'] = __('Bedarfsausweis');
						if (@$new_array['energiepass']['endenergiebedarf'] > 0) {
							@$new_array['energiepass']['endenergiebedarf'] = $new_array['energiepass']['endenergiebedarf'] . $einheit;
						}
						if (@$new_array['energiepass']['mitwarmwasser'] > 0 && @$new_array['energiepass']['mitwarmwasser'] === 'True') {
							$new_array['energiepass']['mitwarmwasser'] = __('Energieverbrauch für Warmwasser enthalten');
						}
						break;
				}
// Value für "ART"
				switch (@$new_array['energiepass']['art']) {
					case 'Verbrauch':
						$new_array['energiepass']['art'] = __('Verbrauchsausweis');
						if (@$new_array['energiepass']['energieverbrauchkennwert'] > 0) {
							$new_array['energiepass']['energieverbrauchkennwert'] = $new_array['energiepass']['energieverbrauchkennwert'] . $einheit;
						}
						if (@$new_array['energiepass']['mitwarmwasser'] === 'True') {
							$new_array['energiepass']['mitwarmwasser'] = __('Energieverbrauch für Warmwasser enthalten');
						} elseif (@$new_array['energiepass']['mitwarmwasser'] === 'False') {
							$new_array['energiepass']['mitwarmwasser'] = __('Energieverbrauch für Warmwasser nicht enthalten');
						} else {
							unset($new_array['energiepass']['mitwarmwasser']);
						}
						break;
					case 'Bedarf':
						$new_array['energiepass']['art'] = __('Bedarfsausweis');
						if (@$new_array['energiepass']['endenergiebedarf'] > 0) {
							@$new_array['energiepass']['endenergiebedarf'] = $new_array['energiepass']['endenergiebedarf'] . $einheit;
						}
						/*if (@$new_array['energiepass']['mitwarmwasser'] > 0 || @$new_array['energiepass']['mitwarmwasser'] === 'true') {
						$new_array['energiepass']['mitwarmwasser'] = __('Energieverbrauch für Warmwasser enthalten');
						}*/
						break;
				}
// Value für Gültig
				if (@$new_array['energiepass']['gueltig_bis']) {
					$gueltig = $new_array['energiepass']['gueltig_bis'];
					$format = 'd.m.Y';
					$datum = strtotime($gueltig);
					$new_array['energiepass']['gueltig_bis'] = date($format, $datum);
				}

// Value für Ausstelldatum
				if (@$new_array['energiepass']['ausstelldatum']) {
					$ausstell = $new_array['energiepass']['ausstelldatum'];
					$format = 'd.m.Y';
					$datum = strtotime($ausstell);
					$new_array['energiepass']['ausstelldatum'] = date($format, $datum);

				}
// Value für "gebaeudeart"
				switch (@$new_array['energiepass']['gebaeudeart']) {
					case 'Wohn':
						$new_array['energiepass']['gebaeudeart'] = __('Wohngebäude');
						break;
					case 'Nichtwohn':
						$new_array['energiepass']['gebaeudeart'] = __('Nichtwohngebäude');
						break;
				}
// Austauschen der Schlüssel für Energieausweisdaten
				if (is_array(@$new_array['energiepass'])) {
					foreach ($epart_keys as $epart_key => $epart_value) {
						if (array_key_exists($epart_key, $new_array['energiepass'])) {
							$new_array['energiepass'][$epart_value] = $new_array['energiepass'][$epart_key];
							unset($new_array['energiepass'][$epart_key]);
						}
					}
				}
			}

			return $new_array;
		} // Vorbereiten des Anhaenge Arrays
		elseif ($arg2 === 'anhang') {
// erlaubte Bildformate
			$bildformate = array(
				'jpg',
				'jpeg',
				'png',
				'JPG',
				'JPEG',
				'PNG',
				'image/jpeg',
				'image/jpg',
				'image/png',
				'IMAGE/JPEG',
				'IMAGE/JPG',
				'IMAGE/PNG'
			);
// Erlaubte Dokumentenvormate
			$dokumentformate = 'pdf';

			if (!is_array_assoc($array['anhang'])):
// Wenn mehrere Anhänge als Array verfügbar
				foreach ($array['anhang'] as $key => $item2) {

// Vorauswahl Anhangtitel, ist einer vorhanden anwenden sonst String 'bild' setzen;
					empty($array['anhang'][$key]['anhangtitel']) ? $anhangtitel = 'bild' : $anhangtitel = $array['anhang'][$key]['anhangtitel'];


					foreach ($bildformate as $formvalue) {
// Bilder
						if ((@$array['anhang'][$key]['format']) && strtolower(@$array['anhang'][$key]['format']) == $formvalue) {
							@$new_array['bilder'][] = array(
								$anhangtitel =>
									strtolower($array['anhang'][$key]['daten']['pfad'])
							);
						}
					}

// Dokumente
					if ((@$array['anhang'][$key]['format']) && strtolower(@$array['anhang'][$key]['format']) == $dokumentformate) {
						@$new_array['dokumente'][] = array(
							$array['anhang'][$key]['anhangtitel'] =>
								strtolower($array['anhang'][$key]['daten']['pfad'])
						);
					}

				}
			else:
// Wenn nur ein Anhang
				foreach ($bildformate as $formvalue) {
// Bilder
					if ((@$array['anhang']['format']) && strtolower(@$array['anhang']['format']) === $formvalue) {
						@$new_array['bilder'][] = array(
							$array['OriginalDateiname'] =>
								strtolower($array['anhang']['daten']['pfad'])
						);
					}
// Dokumente
					if ((@$array['anhang']['format']) && strtolower(@$array['anhang']['format']) === $dokumentformate) {
						@$new_array['dokumente'][] = array(
							$array['anhang']['anhangtitel'] =>
								strtolower($array['anhang']['daten']['pfad'])
						);
					}
				}

			endif;

			return $new_array;
		} // Vorbereiten des Kontakt-Arrays
		elseif ($arg2 === 'kontakt') {

// Array zum austausch der Texte
			$textarray = array(
				'email_zentrale' => 'Email Zentrale',
				'email_direkt' => 'Email Direkt',
				'tel_zentrale' => 'Telefon Zentrale',
				'tel_durchw' => 'Telefon Durchwahl',
				'tel_fax' => 'Fax',
				'tel_handy' => 'Mobil',
				'postf_plz' => 'Postfach PLZ',
				'postf_ort' => 'Postfach Ort',
				'email_privat' => 'Email Privat',
				'email_sonstige' => 'Weitere Email',
				'email_feedback' => 'Feedback',
				'tel_privat' => 'Telefon Privat',
				'tel_sonstige' => 'Weitere Rufnummern',
			);

			foreach ($array as $key => $item) {

				if ($item !== '' && $item !== '-' && !is_array($item)) {
//$key = str_replace('_', ' ', $key);
					$kont_array[$key] = $item;
					$new_array = changeKeyNames($kont_array, $textarray);

				}
			}
//zeigen($new_array);
			return $new_array;
		}
	}

	/**
	 * @param $array
	 * @return bool
	 * Funktion prüft ob ein Array assoziativ ist
	 */
	private function is_array_assoc($array)
	{
		foreach ($array as $key => $value) {
			if (is_integer($key)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param $arrayToChange
	 * @param $arrayTexte
	 * @return $new_array
	 * Funktion tauscht die Texte der ArrayKeys gegen andere Texte
	 */
	private function changeKeyNames($arrayToChange, $arrayTexte)
	{
		foreach ($arrayToChange as $textkey => $value) {
			if (array_key_exists($textkey, $arrayTexte)) {
				$text = $arrayTexte[$textkey];
				$new_array[$text] = $value;
			} else {
				$new_array[$textkey] = $value;
			}
		}
		return $new_array;
	}

}