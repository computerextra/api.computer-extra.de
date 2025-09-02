<?php

function Präambel(): string
{
    $body = <<<HTML
    <bookmark content="Präambel" />
    <h2>Präambel</h2>
    <p>
        Diese Vereinbarung konkretisiert die datenschutzrechtlichen
        Verpflichtungen der Vertragsparteien, die sich aus der in dieser
        Vereinbarung und der in <strong>Anlage A</strong> beschriebenen
        Auftragsverarbeitung ergeben. Sie findet Anwendung auf alle Tätigkeiten,
        die mit der Dienstleistung in Zusammenhang stehen und bei denen
        Mitarbeiter des Auftragnehmers oder durch den Auftragnehmer beauftragte
        Dritte mit personenbezogenen Daten des Auftraggebers in Berührung kommen
        können.
    </p>
    <p>
        Einzelvereinbarungen in dieser Datenschutzvereinbarung gehen den
        Allgemeinen Geschäftsbedinungen (AGB) des Auftragnehmers vor.
    </p>
    HTML;

    return $body;
}

function Definitionen(): string
{
    $body = <<<HTML
    <bookmark content="§ 1 Definitionen" />
    <h2>§ 1 Definitionen</h2>
    <ol>
        <li>
          Personenbezogene Daten <br />
          Nach Art. 4 (1) DS-GVO sind personenbezogene Daten alle Informationen,
          die sich auf eine identifizierte oder identifizierbare natürliche
          Person (im Folgenden "betroffene Person") beziehen; als
          identifizierbar wird eine natürliche Person angesehen, die direkt oder
          indirekt, insbesondere mittels Zuordnung zu einer Kennung wie einem
          Namen, zu einer Kennnummer, zu Standortdaten, zu einer Online-Kennung
          oder zu einem oder mehreren besonderen Merkmalen identifiziert werden
          kann, die Ausdruck der physischen, physiologischen, genetischen,
          psychischen, wirtschaftlichen, kulturellen oder sozialen Identität
          dieser natürlichen Person sind.
        </li>
        <li>
          Auftragsverarbeiter <br />
          Nach Art. 4 (8) DS-GVO ist ein Auftragsverarbeiter eine natürliche
          oder juristische Person, Behörde, Einrichtung oder andere Stelle, die
          personenbezogene Daten im Auftrag des Verantwortlichen verarbeitet.
        </li>
        <li>
          Weisung <br />
          Weisung ist die auf einen bestimmten datenschutzmäßigen Umgang (zum
          Beispiel Speicherung, Pseudonymisierung, Löschung, Herausgabe) des
          Auftragnehmers mit personenbezogenen Daten gerichtete in der Regel
          schriftliche Anordnung des Auftraggebers. Die Weisungen werden vom
          Auftraggeber erteilt und können durch einzelne Weisungen geändert,
          ergänzt oder ersetzt werden (Einzelweisung). Die Weisungen des
          Auftraggebers sind schriftlich oder per E-Mail zu erteilen.
        </li>
    </ol>
    HTML;

    return $body;
}