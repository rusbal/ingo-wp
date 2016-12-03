**WP Immo Manager**
----------------------------------------------------

### Version:
Current Version: 2.0.8

### Description
**WP Immo Manager** integriert *Immobilien aus ihrer Makler-Software* in Wordpress.
Einfach in der Immobilien-Verwaltungssoftware wie *Maklerserver, Maklermanager etc.* ein neues Portal anlegen,
ein FTP-Pfad angeben und OpenImmo Format auswählen. FTP-Pfad in den Einstellungen des Plugins speichern. Fertig!!!
Manuelle Synchronisation der Immobilien direkt aus der Wordpress-Admin Seite oder **WP Immo Manager** überprüft
sofort (Nur in der PRO-Version) ob es neue Aktualisierungen gibt und erstellt daraus eine neue Immobilie in Wordpress!

### Weitere Features
* Anpassen der angezeigten Daten in der Listen-Ansicht
* Anpassen der angezeigten Daten in der Single-Ansicht
* Umkreissuche per Shortcode
* Benutzerdefinierte CSS für Styling der Templates möglich
* Markieren einzelner Immobilien als TOP-Immobilie


### Usersguide

### Settings

### Changelog

**2.0.8**
- Objektorientierte Aufteilung Options
- Objektorientierte Aufteiung Admin Functions
- Objektorientierte Aufteilung Single Page Functions
- One Page Single Template
- Weitere Einstellmöglichkeiten im Admin Bereich

**2.0.7**
- Kompatibilität zu Wordpress 4.6
- Bugfixes()
- Bei manchen Übertragungen wird bei Bildern kein Anhangtitel übertragen,
  dadurch wurden die Bilder im Template nicht angezeigt. Wurde hiermit gefixt.
- Auf mehrfachen Wunsch wurden die Glyphicons im Titel der Immobilien ganz entfernt.

**2.0.6**
- Option im Backend für Title wurde ein Bug behoben
- Optionen für Immobilie "Verkauft" und "Reserviert" hinzugefügt.
- Diese Optionen in den Listen-Ansichten mit eingefügt.

**2.0.5**
- Energieausweisdaten überarbeitet
- Texte für Energieausweis in Backend anpassbar
- Neue Preisfelder zu Liste und Single eingefügt
- Option im Backend für Titel der Immobilien hinzugefügt.

**2.0.4**
- Custom HTML Textarea eingefügt
- Tab-Single-View für Multilanguage vorbereitet
- help_handle_array bei anhang um weitere Bildformate erweitert.
- PLZ / Ort bei Listenansicht hinzugefügt
- Tausenderpunkt und Nachkommastellen bei Preise und Flächen ergänzt

**2.0.3**
- Bei Übertragung eines einzigen Anhangs wurden die Bilder nicht angezeigt, wurde gefixt.

**2.0.2**
- immo-navigation bei Firefox und IE ohne Funktion, wurde gefixt.
- search-form war bei single_immobilie fehlerhaft, wurde gefixt.
- Fehler in der Umkreissuche gefixt.

**2.0.0**
- Erkennung der XML überarbeitet
- Die Meta Daten werden jetzt als Arrays gespeichert
- Singleansicht wurde in view_single_tabs verlagert
- Singleansicht view_single_accordion hinzugefügt
- Die Auswahl zwischen den Anzeigemodi kann in der Admin-Page eingestellt werden.
- Listenansicht wurde in die view_list_openimmo verlagert
- Listenansicht view_list_columns hinzugefügt
- Die Auswahl zwischen den Anzeigemodi kann in der Admin-Page eingestellt werden.
- Ein Excerpt-Filter für die Suchanfragen wurde hinzugefügt.
- mehrere Bugs behoben


**1.0.7.1**
- Beim Shortcode Immobilien wurde eine Ansicht bei nicht vorhanden der Immobilien hinzugefügt.

**1.0.7**
- Shortcode "Immobilien" hinzugefügt.
- Eingabefeld für Upload URL hinzugefügt.

**1.0.6.9**
- Bugs am Single Template behoben

**1.0.6.8**
- Objektdateien von Lagler werden jetzt erkannt

**1.0.6.7**
- Mit Wordpress 4.5 getestet.
- Tab Shortcodes in Admin-Settings bearbeitet
- Leere Preisfelder bei Single und Archiv Ansicht werden nicht mehr angezeigt.
- Loop-Navigation in der Single-Ansicht ist jetzt auch auf großen Geräten zu sehen.

**1.0.6.6**
- Dateierkennung mit Großbuchstaben wurde nicht erkannt
- bei Doppelt gezipten archiven konnte keine XML-Datei gefunden werden

**1.0.6.5**
- Ordnerstrucktur des Plugins angepasst

**1.0.6.4**
- Ausgabe Custom CSS in den Templates angepasst.

**1.0.6.3**
- Issues for WP-Repo fixed
- Pfad-Variablen abgeändert
- Konstanten angepasst
- wp-updates class entfernt

**1.0.6.2**     - Bugs an der Lizenzabfrage behoben.

**1.0.6.1**     - Lizenzierungsabfrage hinzugefügt.

**1.0.6**       - Eingabefeld für Pro-Lizenz hinzugefügt
                - Funktion zum aktivieren/deaktivieren der Bootstrap-Styles
                - Listenpunkt Shortcodes in der Admin-Page hinzugefügt
                - Textarea für Benutzerdefinierte Styles hinzugefügt
                - uninstall.php hinzugefügt

**1.0.5.7**     - Bootstrap.css im Admin-Bereich wird nur auf der Admin-Page des Plugins geladen
                - Kollision mit Admin-Bar behoben
                - Übersetzung zu Admin-Seite hinzugefügt.
                                
## License
GPLv2 or heigher