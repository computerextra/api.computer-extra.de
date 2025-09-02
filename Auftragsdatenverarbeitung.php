<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/vendor/autoload.php";

require __DIR__ . "/Auftragsdaten/Einleitung.php";
require __DIR__ . "/Auftragsdaten/Sections.php";

use \Mpdf\Mpdf;
use \Mpdf\MpdfException;

date_default_timezone_set('Europe/Berlin');

// CORS Headers für alle Clients erlauben
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Nur POST-Methoden erlauben
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//   echo json_encode(["message" => "Nur POST-Methoden sind erlaubt", "status" => 405]);
//   exit();
// }

// $_POST = json_decode(file_get_contents('php://input'), true);

// $Kundennummer = $_POST["Kundennummer"];
// $Firma = $_POST["Firma"];
// $Adresse = $_POST["Adresse"];
// $Postleitzahl = $_POST["Postleitzahl"];
// $Ort = $_POST["Ort"];
// $Land = $_POST["Land"];
// $Vertretungsberechtigter = $_POST["Vertretungsberechtigter"];

try {
  $mpdf = new Mpdf(["format" => "A4", 'mode' => 'utf-8']);

  $mpdf->PDFA = true;

  $mpdf->SetAuthor('Computer Extra GmbH'); // add the author name
  $mpdf->setHeader('Datenschutzvereinbarung zur Auftragsverarbeitung gemäß Art. 28 DS-GVO');
  $mpdf->setFooter('Computer Extra GmbH|Stand: {DATE d.m.Y (H:i)}|Seite: {PAGENO}');

  $mpdf->WriteHTML(Einleitung("D12345", "TestFirma", "Harleshäuser Str. 8", "34130 Kassel"));
  $mpdf->WriteHTML(Präambel());
  $mpdf->WriteHTML(Definitionen());




  //     </section>
//     <section id="§ 2 Anwendungsbereich und Verantwortlichkeit">
//       <h2>§ 2 Anwendungsbereich und Verantwortlichkeit</h2>
//       <ol>
//         <li>
//           Der Auftragnehmer erbringt im Auftrag des Auftraggebers
//           Hosting-Leistungen in Form einer Software as a Service (KAS),
//           Auftragsverarbeitung, Fernwartungen, Datenrettung,
//           IT-Dienstleistungen, Paketversand, Eletrkronische Datenverarbeitung
//           zur Bestellabwicklung sowie Warenlieferung, direkt Versand vom
//           Lieferanten, Kundenservice, vor Ort Service, Technischer Support,
//           Vertrieb von MPS-Verträgen. In diesem Zusammenhang ist nicht
//           ausgeschlossen, dass der Auftragnehmer Zugriff auf personenbezogene
//           Daten bekommt bzw. Kenntniss von diesen erlangt. Nach Art. 28 DSGVO
//           ist daher der Abschluss einer Vereinbarung zur Verarbeitung im Auftrag
//           erforderlich.
//         </li>
//         <li>
//           Der Auftraggeber hat den Auftragnehmer im Rahmen der
//           Sorgfaltspflichten des Art. 28 DS-GVO als Dienstleister ausgewählt.
//           Voraussetzung für die Zulässigkeit einer Datenverarbeitung im Auftrag
//           ist, dass der Auftraggeber dem Auftragnehmer den Auftrag schriftlich
//           erteilt. Dieser Vertrag enthält nach dem Willen der Parteien und
//           insbesondere des Auftraggebers den schriftlichen Auftrag zur
//           Auftragsverarbeitung i.S.d. Art 28 Abs. 3 DS-GVO und regelt die Rechte
//           und Pflichten der Parteien zum Datenschutz im Zusammenhang mit der
//           Erbringung der Leistungen.
//         </li>
//         <li>
//           Das Eigentum an den personenbezogenen Daten liegt ausschließlich beim
//           Auftraggeber als "Verantwortlichem" im Sinne der DS-GVO. Aufgrund
//           dieser Verantwortlichkeit kann der Auftraggeber auch während der
//           Laufzeit des Vertrages und nach Beendigung des Vertrages die
//           Berichtigung, Löschung, Sperrung und Herausgabe von personenbezogenen
//           Daten verlangen.
//         </li>
//       </ol>
//     </section>
//     <section id="§ 3 Gegenstand und Dauer des Auftrages">
//       <h2>§ 3 Gegenstand und Dauer des Auftrages</h2>
//       <ol>
//         <li>
//           Der Gegenstand des Auftrages ist in
//           <strong>Anlage A</strong> niedergelegt.
//         </li>
//         <li>
//           Diese Vereinbarung tritt mit ihrer Unterzeichnung durch beide Parteien
//           in Kraft und endet im Regelfall mit Kündigung des zugrundeliegenden
//           Hauptvertrages laut AGB. Das Recht zur außerordentlichen Kündigung
//           bleibt unberührt.
//         </li>
//       </ol>
//     </section>
//     <section
//       id="§ 4 Beschreibung der Verarbeitung, Daten und betroffener Personen"
//     >
//       <h2>§ 4 Beschreibung der Verarbeitung, Daten und betroffener Personen</h2>
//       <p>
//         Umfang, Art und Zweck der Verarbeitung sind ebenso wie die Art der Daten
//         und der Kreis der betroffenen Personen in
//         <strong>Anlage A</strong> beschrieben.
//       </p>
//     </section>
//     <section id="§ 5 Technische und organisatorische Maßnahmen">
//       <h2>§ 5 Technische und organisatorische Maßnahmen</h2>
//       <p>
//         Der Auftragnehmer verpflichtet sich gegenüber dem Auftraggeber zur
//         Einhaltung der technischen und organisatorischen Maßnahmen, die zur
//         Wahrung der anzuwendenden Datenschutzvorschriften angemessen und
//         erforderlich sind.
//       </p>
//       <ol>
//         <li>
//           Da der Auftragnehmer den Betrieb der Software as a Service Leistungen
//           für den Auftraggeber auch außerhalb der Geschäftsräume des
//           Auftraggebers durchführt, sind vom Auftragnehmer zwingend die von ihm
//           getroffenen technischen und organisatorischen Maßnahmen i.S.d. Art. 28
//           Abs. 3 lit. C DS-GVO, Art. 32 DS-GVO i.V.m. Art. 5 Abs. 1 und Abs. 2
//           DS-GVO hierzu zu dokumentieren und dem Auftraggeber zur Prüfung zu
//           übergeben.
//         </li>
//         <li>
//           Die Maßnahmen dienen der Datensicherheit und der Gewährleistung eines
//           dem Risiko angemessenen Schutzniveaus hinsichtlich der
//           Vertraulichkeit, der Integrität, der Verfügbarkeit sowie der
//           Belastbarkeit der mit diesem Auftrag in Zusammenhang stehenden
//           Systeme. Dabei sind der Stand der Technik, die Implementierungskosten
//           und die Art, der Umfang und die Zwecke der Verarbeitung sowie die
//           unterschiedliche Eintrittswahrscheinlichkeit und Schwere des Risikos
//           für die Rechte und Freiheiten natürlicher Personen im Sinne von Art.
//           32 Abs. 1 DS-GVO zu berücksichtigen.
//         </li>
//         <li>
//           Der zum Zeitpunkt des Vertragsschlusses bestehende Stand der
//           technischen und organisatorischen Maßnahmen ist als
//           <strong
//             >Anlage B "Technische und organisatorische Maßnahmen zum
//             Datenschutz"</strong
//           >
//           dieser Vereinbarung beigefügt. Die Parteien sind sich darüber einig,
//           dass zur Anpassung an technische und rechtliche Gegebenheiten
//           Änderungen der technischen und organisatorischen Maßnahmen
//           erforderlich werden können. Wesentliche Änderungen, die die
//           Integrität, Vertraulichkeit oder Verfügbarkeit der personenbezogenen
//           Daten beeinträchtigen können, wird der Auftragnehmer im Vorwege mit
//           dem Auftraggeber abstimmen. Maßnahmen, die lediglich geringfügige
//           technische oder organisatorische Änderungen mit sich bringen und die
//           Integrität, Vertraulichkeit und Verfügbarkeit der personenbezogenen
//           Daten nicht negativ beeinträchtigen, können vom Auftragnehmer ohne
//           Abstimmung mit dem Auftraggeber umgesetzt werden. Der Auftraggeber
//           kann jederzeit eine aktuelle Fassung der vom Auftragnehmer getroffenen
//           technischen und organisatorischen Maßnahmen anfordern.
//         </li>
//       </ol>
//     </section>
//     <section id="§ 6 Berichtigung, Einschränkung und Löschung von Daten">
//       <h2>§ 6 Berichtigung, Einschränkung und Löschung von Daten</h2>
//       <ol>
//         <li>
//           Der Auftragnehmer darf die Daten, die im Auftrag verarbeitet werden,
//           nicht eigenmächtig sondern nur nach dokumentierter Weisung des
//           Auftraggebers berichtigen, löschen oder deren Verarbeitung
//           einschränken. Soweit eine betroffene Person sich diesbezüglich
//           unmittelbar an den Auftragnehmer wendet, wird der Auftragnehmer dieses
//           Ersuchen unverzüglich an den Auftraggeber zur Freigabe weiterleiten.
//         </li>
//         <li>
//           Die Umsetzung von Löschkonzept, Recht auf Vergessenwerden,
//           Berichtigung, Datenportabilität und Auskunft sind nur nach
//           dokumentierter Weisung des Auftraggebers unmittelbar durch den
//           Auftragnehmer sicherzustellen.
//         </li>
//         <li>
//           Kopien oder Duplikate der Daten werden ohne Wissen des Auftraggebers
//           nicht erstellt. Hiervon ausgenommen sind Sicherheitskopien, soweit sie
//           zur Gewährleistung einer ordnungsgemäßen Datenverarbeitung
//           erforderlich sind, sowie Daten, die im Hinblick auf die Einhaltung
//           gesetzlicher Aufbewahrungspflichten erforderlich sind.
//         </li>
//         <li>
//           Nach Abschluss der vertraglich vereinbarten Arbeiten oder früher nach
//           Aufforderung durch den Auftraggeber - spätestens jedoch mit Beendigung
//           der Leistungsvereinbarung - hat der Auftragnehmer sämtliche in seinen
//           Besitz gelangten Unterlagen, erstellte Verarbeitungs- und
//           Nutzungsergebnisse sowie Datenbestände, die im Zusammenhang mit dem
//           Auftragsverhältnis stehen, dem Auftraggeber auszuhändigen oder nach
//           vorheriger Zustimmung datenschutzgerecht zu vernichten. Gleiches gilt
//           für Test- und Ausschussmaterial. Das Protokoll der Löschung ist auf
//           Anforderung vorzulegen.
//         </li>
//         <li>
//           Dokumentationen, die dem Nachweis der auftrags- und ordnungsgemäßen
//           Datenverarbeitung dienen, sind durch den Auftragnehmer entsprechend
//           der jeweiligen Aufbewahrungsfristen über das Vertragsende hinaus
//           aufzubewahren. Er kann sie zu seiner Entlastung bei Vertragsende dem
//           Auftraggeber übergeben.
//         </li>
//       </ol>
//     </section>
//     <section id="§ 7 Pflichten des Auftragnehmers">
//       <h2>§ 7 Pflichten des Auftragnehmers</h2>
//       <ol>
//         <li>
//           Eine Verarbeitung personenbezogener Daten, die sich nicht auf die
//           Erbringung der in §2 angegebenen Verarbeitungen bezieht, ist dem
//           Auftragnehmer untersagt. Es sei denn, dass der Auftraggeber dieser
//           schriftlich zugestimmt hat.
//         </li>
//         <li>
//           Der Auftragnehmer bestätigt, dass er - soweit dieser gesetzlich dazu
//           verpflichtet ist - einen betrieblichen Datenschutzbeauftragten i.S.d.
//           Artt. 38, 39 DS-GVO bestellt hat. Der Auftragnehmer ist derzeit nicht
//           zur Bestellung eines Datenschutzbeauftragten verpflichtet. Dieser hat
//           sich dennoch dazu entschieden, einen internen Datenschutzbeauftragter
//           zu bestellen. Anfragen zum Datenschutz richten Sie bitte an: <br />
//           Johannes Kirchner <br />
//           E-Mail: johannes.kirchner@computer-extra.de <br />
//           Telefon: 0561 / 60144 - 122
//         </li>
//         <li>
//           Der Auftragnehmer wird den Auftraggeber unverzüglich darüber
//           informieren, wenn eine vom Auftraggeber erteilte Weisung nach seiner
//           Auffassung gegen gesetzliche Regelungen verstößt. Der Auftragnehmer
//           ist berechtigt, die Durchführung der betreffenden Weisung solange
//           auszusetzen, bis diese durch den Auftraggeber bestätigt oder geändert
//           wird.
//         </li>
//         <li>
//           Der Auftragnehmer unterrichtet den Auftraggeber unverzüglich bei
//           schwerwiegenden Störungen des Betriebsablaufes, bei Verdacht auf
//           Datenschutzverletzungen oder andere Unregelmäßigkeiten bei der
//           Verarbeitung der personenbezogenen Daten des Auftraggebers.
//         </li>
//         <li>
//           Für den Fall, dass der Auftragnehmer feststellt oder Tatsachen, die
//           Annahme begründen, dass von ihm für den Auftraggeber verarbeitete
//           personenbezogene Daten einer Verletzung des gesetzlichen Schutzes
//           personenbezogener Daten gem. Art. 33 DS-GVO (Datenschutzverstoß bzw.
//           Datenpanne) unterliegen z.B. indem diese unrechtmäßig übermittelt oder
//           auf sonstige Weise Dritten unrechtmäßig zur Kenntnis gelangt sind, hat
//           der Auftragnehmer den Auftraggeber unverzüglich und vollständig über
//           Zeitpunkt, Art und Umfang des Vorfalls bzw. der Vorfälle in
//           Schriftform oder Textform (Fax/E-Mail) zu informieren. Die Meldung an
//           den Auftraggeber muss mindestens folgende Informationen enthalten:
//           <ul>
//             <li>
//               Eine Beschreibung der Art der Verletzung des Schutzes
//               personenbezogener Daten, soweit möglich mit Angabe der Kategorien
//               und der ungefähren Zahl der betroffenen Personen, der betroffenen
//               Kategorien und der ungefähren Zahl der betroffenen
//               personenbezogenen Datensätze.
//             </li>
//             <li>
//               Den Namen und die Kontaktdaten des Datenschutzbeauftragten oder
//               einer sonstigen Anlaufstelle für weitere Informationen.
//             </li>
//             <li>
//               Eine Beschreibung der wahrscheinlichen Folgen der Verletzung des
//               Schutzes personenbezogener Daten.
//             </li>
//             <li>
//               Eine Beschreibung der ergriffenen oder vorgeschlagenen Maßnahmen
//               zur Behebung der Verletzung des Schutzes personenbezogener Daten
//               und gegebenenfalls Maßnahmen zur Abmilderung ihrer möglichen
//               nachteiligen Auswirkungen. Der Auftragnehmer ist darüber hinaus
//               verpflichtet, unverzüglich mitzuteilen, welche Maßnahmen durch den
//               Auftragnehmer getroffen wurden, um die unrechtmäßige Übermittlung
//               bzw. unbefugte Kenntnisnahme durch Dritte künftig zu verhindern.
//             </li>
//           </ul>
//         </li>
//         <li>
//           Der Auftragnehmer stellt auf Anforderung dem Auftraggeber die für das
//           Verzeichnis der Verarbeitungstätigkeiten nach Art. 30 Abs. 1 DS-GVO
//           notwendigen Angaben zur Verfügung und führt als Auftragsverarbeiter
//           selbst ein Verzeichnis von Verarbeitungstätigkeiten nach Art. 30 Abs.
//           2 DS-GVO.
//         </li>
//         <li>
//           Der Auftragnehmer stellt sicher, dass die mit der Verarbeitung der
//           personenbezogenen Daten des Auftraggebers befassten Mitarbeiter gemäß
//           Artt. 28 Abs. 3 S. 2 lit. b, 29, 32 Abs. 4 DS-GVO zur Wahrung der
//           Vertraulichkeit verpflichtet und zuvor mit den für sie relevanten
//           Bestimmungen des Datenschutzes vertraut gemacht wurden. Der
//           Auftragnehmer und jede dem Auftragnehmer unterstellte Person, die
//           Zugriff auf personenbezogenen Daten hat, dürfen diese Daten
//           ausschließlich entsprechend der Weisung des Auftraggebers verarbeiten,
//           einschließlich der in dieser Vereinbarung eingeräumten Befugnisse, es
//           sei denn, dass sie gesetzlich zur Verarbeitung verpflichtet sind.
//           Diese Vertraulichkeitsverpflichtung besteht auch nach Beendigung der
//           Tätigkeit fort.
//         </li>
//         <li>
//           Die Erfüllung der vorgenannten Pflichten ist vom Auftragnehmer zu
//           kontrollieren und in geeigneter Weise nachzuweisen.
//         </li>
//         <li>
//           Des Weiteren verpflichtet sich der Auftragnehmer den Auftraggeber
//           gemäß Art. 28 Abs. 3 lit. f DS-GVO bei der Einhaltung der in Artt. 34
//           - 36 DS-GVO genannten Pflichten zu unterstützen:
//           <ul>
//             <li>
//               Im Rahmen seiner Informationspflicht gegenüber den betroffenen
//               Personen und dem Auftraggeber in diesem Zusammenhang sämtliche
//               relevante Informationen unverzüglich zur Verfügung zu stellen.
//             </li>
//             <li>Bei der Durchführung seiner Datenschutz-Folgenabschätzung</li>
//             <li>
//               Im Rahmen einer vorherigen Konsultation mit der Aufsichtsbehörde.
//             </li>
//           </ul>
//         </li>
//         <li>
//           Der Auftraggeber und der Auftragnehmer arbeiten auf Anfrage mit der
//           Aufsichtsbehörde bei der Erfüllung ihrer Aufgaben zusammen.
//         </li>
//         <li>
//           Der Auftragnehmer hat den Auftraggeber unverzüglich über
//           Kontrollhandlungen und Maßnahmen der Aufsichtsbehörde, soweit sie sich
//           auf diesen Auftrag beziehen, zu informieren. Dies gilt auch, soweit
//           eine zuständige Behörde im Rahmen eines Ordnungswidrigkeits- oder
//           Strafverfahrens in Bezug auf die Verarbeitung personenbezogener Daten
//           bei der Auftragsverarbeitung beim Auftragnehmer ermittelt.
//         </li>
//         <li>
//           Soweit der Auftraggeber seinerseits einer Kontrolle der
//           Aufsichtsbehörde, einem Ordnungswidrigkeits- oder Strafverfahren, dem
//           Haftungsanspruch einer betroffenen Person oder eines Dritten oder
//           einem anderen Anspruch im Zusammenhang mit der Auftragsverarbeitung
//           durch den Auftragnehmer ausgesetzt ist, hat ihn der Auftragnehmer nach
//           besten Kräften zu unterstützen.
//         </li>
//         <li>
//           Der Auftragnehmer kontrolliert regelmäßig die internen Prozesse sowie
//           die technischen und organisatorischen Maßnahmen, um zu gewährleisten,
//           dass die Verarbeitung in seinem Verantwortungsbereich im Einklang mit
//           den Anforderungen des geltenden Datenschutzrechts erfolgt und der
//           Schutz der Rechte der betroffenen Person gewährleistet wird.
//         </li>
//       </ol>
//     </section>
//     <section id="§ 8 Rechte und Pflichten des Auftraggebers">
//       <h2>§ 8 Rechte und Pflichten des Auftraggebers</h2>
//       <ol>
//         <li>
//           Der Auftraggeber hat das Recht, jederzeit ergänzende Weisungen über
//           Art, Umfang und Verfahren der Entwicklung, Pflege und Wartung von
//           Software und/oder IT-Systemen gegenüber dem Auftragnehmer zu erteilen.
//           Weisungen können
//           <ul>
//             <li>schriftlich</li>
//             <li>per Fax</li>
//             <li>per E-Mail</li>
//             <li>mündlich</li>
//           </ul>
//           erfolgen. Der Auftraggeber soll mündliche Weisungen unverzüglich in
//           Textform (z.B. Fax oder E-Mail) gegenüber dem Auftragnehmer
//           bestätigen.
//         </li>
//         <li>
//           Der Auftraggeber hat den Auftragnehmer unverzüglich und vollständig zu
//           informieren, wenn er bei der Prüfung der Auftragsergebnisse Fehler
//           oder Unregelmäßigkeiten bzgl. datenschutzrechtlicher Bestimmungen
//           feststellt.
//         </li>
//         <li>
//           Dem Auftraggeber obliegen die aus Art. 33 Abs. 1 DS-GVO resultierenden
//           Meldepflichten.
//         </li>
//         <li>
//           Der Auftraggeber legt die Maßnahmen zur Rückgabe der überlassenen
//           Datenträger und/oder Löschung der gespeicherten personenbezogenen
//           Daten nach Beendigung des Auftrages vertraglich oder durch Weisung
//           fest.
//         </li>
//         <li>
//           Erteilt der Auftraggeber Einzelweisungen, die über den vertraglich
//           vereinbarten Leistungsumfang hinausgehen, sind die dadurch begründeten
//           Kosten vom Auftraggeber zu tragen.
//         </li>
//       </ol>
//     </section>
//     <section id="§ 9 Wahrung von Rechten der betroffenen Person">
//       <h2>§ 9 Wahrung von Rechten der betroffenen Person</h2>
//       <ol>
//         <li>
//           Der Auftraggeber ist für die Wahrung der Rechte der betroffenen Person
//           verantwortlich.
//         </li>
//         <li>
//           Soweit eine Mitwirkung des Auftragnehmers für die Wahrung von
//           Betroffenenrechten - insbesondere auf Auskunft, Berichtigung,
//           Einschränkung, Datenübertragbarkeit oder Löschung - durch den
//           Auftraggeber erforderlich ist, wird der Auftragnehmer die jeweils
//           erforderlichen Maßnahmen nach Weisung des Auftraggebers treffen.
//         </li>
//         <li>
//           Soweit eine betroffene Person sich unmittelbar an den Auftragnehmer
//           zwecks Berichtigung, Löschung oder Einschränkung oder
//           Datenübertragbarkeit seiner Daten wenden sollte, wird der
//           Auftragnehmer dieses Ersuchen unverzüglich an den Auftraggeber
//           weiterleiten.
//         </li>
//         <li>
//           Regelungen über eine etwaige Vergütung von Mehraufwänden, die durch
//           Mitwirkungsleistungen im Zusammenhang mit Geltendmachung von
//           Betroffenenrechten gegenüber dem Auftraggeber beim Auftragnehmer
//           entstehen, bleiben unberührt.
//         </li>
//       </ol>
//     </section>
//     <section id="§ 10 Kontrollbefugnisse">
//       <h2>§ 10 Kontrollbefugnisse</h2>
//       <ol>
//         <li>
//           Der Auftraggeber hat das Recht, die Einhaltung der gesetzlichen
//           Vorschriften zum Datenschutz und die Einhaltung der zwischen den
//           Parteien getroffenen vertraglichen Regelungen sowie die Einhaltung der
//           Weisungen des Auftraggebers durch den Auftragnehmer jederzeit im
//           erforderlichen Umfang zu kontrollieren.
//         </li>
//         <li>
//           Der Auftragnehmer ist dem Auftraggeber gegenüber zur
//           Auskunftserteilung verpflichtet, soweit dies zur Durchführung der
//           Kontrolle i.S.d. Abs. 1 erforderlich ist.
//         </li>
//         <li>
//           Der Auftraggeber kann nach vorheriger Anmeldung mit angemessener Frist
//           die Kontrolle im Sinne des Abs. 1 in der Betriebsstätte des
//           Auftragnehmers zu den jeweils üblichen Geschäftszeiten vornehmen. Der
//           Auftraggeber wird dabei Sorge dafür tragen, dass die Kontrollen nur im
//           erforderlichen Umfang durchgeführt werden, sofern die Betriebsabläufe
//           des Auftragnehmers durch die Kontrollen gestört werden.
//         </li>
//         <li>
//           Der Auftragnehmer ist verpflichtet, im Falle von Maßnahmen der
//           Aufsichtsbehörde gegenüber dem Auftraggeber i.S.d. Art. 58 DS-GVO,
//           insbesondere im Hinblick auf Auskunfts- und Kontrollpflichten die
//           erforderlichen Auskünfte an den Auftraggeber zu erteilen.
//         </li>
//         <li>
//           Der Auftragnehmer erbringt den Nachweis technischer und
//           organisatorischer Maßnahmen, die nicht nur den konkreten Auftrag
//           betreffen. Dabei kann dies erfolgen durch:
//           <ul>
//             <li>
//               die Einhaltung genehmigter Verhaltensregeln gemäß Art. 40 DS-GVO.
//             </li>
//             <li>
//               die Zertifizierung nach einem genehmigten Zertifizierungsverfahren
//               gemäß Art. 42 DS-GVO.
//             </li>
//             <li>
//               aktuelle Testate, Berichte oder Berichtsauszüge unabhängiger
//               Instanzen (z.B. Wirtschaftsprüfer, Revision,
//               Datenschutzbeauftragter, IT-Sicherheitsbeauftragter,
//               Datenschutzauditoren.
//             </li>
//             <li>
//               eine geeignete Zertifizierung durch IT-Sicherheits- oder
//               Datenschutzaudit (z.B. nach ISO 27001 oder BSI-Grundschutz).
//             </li>
//           </ul>
//         </li>
//         <li>
//           Die Kosten für Aufwände einer Kontrolle beim Auftragnehmer gem. Abs. 3
//           und 4 können gegenüber dem Auftraggeber geltend gemacht werden.
//         </li>
//       </ol>
//     </section>
//     <section id="§ 11 Unterauftragsverhältnisse">
//       <h2>§ 11 Unterauftragsverhältnisse</h2>
//       <ol>
//         <li>
//           Der Auftragnehmer nimmt für die Erbringung der Leistungen im Auftrag
//           des Auftraggebers keine Leistungen von Dritten in Anspruch, welche
//           nicht in Abs. 3 genannt werden, die in seinem Auftrag Daten gem. Art.
//           28 DS-GVO verarbeiten ("Unterauftragnehmer").
//         </li>
//         <li>
//           Der Auftraggeber ist damit einverstanden, dass der Auftragnehmer zur
//           Erfüllung seiner vertraglich vereinbarten Leistungen Unternehmen zur
//           Leistungserfüllung heranzieht bzw. mit Leistungen unterbeauftragt. Es
//           werden genehmigte Unterauftragnehmer laut
//           <strong>Anlage A</strong> eingesetzt. <br />
//           Der Auftraggeber stimmt allgemein einer Änderung der
//           Unter-Unterauftragnehmer durch den Unterauftragnehmer zu
//         </li>
//         <li>
//           Im Falle einer Beauftragung, hat der Auftragnehmer den
//           Unterauftragnehmer sorgfältig auszuwählen und vor der Beauftragung zu
//           prüfen, dass dieser die zwischen Auftraggeber und Auftragnehmer
//           getroffenen Vereinbarungen einhalten kann. Der Auftragnehmer hat
//           insbesondere vorab und regelmäßig während der Vertragsdauer zu
//           kontrollieren, dass der Unterauftragnehmer die nach Art. 28 Abs. 3
//           lit. c, 32 DS-GVO i.V.m. Art. 5 Abs. 1, Abs. 2 DS-GVO erforderlichen
//           technischen und organisatorischen Maßnahmen zum Schutz
//           personenbezogener Daten getroffen hat. Das Ergebnis der Kontrolle ist
//           vom Auftragnehmer zu dokumentieren und auf Anfrage dem Auftraggeber zu
//           übermitteln. Der Auftragnehmer ist verpflichtet, sich vom
//           Unterauftragnehmer bestätigen zu lassen, dass dieser einen
//           betrieblichen Datenschutzbeauftragten i.S.d. Artt. 37-39 DS-GVO
//           bestellt hat.
//         </li>
//         <li>
//           Der Auftragnehmer hat sicherzustellen, dass die in diesem Vertrag
//           vereinbarten Regelungen und ggf. ergänzende Weisungen des
//           Auftraggebers auch gegenüber dem Unterauftragnehmer gelten. Der
//           Auftragnehmer hat die Einhaltung dieser Pflichten regelmäßig zu
//           kontrollieren.
//         </li>
//         <li>
//           Die Verpflichtung des Unterauftragnehmers muss grundsätzlich
//           schriftlich erfolgen, sofern keine andere Form angemessen ist. Dem
//           Auftraggeber ist die Verpflichtung auf Anfrage in Kopie zu
//           übermitteln.
//         </li>
//         <li>
//           Der Auftragnehmer ist insbesondere verpflichtet, durch vertragliche
//           Regelungen sicherzustellen, dass die Kontrollbefugnisse (Ziff. 10
//           dieser Vereinbarung) des Auftraggebers und von Aufsichtsbehörden auch
//           gegenüber dem Unterauftragnehmer gelten und entsprechende
//           Kontrollrechte von Auftraggeber und Aufsichtsbehörden vereinbart
//           werden. Es ist zudem vertraglich zu regeln, dass der
//           Unterauftragnehmer diese Kontrollmaßnahmen und etwaige
//           Vor-Ort-Kontrollen zu dulden hat.
//         </li>
//         <li>
//           Eine Beauftragung von weiteren Auftragnehmern der Unterauftragnehmer
//           bedarf der Zustimmung des Auftragnehmers, sofern dies nicht lediglich
//           Nebenleistungen (z.B. Telekommunikationsleistungen) betrifft. Der
//           Unterauftragnehmer stellt sicher, dass die in dieser Vereinbarung
//           festgelegten Regelungen zur Auftragsverarbeitung auch für seine
//           weiteren Auftragnehmer vertraglich vereinbart werden.
//         </li>
//         <li>
//           Die Beauftragung von Subunternehmern zur Verarbeitung von Daten des
//           Verantwortlichen ist dem Auftragnehmer nur mit Genehmigung des
//           Auftraggebers gestattet, Art. 28 Abs. 2 DSGVO, welche auf einem der
//           o.g. Kommunikationswege mit Ausnahme der mündlichen Gestattung
//           erfolgen muss. Die Zustimmung kann nur erteilt werden, wenn der
//           Auftragnehmer dem Auftraggeber Namen und Anschrift sowie due
//           vorgesehene Tätigkeit des Subunternehmers mitteilt.
//         </li>
//       </ol>
//     </section>
//     <section id="§ 12 Datengeheimnis und Geheimhaltungspflichten">
//       <h2>§ 12 Datengeheimnis und Geheimhaltungspflichten</h2>
//       <ol>
//         <li>
//           Der Auftragnehmer verpflichtet sich, die gleichen
//           Geheimnisschutzregeln zu beachten, wie sie dem Auftraggeber obliegen.
//           Der Auftraggeber ist verpflichtet, dem Auftragnehmer etwaige besondere
//           Geheimnisschutzregeln mitzuteilen.
//         </li>
//         <li>
//           Der Auftragnehmer sichert zu, dass ihm die jeweils geltenden
//           datenschutzrechtlichen Vorschriften bekannt sind und er mit der
//           Anwendung dieser vertraut ist.
//         </li>
//         <li>
//           Beide Parteien verpflichten sich, alle Informationen, die sie im
//           Zusammenhang mit der Durchführung dieser Vereinbarung erhalten,
//           zeitlich unbegrenzt vertraulich zu behandeln und nur zur Durchführung
//           des Vertrages zu verwenden. Keine Partei ist berechtigt, diese
//           Informationen ganz oder teilweise zu anderen als den oben genannten
//           Zwecken zu nutzen oder diese Information Dritten zugänglich zu machen.
//         </li>
//         <li>
//           Die vorstehende Verpflichtung gilt nicht für Informationen, die eine
//           der Parteien nachweisbar von Dritten erhalten hat, ohne zur
//           Geheimhaltung verpflichtet zu sein, oder die öffentlich bekannt sind.
//         </li>
//       </ol>
//     </section>
//     <section id="§ 13 Informationspflichten, Schriftformklausel, Rechtswahl">
//       <h2>§ 13 Informationspflichten, Schriftformklausel, Rechtswahl</h2>
//       <ol>
//         <li>
//           Sollten die personenbezogenen Daten des Auftraggebers beim
//           Auftragnehmer durch Pfändung oder Beschlagnahme, durch ein Insolvenz-
//           oder Vergleichsverfahren oder durch sonstige Ereignisse oder Maßnahmen
//           Dritter gefährdet werden, so hat der Auftragnehmer den Auftraggeber
//           unverzüglich darüber zu informieren. Der Auftragnehmer wird alle in
//           diesem Zusammenhang Verantwortlichen unverzüglich darüber informieren,
//           dass die Hoheit und das Eigentum an den personenbezogenen Daten
//           ausschließlich beim Auftraggeber als "Verantwortlichem" im Sinne der
//           DS-GVO liegen.
//         </li>
//         <li>
//           Änderungen und Ergänzungen dieser Vereinbarung und aller ihrer
//           Bestandteile - einschließlich etwaiger Zusicherungen des
//           Auftragnehmers - bedürfen einer schriftlichen Vereinbarung und des
//           ausdrücklichen Hinweises darauf, dass es sich um eine Änderung bzw.
//           Ergänzung dieser Bedingungen handelt. Dies gilt auch für den Verzicht
//           auf dieses Formerfordernis.
//         </li>
//         <li>
//           Bei Unwirksamkeit einer Bestimmung in diesen Vertragsbedingungen
//           bleiben die übrigen Bestimmungen gleichwohl wirksam. Die
//           Vertragsparteien verpflichten sich, eine unwirksame Bestimmung oder
//           eine planwidrig fehlende Bestimmung nach Treu und Glauben durch eine
//           Bestimmung zu ersetzen, die dem gemeinsam verfolgten Zweck der
//           Vertragsparteien am nächsten kommt.
//         </li>
//       </ol>
//     </section>
//      <section id="Unterschriften">
// `;

  //   // TODO:   <p>Kaufungen, 25.08.2025</p>
// //       <p>gez. Markus Barella (Vertretungsberechtigter des Auftraggebers)</p>

  //   $body .= `
//       <p>Kassel, 25.08.2025</p>
//       <p>gez. Christian Krauss (Computer Extra GmbH)</p>
//     </section>`;




  // $mpdf->WriteHTML($body);
  // $date = date('d.m.Y', time());
  // $filename = "AVV-$Kundennummer-$date.pdf";
  // $mpdf->OutputFile(__DIR__ . "/$filename");
  $mpdf->Output();

  // echo json_encode(["message" => "PDF Erfolgreich erstellt.", "filename" => $filename, "status" => 200]);
  exit();
} catch (MpdfException $e) {
  $msg = $e->getMessage();
  echo json_encode(["message" => "Fehler: $msg", "filename" => null, "status" => 500]);
  // exit();
}
