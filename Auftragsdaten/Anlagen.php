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
    <ul>
        <li style="display: inline-flex; gap: 0.5rem;">
            <input style="checkbox" checked />Auftragsverarbeitung in unserem Warenwirtschaftssystem und Intranet</li>
        <li><input style="checkbox" checked />Durchführung von Fernwartungen</li>
        <li><input style="checkbox" checked />Elektronische Datenverarbeitung zur Bestellabwicklung sowie Warenlieferung</li>
        <li><input style="checkbox" checked />Support Tätigkeiten bei Problemen und Störungen</li>
        <li><input style="checkbox" checked />Verwaltung von Securepoint Firewalls und deren Lizenzen</li>
        <li><input style="checkbox" checked />Durchführung von Datensicherungen und Datenvernichtungen</li>
        <li><input style="checkbox" checked />Versand von Waren mittels Versanddienstleister</li>
    </ul>
    HTML;
    return $body;
}

function A_2(): string
{
    $body = <<<HTML

    HTML;
    return $body;
}

function A_3(): string
{
    $body = <<<HTML

    HTML;
    return $body;
}

function A_4(): string
{
    $body = <<<HTML

    HTML;
    return $body;
}

function A_5(): string
{
    $body = <<<HTML

    HTML;
    return $body;
}