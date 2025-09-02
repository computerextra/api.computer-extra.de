<?php

function Anlage_A(): string
{
    $a1 = A_1();
    $a2 = A_2();
    $a3 = A_3();
    $a4 = A_4();
    $a5 = A_5();
    $body = <<<HTML
    <h1>Anlage A:</h1>
    $a1
    $a2
    $a3
    $a4
    $a5
    HTML;
    return $body;
}

function Anlage_B(): string
{
    $b1 = B_1();
    $b2 = B_2();
    $b3 = B_3();
    $b4 = B_4();
    $body = <<<HTML
    <h1>Anlage B: Technische und organisatorische Maßnahmen</h1>
    <h2>Standardmaßnahme nach DSGVO</h2>
    $b1
    $b2
    $b3
    $b4
    HTML;
    return $body;
}

function A_1(): string
{
    $body = <<<HTML
    <h2>1. Gegenstand des Auftrages</h2>
    <p>
        Der Auftrag des Auftraggebers an den Auftragnehmer und dessen Unterauftragnehmer
        umfasst folgende Arbeiten und/oder Leistungen:
    </p>
    <ul class="check" style="list-style-type: none !important;">
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Auftragsverarbeitung in unserem Warenwirtschaftssystem und Intranet
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Durchführung von Fernwartungen
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Elektronische Datenverarbeitung zur Bestellabwicklung sowie Warenlieferung
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Support Tätigkeiten bei Problemen und Störungen
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Verwaltung von Securepoint Firewalls und deren Lizenzen
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Durchführung von Datensicherungen und Datenvernichtungen
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Versand von Waren mittels Versanddienstleister
        </li>
    </ul>
    HTML;
    return $body;
}

function A_2(): string
{
    $body = <<<HTML
    <h2>2. Umfang, Art und Zweck der Verarbeitung</h2>
    <p>
        Der Umfang der Verarbeitung und damit die Menge der eingesetzten Daten sind variabel
        und richtet sich nach der Nutzungsintensität durch den Auftraggeber.
        Der Zweck dient zur Verwaltung der Aufgaben und Maßnahmen im Datenschutz und
        Dokumentation von digitalen bzw. digitalisierten Dokumenten sowie zu durchgeführten
        Schulungsmaßnahmen einzelner Beschäftigter des Auftraggebers.
        Die Erbringung der vertraglich vereinbarten Datenverarbeitung findet ausschließlich in
        einem Mitgliedsstaat der Europäischen Union oder in einem anderen Vertragsstaat des
        Abkommens über den Europäischen Wirtschaftsraum statt. Jede Verlagerung in ein
        Drittland bedarf der vorherigen Zustimmung des Auftraggebers und darf nur erfolgen, wenn
        die besonderen Voraussetzungen der Artt. 44 ff. DS-GVO erfüllt sind.
    </p>
    <p>
        Bei der Auftragsleistung handelt es sich um folgende Arten der automatisierten
        Verarbeitung personenbezogener Daten unter Einsatz von Datenverarbeitungsanlagen:
    </p>
    <ul class="check" style="list-style-type: none !important;">
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Verändern (z.B. durch Änderungen der Benutzer an den Datensätzen)
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Übermitteln (z.B. durch Übermittlung per E-Mail von Nachrichten)
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Speichern (z.B. durch Sicherung und Archivierung auf Festplatten, anderen
            Speichersystemen oder Datenträgern)
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Löschen (z.B. durch Löschen von Datensätzen oder Vernichten von Datenträgern)
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Erheben (z.B. durch Einlesen von Mitarbeiterlisten und anderen Daten)
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Einschränken (z.B. durch Deaktivierung von einzelnen Datensätzen)
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Nutzen (z.B. durch Durchführung von Auswertungen)
        </li>
    </ul>
    HTML;
    return $body;
}

function A_3(): string
{
    $body = <<<HTML
    <h2>3. Art der Daten</h2>
    <ul class="check" style="list-style-type: none !important;">
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
           Beschäftigtendaten (z.B. Name, Vorname, Benutzername, E-Mailadresse).
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Nutzungsdaten (z.B. Protokollierung der Nutzeraktivitäten, Log-Files nur mit anonymer IP-Adresse).
        </li>
    </ul>
    HTML;
    return $body;
}

function A_4(): string
{
    $body = <<<HTML
    <h2>4. Kategorien der betroffenen Personen</h2>
    <ul class="check" style="list-style-type: none !important;">
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
           Beschäftigte des Auftraggebers
        </li>
        <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Beauftragte des Auftraggebers
        </li>
          <li style="display: flex; gap: 0.5rem; align-items: baseline;">
            <input type="checkbox" checked disabled />
            Lieferanten
        </li>
    </ul>
    HTML;
    return $body;
}

function A_5(): string
{
    $body = <<<HTML
    <h2>5. Eingesetzte Subunternehmer</h2>
    <p>Folgende Subunternehmer werden von uns eingesetzt.</p>
    <ul class="check" style="list-style-type: none !important">
    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        Securepoint GmbH, Bleckeder Landstraße 28, 21337 Lüneburg ((Fern-) Prüfung
        und Wartung Securepoint)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        Meinhardt Städtereinigung GmbH & Co. KG, Nassaustraße 13-15, 65719
        Hofheim-Wallau (Aktenvernichtung)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        audatis Services GmbH, Luisenstraße 1, 32052 Herford (Datenschutzmanager)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        Terra Cloud GmbH, Hankamp 2, 32609 Hüllhorst (Cloud Computing)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        Brother International GmbH, Konrad-Adenauer-Allee 1-11, 61118 Bad Vilbel
        (Direkte Verbrauchsmaterial-Belieferung von Endkunden gemäß Partner MPS
        Alliance-Einzelverträge)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        Systeam GmbH, Industriestraße 8, 96250 Ebensfeld (Dropshipping)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        Synaxon AG, Falkenstraße 31, 33758 Schloß Holte-Stukenbrock (eProcurement /
        Management von Beschaffungskonditionen)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        pcvisit Software AG, Manfred-von-Ardenne-Ring 20, 01099 Dresden
        (Fernwartungen)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        Annegrit Schmiedeknecht, Einzelunternehmerin, Obervellmaer Str. 3, 34128
        Kassel (IT-Schulungen)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        Wortmann AG (Leistungen im Bereich Informationstechnologie Produkt & Handel,
        Service & Dienstleistungen)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        Microsoft Corp., Konrad-Zuse-Str.1, 85716 Unterschleißheim (Einsatz und
        Verwaltung von MS365)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        DHL Paket GmbH, Sträßchensweg 10, 53113 Bonn (Paketversand)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        UNITED PARCEL SERVICE DEUTSCHLAND S.à r.l. & Co. OHG, Görlitzer Straße 1,
        41460 Neuss (Paketversand)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        General Logistics Systems Germany GmbH & Co. OHG, GLS Germany-Straße 1 - 7,
        36286 Neuenstein (Paketversand)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        trans-o-flex Express GmbH & Co. KGaA, Hertzstraße 10, 69469 Weinheim
        (Paketversand)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        G Data CyberDefense AG, Königsallee 178, 44799 Bochum (Vertrieb von Waren
        und Dienstleistungen; Installationsunterstützung; Support; online
        Awareness-Trainings)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        Intos Electronic AG, Siemensstraße 11, 35394 Gießen (Verwaltung
        personenbezogener Daten für die Durchführung des Auftrags beziehungsweise
        zur Abwicklung der Geschäftsbeziehung)
    </li>

    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        AEM Communication GmbH & Co. KG, Obergasse 40-42, 36304 Alsfeld (Wartung und
        Pflege Kundenmanagement; Bereitstellung SaaS f. Kundenmanagement; Support)
    </li>
    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        ALL-INKL.COM - Neue Medien Münnich, Inhaber René Münnich, Hauptstraße 68B,
        02742 Friedersdorf (Web Hosting und Vertrieb von Hosting Verträgen)
    </li>
    <li style="display: flex; gap: 0.5rem; align-items: baseline">
        <input type="checkbox" checked disabled />
        meetergo GmbH - Hansaring 61, 50670 Köln (Online Beratungstermine
        Verwaltung)
    </li>
    </ul>
    HTML;
    return $body;
}

function B_1(): string
{
    $b1 = B_1_1();
    $b2 = B_1_2();
    $b3 = B_1_3();
    $b4 = B_1_4();
    $b5 = B_1_5();
    $b6 = B_1_6();


    $body = <<<HTML
    <h2>1. Vertraulichkeit</h2>
    $b1
    $b2
    $b3
    $b4
    $b5
    $b6
    HTML;
    return $body;
}

function B_1_1(): string
{
    $body = <<<HTML
    <h3>1.1 Zutrittskontrolle</h3>
    <p>
        Unbefugten ist der Zutritt zu den zwecks Erbringung der Verarbeitung genutzten
        technischen Einrichtungen zu verwehren.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
            Manuelles Schließsystem - Manuelles Schließsystem mit Schließzylinder
        </li>
        <li>
            Schlüsselverwaltung - Schlüsselregelung mit Dokumentation der Schlüssel (z. B.
            Schlüsselbuch)
        </li>
        <li>
             Videoüberwachung - Videoüberwachung der Zugänge
        </li>
    </ul>
    HTML;
    return $body;
}

function B_1_2(): string
{
    $body = <<<HTML
     <h3>1.2 Zugangskontrolle</h3>
    <p>
        Es ist zu verhindern, dass die zur Erbringung der beschriebenen IT-Dienstleistung
        notwendigen Einrichtungen (Hardware, Betriebssysteme, Software) von Unbefugten genutzt
        werden können.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
            Authentifikation mit Benutzer + Passwort
        </li>
        <li>
            Benutzerberechtigungen - Benutzerberechtigungen verwalten (z.B. bei Eintritt, Änderung,
            Austritt)
        </li>
        <li>
             Firewall - Einsatz von Firewalls zum Schutz des Netzwerkes
        </li>
         <li>
             Sorgfältige Personalauswahl - Sorgfältige Auswahl von Reinigungspersonal und
            Sicherheitspersonal
        </li>
         <li>
             Sperren von externen Schnittstellen - Sperren von externen Schnittstellen (z.B.
            USB-Anschlüsse)
        </li>
         <li>
            Verschlüsselung von Datenträgern - Verschlüsselung von Datenträgern mit dem Stand der
            Technik entsprechenden Verfahren
        </li>
    </ul>
    HTML;
    return $body;
}

function B_1_3(): string
{
    $body = <<<HTML
     <h3>1.3 Zugriffskontrolle</h3>
    <p>
        Es ist sicherzustellen, dass die zur Benutzung eines Datenverarbeitungssystems
        Berechtigten ausschließlich auf die ihrer Zugriffsberechtigung unterliegenden Daten
        zugreifen können, und dass personenbezogene Daten bei der Verarbeitung, Nutzung und
        nach der Speicherung nicht unbefugt gelesen, kopiert oder verändert werden können.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
            Berechtigungskonzept - Erstellen und Einsatz eines Berechtigungskonzepts
        </li>
        <li>
            Datenlöschung - Sichere Löschung von Datenträgern vor deren Wiederverwendung (z.B.
            durch mehrfaches Überschreiben)
        </li>
        <li>
             Einsatz von Aktenvernichtern - Einsatz von Aktenvernichtern (min. Sicherheitsstufe 3 und
            Schutzklasse 2)
        </li>
        <li>
            Einsatz von Dienstleistern - Einsatz von Dienstleistern zur Akten- und Datenvernichtung
            (nach Möglichkeit mit DIN 66399 Zertifikat)
        </li>
        <li>
            Sichere Aufbewahrung - Sichere Aufbewahrung von Datenträgern
        </li>
        <li>
            Verschlüsselung von Datenträgern - Verschlüsselung von Datenträgern mit dem Stand der
            Technik entsprechenden Verfahren
        </li>
    </ul>
    HTML;
    return $body;
}

function B_1_4(): string
{
    $body = <<<HTML
    <h3>1.4 Weitergabekontrolle</h3>
    <p>
        Es muss dafür gesorgt werden, dass personenbezogene Daten bei der elektronischen
        Übertragung oder während ihres Transports oder ihrer Speicherung auf Datenträger nicht
        unbefugt gelesen, kopiert, verändert oder entfernt werden können, und dass überprüft und
        festgestellt werden kann, an welchen Stellen die Übermittlung personenbezogener Daten
        durch Einrichtungen zur Datenübertragung vorgesehen ist.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
            E-Mail-Verschlüsselung - E-Mail-Verschlüsselung mit S/MIME oder PGP Verfahren (oder
            anderen, dem Stand der Technik entsprechenden Verfahren)
        </li>
        <li>
            SSL / TLS Verschlüsselung - Einsatz von SSL-/TLS-Verschlüsselung bei der
            Datenübertragung im Internet
        </li>
        <li>
             VPN-Tunnel - Einrichtungen von VPN-Tunneln zur Einwahl ins Netzwerk von außen
        </li>
    </ul>
    HTML;
    return $body;
}

function B_1_5(): string
{
    $body = <<<HTML
    <h3>1.5 Trennungskontrolle</h3>
    <p>
       Es ist sicherzustellen, dass zu unterschiedlichen Zwecken erhobene Daten getrennt
        verarbeitet werden können.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
            Logische Mandantentrennung - Logische Mandantentrennung (softwareseitig)
        </li>
        <li>
            Physikalische Trennung der Daten - Physikalisch getrennte Speicherung auf gesonderten
            Systemen oder Datenträgern
        </li>
        <li>
             Produktiv- und Testsystem - Trennung von Produktiv- und Testsystem
        </li>
    </ul>
    HTML;
    return $body;
}

function B_1_6(): string
{
    $body = <<<HTML
    <h3>1.6 Verschlüsselung</h3>
    <p>
        Die Verarbeitung personenbezogener Daten soll in einer Weise erfolgen, die eine
        unbeabsichtigte oder unrechtmäßige oder unbefugte Offenlegung dieser verhindert. Hierzu
        dienen dem Stand der Technik entsprechende und als sicher geltende
        Verschlüsselungsmechanismen.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
            Speicherung - Verschlüsselte Datenspeicherung (z.B. Dateiverschlüsselung nach AES256
            Standard)
        </li>
        <li>
            Übertragung - Verschlüsselte Datenübertragung (z.B. E-Mailverschlüsselung nach PGP
            oder S/Mime, VPN, verschlüsselte Internetverbindungen mittels TLS/SSL, Einsatz FTAPI -
            Datentransfertool)
        </li>
    </ul>
    HTML;
    return $body;
}

function B_2(): string
{
    $b1 = B_2_1();

    $body = <<<HTML
    <h2>2. Integrität</h2>
    $b1
    HTML;
    return $body;
}

function B_2_1(): string
{
    $body = <<<HTML
    <h3>2.1 Eingabekontrolle</h3>
    <p>
        Es muss nachträglich geprüft und festgestellt werden können, ob und von wem
        personenbezogene Daten in Datenverarbeitungssysteme eingegeben, verändert oder entfernt worden sind.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
            Personalisierte Benutzernamen - Nachvollziehbarkeit von Eingabe, Änderung und
            Löschung von Daten durch individuelle Benutzernamen (nicht Benutzergruppen)
        </li>
        <li>
           Protokollierung - Protokollierung der Eingabe, Änderung und Löschung von Daten
        </li>
         <li>
           Zugriffsrechte - Personenbezogene Zugriffsrechte zur Nachvollziehbarkeit der Zugriffe.
        </li>
    </ul>
    HTML;
    return $body;
}

function B_3(): string
{
    $b1 = B_3_1();
    $b2 = B_3_2();

    $body = <<<HTML
    <h2>3. Verfügbarkeit und Belastbarkeit</h2>
    $b1
    $b2
    HTML;
    return $body;
}

function B_3_1(): string
{
    $body = <<<HTML
    <h3>3.1 Verfügbarkeitskontrolle</h3>
    <p>
        Es ist dafür Sorge zu tragen, dass personenbezogene Daten gegen zufällige Zerstörung
        oder Verlust geschützt sind.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
            Antivirensoftware - Einsatz von Antivirensoftware zum Schutz vor Malware
        </li>
        <li>
           Backup- und Recoverykonzept - Erstellen eines Backup- und Recoverykonzepts
        </li>
         <li>
           Brandmeldeanlagen - Feuer- und Rauchmeldeanlagen
        </li>
         <li>
           Feuerlöschgeräte - CO2 Feuerlöschgeräte in Serverräumen
        </li>
         <li>
           Klimaanlage - Klimaanlage in Serverräumen
        </li>
         <li>
           Redundante Datenhaltung - Redundante Datenhaltung (z.B. gespiegelte Festplatten,
            RAID 1 oder höher, gespiegelter Serverraum)
        </li>
         <li>
           Schutzsteckdosenleisten - Schutzsteckdosenleisten in Serverräumen
        </li>
         <li>
           Unterbrechungsfreie Stromversorgung - (USV) Unterbrechungsfreie Stromversorgung
        </li>
    </ul>
    HTML;
    return $body;
}

function B_3_2(): string
{
    $body = <<<HTML
    <h3>3.2 Rasche Wiederherstellbarkeit</h3>
    <p>
        Es müssen Maßnahmen getroffen werden, um Verfügbarkeit der personenbezogenen
        Daten und den Zugang zu ihnen bei einem physischen oder technischen Zwischenfall rasch
        wiederherzustellen.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
            Datenwiederherstellungen - Regelmäßige und dokumentierte Datenwiederherstellungen
        </li>
    </ul>
    HTML;
    return $body;
}

function B_4(): string
{
    $b1 = B_4_1();
    $b2 = B_4_2();

    $body = <<<HTML
    <h2>4. Weitere Maßnahmenbereiche</h2>
    $b1
    $b2
    HTML;
    return $body;
}

function B_4_1(): string
{
    $body = <<<HTML
    <h3>4.1 Auftragskontrolle</h3>
    <p>
        Es muss dafür gesorgt werden, dass personenbezogene Daten, die im Auftrag verarbeitet
        werden, nur entsprechend den Weisungen des Auftraggebers verarbeitet werden können.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
           Audits - Regelmäßige Datenschutzaudits des betrieblichen Datenschutzbeauftragten
        </li>
        <li>
           Auswahl - Auswahl des Auftragnehmers unter Sorgfaltsgesichtspunkten (insbesondere
            hinsichtlich Datensicherheit)
        </li>
         <li>
           AV-Vertrag - Abschluss einer Vereinbarung zur Auftragsverarbeitung gem. Art. 28
            DS-GVO.
        </li>
         <li>
           Laufende Überprüfung - Laufende Überprüfung des Auftragnehmers und seiner Tätigkeiten
        </li>
    </ul>
    HTML;
    return $body;
}

function B_4_2(): string
{
    $body = <<<HTML
    <h3>4.2 Datenschutz-Managementsystem</h3>
    <p>
        Es muss ein Verfahren zur regelmäßigen Überprüfung, Bewertung und Evaluierung des
        Datenschutzes und der Wirksamkeit der festgelegten technischen und organisatorischen
        Maßnahmen implementiert sein.
    </p>
    <p>
        Beim Auftragsverarbeiter umgesetzte Maßnahmen:
    </p>
    <ul>
        <li>
            Audits - Durchführung regelmäßiger interner Audits
        </li>
         <li>
            DSB - Benennung eines Datenschutzbeauftragten
        </li>
         <li>
            Managementsystem Datenschutz - Managementsystem zum Datenschutz (z.B. in
            Anlehnung an ISO 27701)
        </li>
         <li>
            Schulung - Schulungen aller zugriffsberechtigten Mitarbeiter. Regelmäßig stattfindende
            Nachschulungen.
        </li>
         <li>
            Softwaregestützte Tools - Einsatz softwaregestützter Tools zur Einhaltung der
            datenschutzrechtlichen Anforderungen (z.B. audatis MANAGER)
        </li>
         <li>
            Verpflichtung - Verpflichtung auf die Vertraulichkeit gem. Art. 28 Abs. 3 S. 2 lit. b, Art. 29,
            Art. 32 Abs. 4 DS-GVO
        </li>
    </ul>
    HTML;
    return $body;
}