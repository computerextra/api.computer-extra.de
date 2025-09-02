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
    $body = <<<HTML
    <h1>Anlage B: Technische und organisatorische Maßnahmen</h1>

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
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Auftragsverarbeitung in unserem Warenwirtschaftssystem und Intranet
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Durchführung von Fernwartungen
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Elektronische Datenverarbeitung zur Bestellabwicklung sowie Warenlieferung
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Support Tätigkeiten bei Problemen und Störungen
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Verwaltung von Securepoint Firewalls und deren Lizenzen
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Durchführung von Datensicherungen und Datenvernichtungen
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
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
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Verändern (z.B. durch Änderungen der Benutzer an den Datensätzen)
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Übermitteln (z.B. durch Übermittlung per E-Mail von Nachrichten)
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Speichern (z.B. durch Sicherung und Archivierung auf Festplatten, anderen
            Speichersystemen oder Datenträgern)
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Löschen (z.B. durch Löschen von Datensätzen oder Vernichten von Datenträgern)
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Erheben (z.B. durch Einlesen von Mitarbeiterlisten und anderen Daten)
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Einschränken (z.B. durch Deaktivierung von einzelnen Datensätzen)
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
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
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
           Beschäftigtendaten (z.B. Name, Vorname, Benutzername, E-Mailadresse).
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
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
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
           Beschäftigte des Auftraggebers
        </li>
        <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
            <input type="checkbox" checked disabled />
            Beauftragte des Auftraggebers
        </li>
          <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle;">
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
    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        Securepoint GmbH, Bleckeder Landstraße 28, 21337 Lüneburg ((Fern-) Prüfung
        und Wartung Securepoint)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        Meinhardt Städtereinigung GmbH & Co. KG, Nassaustraße 13-15, 65719
        Hofheim-Wallau (Aktenvernichtung)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        audatis Services GmbH, Luisenstraße 1, 32052 Herford (Datenschutzmanager)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        Terra Cloud GmbH, Hankamp 2, 32609 Hüllhorst (Cloud Computing)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        Brother International GmbH, Konrad-Adenauer-Allee 1-11, 61118 Bad Vilbel
        (Direkte Verbrauchsmaterial-Belieferung von Endkunden gemäß Partner MPS
        Alliance-Einzelverträge)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        Systeam GmbH, Industriestraße 8, 96250 Ebensfeld (Dropshipping)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        Synaxon AG, Falkenstraße 31, 33758 Schloß Holte-Stukenbrock (eProcurement /
        Management von Beschaffungskonditionen)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        pcvisit Software AG, Manfred-von-Ardenne-Ring 20, 01099 Dresden
        (Fernwartungen)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        Annegrit Schmiedeknecht, Einzelunternehmerin, Obervellmaer Str. 3, 34128
        Kassel (IT-Schulungen)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        Wortmann AG (Leistungen im Bereich Informationstechnologie Produkt & Handel,
        Service & Dienstleistungen)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        Microsoft Corp., Konrad-Zuse-Str.1, 85716 Unterschleißheim (Einsatz und
        Verwaltung von MS365)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        DHL Paket GmbH, Sträßchensweg 10, 53113 Bonn (Paketversand)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        UNITED PARCEL SERVICE DEUTSCHLAND S.à r.l. & Co. OHG, Görlitzer Straße 1,
        41460 Neuss (Paketversand)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        General Logistics Systems Germany GmbH & Co. OHG, GLS Germany-Straße 1 - 7,
        36286 Neuenstein (Paketversand)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        trans-o-flex Express GmbH & Co. KGaA, Hertzstraße 10, 69469 Weinheim
        (Paketversand)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        G Data CyberDefense AG, Königsallee 178, 44799 Bochum (Vertrieb von Waren
        und Dienstleistungen; Installationsunterstützung; Support; online
        Awareness-Trainings)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        Intos Electronic AG, Siemensstraße 11, 35394 Gießen (Verwaltung
        personenbezogener Daten für die Durchführung des Auftrags beziehungsweise
        zur Abwicklung der Geschäftsbeziehung)
    </li>

    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        AEM Communication GmbH & Co. KG, Obergasse 40-42, 36304 Alsfeld (Wartung und
        Pflege Kundenmanagement; Bereitstellung SaaS f. Kundenmanagement; Support)
    </li>
    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        ALL-INKL.COM - Neue Medien Münnich, Inhaber René Münnich, Hauptstraße 68B,
        02742 Friedersdorf (Web Hosting und Vertrieb von Hosting Verträgen)
    </li>
    <li style="display: inline-flex; gap: 0.5rem; vertical-align: middle">
        <input type="checkbox" checked disabled />
        meetergo GmbH - Hansaring 61, 50670 Köln (Online Beratungstermine
        Verwaltung)
    </li>
    </ul>
    HTML;
    return $body;
}