<?php

function Einleitung(string $Kundennummer, string $Firma, string $Adresse, string $Ort): string
{
  $Kundennummer = htmlspecialchars($Kundennummer);
  $Firma = htmlspecialchars($Firma);
  $Adresse = htmlspecialchars($Adresse);
  $Ort = htmlspecialchars($Ort);

  $body = <<<HTML
    <bookmark content="Einleitung" />
    <h1>
      Datenschutzvereinbarung zur Auftragsverarbeitung gemäß Art. 28 DS-GVO
    </h1>
    <p>zwischen dem Verantwortlichen:</p>
    <p>
        Kundennummer: $Kundennummer<br>
        $Firma<br>
        $Adresse<br>
        $Ort<br>
        (nachstehend Auftraggeber genannt)  
    </p>
    <p>und dem Auftragsverarbeiter:</p>
    <p>
      Computer Extra GmbH<br />
      Harleshäuser Str. 8<br />
      34130 Kassel<br />
      (nachstehend Auftragnehmer genannt)
    </p>
    HTML;

  return $body;
}

function Unterschrift(string $Ort, string $Vertretungsberechtigter): string
{
  date_default_timezone_set('Europe/Berlin');

  $Ort = htmlspecialchars($Ort);
  $Vertretungsberechtigter = htmlspecialchars($Vertretungsberechtigter);
  $heute = date("d.m.Y");

  $body = <<<HTML
    <p></p>
    <p></p>
    <p></p>
    <p>$Ort, $heute</p>
    <p>$Vertretungsberechtigter (Vertretungsberechtigter des Auftraggebers)</p>
    <p></p>
    <p></p>
    <p>Kassel, $heute</p>
    <p>Christian Krauss (Computer Extra GmbH)</p>
    <p>[Diese Vereinbarung ist ohne Unterschrift gültig]</p>
  HTML;
  return $body;
}