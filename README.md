[![Moodle Plugin CI](https://github.com/moodleblockevaluation/moodle-block_evaluation/actions/workflows/moodle-plugin-ci.yml/badge.svg)](https://github.com/moodleblockevaluation/moodle-block_evaluation/actions/workflows/moodle-plugin-ci.yml)
[![Latest Release](https://img.shields.io/github/v/release/moodleblockevaluation/moodle-block_evaluation)](https://github.com/moodleblockevaluation/moodle-block_evaluation/releases)
[![PHP Support](https://img.shields.io/badge/php-8.1--8.4-blue)](https://github.com/moodleblockevaluation/moodle-block_evaluation/actions)
[![Moodle Support](https://img.shields.io/badge/Moodle-4.5--5.2+-orange)](https://github.com/moodleblockevaluation/moodle-block_evaluation/actions)
[![License GPL-3.0](https://img.shields.io/github/license/moodleblockevaluation/moodle-block_evaluation?color=lightgrey)](https://github.com/moodleblockevaluation/moodle-block_evaluation/blob/main/LICENSE)
[![GitHub contributors](https://img.shields.io/github/contributors/moodleblockevaluation/moodle-block_evaluation)](https://github.com/moodleblockevaluation/moodle-block_evaluation/graphs/contributors)

Projekt - Online-Lehrevaluation Hochschule Neubrandenburg mittels Moodle Feedback und Zusatztools 

Projektleitung: Dipl.-Inform. J. Schäfer 

* 2026:  
  * Projektleitung: Luca Bösch  
  * Projektteilnehmer: Max Fromme,  Christian Peske  

Block Evaluation
=================

This block plugin displays, on a role-by-role basis, the upcoming teaching evaluations due within the specified evaluation period for the roles
* Dean of Studies
* Lecturers
* Students
in the dashboard.

To this end, additional role-specific information is provided for each teaching evaluation, such as
* Dean of Studies
  expected number of response records (number of active course room participants) | current number of response records
* Lecturers
  expected number of response records (number of active course room participants) | current number of response records
* Students
  processing status (completed | still to be completed)   

The role of Dean of Studies is assigned at course category level.

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

In diesem Block-Plugin werden rollenbasiert die in der festgelegten Lehrevaluationszeit anstehenden Lehrevaluationen für die Rollen
* Studiendekan
* Lehrende
* Studierende
im Dashboard angezeigt.

Dazu werden rollenbasiert weitere Informationen je Lehrevaluation bereitgestellt wie
* Studiendekan
   voraussichtliche Anzahl der Antwortdatensätze (Anzahl aktive Kursraumteilnehmer*innen) | aktuelle Anzahl der Antwortdatensätze
* Lehrende
  voraussichtliche Anzahl der Antwortdatensätze (Anzahl aktive Kursraumteilnehmer*innen) | aktuelle Anzahl der Antwortdatensätze
* Studierende
  Status der Bearbeitung (ausgefüllt | noch auszufüllen)   

Die Rolle Studiedekan wird auf Kurskategorieebene zugeteilt.


How it works/ Wie es funktioniert
==================================

(1) Download the plugin and unpack zip file to /blocks directory.

(2) Go to Site administration > Notifications to complete the plugin installation.

or

Go to Site administration > Plugins > Install plugins > Install plugin from ZIP file

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

(1) Laden Sie das Plugin herunter und entpacken Sie die Zip-Datei in das Verzeichnis /blocks.

(2) Gehen Sie zu Website-Administration > Systemnachrichten, um die Installation des Plugins abzuschließen.

oder

Gehen Sie zu Website-Administration > Plugins > Plugin installieren > Plugin aus einer ZIP-Datei installieren
