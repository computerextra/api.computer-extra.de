<?php

function Einleitung(string $Kundennummer, string $Firma, string $Adresse, string $Ort): string
{
    return `<bookmark content="Einleitung" />
    <h1>
      Datenschutzvereinbarung zur Auftragsverarbeitung gemäß Art. 28 DS-GVO
    </h1>
    <p>zwischen dem Verantwortlichen:</p>
    <p>
    ` . "Kundennummer: $Kundennummer<br>$Firma<br>$Adresse<br>$Ort<br>" . `
    (nachstehend Auftraggeber genannt)
    </p>
    <p>und dem Auftragsverarbeiter:</p>
    <p>
      Computer Extra GmbH<br />
      Harleshäuser Str. 8<br />
      34130 Kassel<br />
      (nachstehend Auftragnehmer genannt)
    </p>`;
}