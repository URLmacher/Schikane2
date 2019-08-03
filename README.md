# MULTIPLAYER Cardgame
## Schikane

## Quickstart
1. Repo klonieren
2. Datenbank erstellen utf8_mb4_general_ci name schikane
3. Datenbank migrieren: php index.php migrate latest
4. websockets am server aktivieren extension=sockets in php.ini
5. vhost erstellen schikanezwei.loc od. $config['base_url'] in application/config/config.php anpassen
6. composer install
7. php connection.php im Hauptverzeichnis ausführen um Websockets zu starten
8. Website im Browser aufrufen

**Minimum Viable Product:**
- Kartenspiel für 2 Spieler
- User Login
- Spielersuche
- Freundesliste
- Spiel-Einladungen
- User-Dashboard
- Statistiken
- User-Nachrichten

**Optionale Features:**
- Kartenspiel für 4 Spieler
- Chat
- Feedback Kanal
- geführtes Tutorial
- KI-Matches

------------------------------------------------------------------------------------------------

### Spielregeln
> Zwei volle Karten-Decks mit 4 Jokern werden verwendet
> Karten haben Werte entsprechend ihrer Position, Ass ist 1 und König 13
> Jeder Spieler erhält 6 Karten als _Hand-Karten_
> Und 14 Karten als _Spiel-Stapel_, wobei die oberste Karte aufgedeckt wird
> Es gibt einen _Hauptstapel_, bestehend aus den restlichen Karten, welche immer verdeckt sind
> Jeder Spieler hat 4 _Ablege-Stapel_
> Und einen _Joker-Stapel_
> _Joker-Stapel_ und Ablage-Stapel sind immer für alle Spieler einsehbar

> Ziel ist es, den _Spiel-Stapel_ durch ablegen im _Spielfeld_ zu entleeren
> Gewonnen hat der Spieler, dessen _Spiel-Stapel_ zuerst leer ist
> Karten im _Spielfeld_ dürfen nur in aufsteigender Reihenfolge abgelegt werden
> Alle Spieler teilen sich die Stapel im _Spielfeld_
> Karten aus _Hand-Karten_, _Ablege-Stapel_, _Joker-Stapel_ und _Spiel-Stapel_ dürfen zum ablegen verwendet werden
> Kann ein Spieler nicht im _Spielfeld_ ablegen, muss er eine Karte auf einen _Ablagestapel_ legen
> Durch ablegen auf den _Ablege-Stapel_ endet eine Runde

> Karten dürfen in die _Ablage-Stapel_ in beliebiger Reihenfolge abgelegt werden
> Aus dem _Ablagestapel_ dürfen aber immer nur die obersten Karten agehoben werden
> Wenn die Karten der _Hand-Karten_ vor Runden-Ende leer sind, dürfen Karten aus dem _Hauptstapel_ abgehoben werden
> Joker aus dem _Spiel-Stapel_ dürfen sofort in den _Joker-Stapel_ gelegt werden
> Joker stehen für alle Karten, außer Ass
> Ist ein Stapel im _Spielfeld_ bei König angelangt, wird er in den _Hauptstapel_ gemischt
> Zu Runden-Beginn dürfen fehlende Karten in die _Hand-Karten_ abgehoben werden, bis wieder 6 Karten in den _Hand-Karten_ sind

__________________________________________________________________________________________________


### Umsetzung

**User**
- Registrierung mit Username, Email und Passwort
    - Verifickation per Email
- Login als User 
    - oder Gast mit eingeschränkten Funktionen

**Verbindung**
- Spieler klicken auf Spielersuche
    - diese Spielbereiten Spieler landet in einer Lobby
    - von dort aus kann mit gegenseitigem Einverständnis ein Spiel gestartet werden
- Spielstart gibt beiden Spielern Zugang zu einem privaten Port
    - zur Sicherheit werden Keys ausgetauscht
- Alternativ können aus der Lobby gerade angemeldete User eingeladen werden
    - Eingeladete erhalten erhalten eine Nachricht mit Link zur Lobby
    - Vielleicht auch per Email 

**Game**
- Karten liegen als JSON am Server, bestehen aus:
    - Name, der später zur Erzeugung von SVGs verwendet wird
    - Wert der Karte 
    - Karten ID
    - Joker-Wert (merkt den Wert der Karte, die ersetzt wird)

- ein Server-Script mischt die Karten und schickt die gerade sichtbaren an den Client

- der Spieler klickt auf eine Karte und danach auf die Position, auf der sie gelegt werden soll
    - Javscript gibt die Auswahl dann an den Server weiter

- der Server überprüft den Zug auf Gültigkeit 
    - bei Gültigkeit werden die Daten verschoben und der Client wird über diese Veränderung in Kenntnis gesetzt

- am Client werden die veränderten Karten und Stapel neu gerendert

- ein Spiel-Script verwaltet Anzahl der Spiel und wer gerade dran ist 
    - ebenso die Spielenden, gewonnen unentschieden oder verloren

**Daeshboard**
- Nachrichten Eingang
- Als Overlay von jeder Seite zugänglich
- Statistik mit erlangten Siegen, K/D Ratio etc.
- Logout 
- Account Löschung

**Friendlist**
- zeigt an wer gerade online ist
- User können eingeladen werden
- User können blockiert werden
- Privatnachrichten können verschickt werden

**Chat**
- In-Game Chat 
- Emoji-Support

**Nachrichten**
- werden vom User-Dashboard verschickt
- vielleicht HTML-Support
- landen im Post Eingang des Dashboards
    - vielleicht auch als Email
- Text-Nachrichten, oder Einladungen mit Links#

**KI**
- aus sichtbaren Karten werden mögliche Züge berechnet
    - potenzielle Züge erhalten eine Bewertung nach Effektivität
    - je nach Schwierigkeitsgrad werden Züge mit besserer oder schlechterer Bewertung ausgeführt
    - gleichwertige Züge werden per Zufall ausgeführt

- anhand der sichtbaren Karten werden Wahrscheinlichkeit des Vorkommens noch nicht gezogener Karten berechnet
    - ein potenzieller Zug wird besser bewertet, wenn das Ziehen einer nützlichen Karte wahrscheinlich ist

- Effektivität erhöht sich durch die Anzahl der in einer Runde ablegbaren Karten
    - KI macht mögliche Züge nicht, wenn die Effektivität nicht hoch genug ist und setzt aus

**Statistik**
- wohnen im Dashboard

- enthalten Daten über zurückliegende Spiele
    - Gegner (KI oder Mensch)
    - Sieg und Verlierung
    - Spielabrüche

- für Administratoren interessante Werte
    - Zeit in Lobby
    - Zeit zwischen Runden
    - Ignores von andern Spielern

**Geführtes Tutorial**
- bei Seitenaufruf angeboten
- startet ein Pseudo-Spiel
    - mit Popups werden Spielregeln erklärt
    - Längen werden geskippt
- Gesamtlänge: 3 - 5 min
