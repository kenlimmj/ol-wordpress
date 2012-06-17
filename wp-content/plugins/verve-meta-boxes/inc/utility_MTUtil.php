<?php
# Movable Type (r) (C) 2001-2008 Six Apart, Ltd. All Rights Reserved.
# This code cannot be redistributed without permission from www.sixapart.com.
# For more information, consult your Movable Type license.
#
# $Id: MTUtil.php 3128 2008-10-23 20:44:03Z bchoate $


global $Utf8_ASCII;
$Utf8_ASCII = array(
    "\xc3\x80" => 'A',    # A`
    "\xc3\xa0" => 'a',    # a`
    "\xc3\x81" => 'A',    # A'
    "\xc3\xa1" => 'a',    # a'
    "\xc3\x82" => 'A',    # A^
    "\xc3\xa2" => 'a',    # a^
    "\xc4\x82" => 'A',    # latin capital letter a with breve
    "\xc4\x83" => 'a',    # latin small letter a with breve
    "\xc3\x86" => 'AE',   # latin capital letter AE
    "\xc3\xa6" => 'ae',   # latin small letter ae
    "\xc3\x85" => 'A',    # latin capital letter a with ring above
    "\xc3\xa5" => 'a',    # latin small letter a with ring above
    "\xc4\x80" => 'A',    # latin capital letter a with macron
    "\xc4\x81" => 'a',    # latin small letter a with macron
    "\xc4\x84" => 'A',    # latin capital letter a with ogonek
    "\xc4\x85" => 'a',    # latin small letter a with ogonek
    "\xc3\x84" => 'A',    # A:
    "\xc3\xa4" => 'a',    # a:
    "\xc3\x83" => 'A',    # A~
    "\xc3\xa3" => 'a',    # a~
    "\xc3\x88" => 'E',    # E`
    "\xc3\xa8" => 'e',    # e`
    "\xc3\x89" => 'E',    # E'
    "\xc3\xa9" => 'e',    # e'
    "\xc3\x8a" => 'E',    # E^
    "\xc3\xaa" => 'e',    # e^
    "\xc3\x8b" => 'E',    # E:
    "\xc3\xab" => 'e',    # e:
    "\xc4\x92" => 'E',    # latin capital letter e with macron
    "\xc4\x93" => 'e',    # latin small letter e with macron
    "\xc4\x98" => 'E',    # latin capital letter e with ogonek
    "\xc4\x99" => 'e',    # latin small letter e with ogonek
    "\xc4\x9a" => 'E',    # latin capital letter e with caron
    "\xc4\x9b" => 'e',    # latin small letter e with caron
    "\xc4\x94" => 'E',    # latin capital letter e with breve
    "\xc4\x95" => 'e',    # latin small letter e with breve
    "\xc4\x96" => 'E',    # latin capital letter e with dot above
    "\xc4\x97" => 'e',    # latin small letter e with dot above
    "\xc3\x8c" => 'I',    # I`
    "\xc3\xac" => 'i',    # i`
    "\xc3\x8d" => 'I',    # I'
    "\xc3\xad" => 'i',    # i'
    "\xc3\x8e" => 'I',    # I^
    "\xc3\xae" => 'i',    # i^
    "\xc3\x8f" => 'I',    # I:
    "\xc3\xaf" => 'i',    # i:
    "\xc4\xaa" => 'I',    # latin capital letter i with macron
    "\xc4\xab" => 'i',    # latin small letter i with macron
    "\xc4\xa8" => 'I',    # latin capital letter i with tilde
    "\xc4\xa9" => 'i',    # latin small letter i with tilde
    "\xc4\xac" => 'I',    # latin capital letter i with breve
    "\xc4\xad" => 'i',    # latin small letter i with breve
    "\xc4\xae" => 'I',    # latin capital letter i with ogonek
    "\xc4\xaf" => 'i',    # latin small letter i with ogonek
    "\xc4\xb0" => 'I',    # latin capital letter with dot above
    "\xc4\xb1" => 'i',    # latin small letter dotless i
    "\xc4\xb2" => 'IJ',   # latin capital ligature ij
    "\xc4\xb3" => 'ij',   # latin small ligature ij
    "\xc4\xb4" => 'J',    # latin capital letter j with circumflex
    "\xc4\xb5" => 'j',    # latin small letter j with circumflex
    "\xc4\xb6" => 'K',    # latin capital letter k with cedilla
    "\xc4\xb7" => 'k',    # latin small letter k with cedilla
    "\xc4\xb8" => 'k',    # latin small letter kra
    "\xc5\x81" => 'L',    # latin capital letter l with stroke
    "\xc5\x82" => 'l',    # latin small letter l with stroke
    "\xc4\xbd" => 'L',    # latin capital letter l with caron
    "\xc4\xbe" => 'l',    # latin small letter l with caron
    "\xc4\xb9" => 'L',    # latin capital letter l with acute
    "\xc4\xba" => 'l',    # latin small letter l with acute
    "\xc4\xbb" => 'L',    # latin capital letter l with cedilla
    "\xc4\xbc" => 'l',    # latin small letter l with cedilla
    "\xc4\xbf" => 'l',    # latin capital letter l with middle dot
    "\xc5\x80" => 'l',    # latin small letter l with middle dot
    "\xc3\x92" => 'O',    # O`
    "\xc3\xb2" => 'o',    # o`
    "\xc3\x93" => 'O',    # O'
    "\xc3\xb3" => 'o',    # o'
    "\xc3\x94" => 'O',    # O^
    "\xc3\xb4" => 'o',    # o^
    "\xc3\x96" => 'O',    # O:
    "\xc3\xb6" => 'o',    # o:
    "\xc3\x95" => 'O',    # O~
    "\xc3\xb5" => 'o',    # o~
    "\xc3\x98" => 'O',    # O/
    "\xc3\xb8" => 'o',    # o/
    "\xc5\x8c" => 'O',    # latin capital letter o with macron
    "\xc5\x8d" => 'o',    # latin small letter o with macron
    "\xc5\x90" => 'O',    # latin capital letter o with double acute
    "\xc5\x91" => 'o',    # latin small letter o with double acute
    "\xc5\x8e" => 'O',    # latin capital letter o with breve
    "\xc5\x8f" => 'o',    # latin small letter o with breve
    "\xc5\x92" => 'OE',   # latin capital ligature oe
    "\xc5\x93" => 'oe',   # latin small ligature oe
    "\xc5\x94" => 'R',    # latin capital letter r with acute
    "\xc5\x95" => 'r',    # latin small letter r with acute
    "\xc5\x98" => 'R',    # latin capital letter r with caron
    "\xc5\x99" => 'r',    # latin small letter r with caron
    "\xc5\x96" => 'R',    # latin capital letter r with cedilla
    "\xc5\x97" => 'r',    # latin small letter r with cedilla
    "\xc3\x99" => 'U',    # U`
    "\xc3\xb9" => 'u',    # u`
    "\xc3\x9a" => 'U',    # U'
    "\xc3\xba" => 'u',    # u'
    "\xc3\x9b" => 'U',    # U^
    "\xc3\xbb" => 'u',    # u^
    "\xc3\x9c" => 'U',    # U:
    "\xc3\xbc" => 'u',    # u:
    "\xc5\xaa" => 'U',    # latin capital letter u with macron
    "\xc5\xab" => 'u',    # latin small letter u with macron
    "\xc5\xae" => 'U',    # latin capital letter u with ring above
    "\xc5\xaf" => 'u',    # latin small letter u with ring above
    "\xc5\xb0" => 'U',    # latin capital letter u with double acute
    "\xc5\xb1" => 'u',    # latin small letter u with double acute
    "\xc5\xac" => 'U',    # latin capital letter u with breve
    "\xc5\xad" => 'u',    # latin small letter u with breve
    "\xc5\xa8" => 'U',    # latin capital letter u with tilde
    "\xc5\xa9" => 'u',    # latin small letter u with tilde
    "\xc5\xb2" => 'U',    # latin capital letter u with ogonek
    "\xc5\xb3" => 'u',    # latin small letter u with ogonek
    "\xc3\x87" => 'C',    # ,C
    "\xc3\xa7" => 'c',    # ,c
    "\xc4\x86" => 'C',    # latin capital letter c with acute
    "\xc4\x87" => 'c',    # latin small letter c with acute
    "\xc4\x8c" => 'C',    # latin capital letter c with caron
    "\xc4\x8d" => 'c',    # latin small letter c with caron
    "\xc4\x88" => 'C',    # latin capital letter c with circumflex
    "\xc4\x89" => 'c',    # latin small letter c with circumflex
    "\xc4\x8a" => 'C',    # latin capital letter c with dot above
    "\xc4\x8b" => 'c',    # latin small letter c with dot above
    "\xc4\x8e" => 'D',    # latin capital letter d with caron
    "\xc4\x8f" => 'd',    # latin small letter d with caron
    "\xc4\x90" => 'D',    # latin capital letter d with stroke
    "\xc4\x91" => 'd',    # latin small letter d with stroke
    "\xc3\x91" => 'N',    # N~
    "\xc3\xb1" => 'n',    # n~
    "\xc5\x83" => 'N',    # latin capital letter n with acute
    "\xc5\x84" => 'n',    # latin small letter n with acute
    "\xc5\x87" => 'N',    # latin capital letter n with caron
    "\xc5\x88" => 'n',    # latin small letter n with caron
    "\xc5\x85" => 'N',    # latin capital letter n with cedilla
    "\xc5\x86" => 'n',    # latin small letter n with cedilla
    "\xc5\x89" => 'n',    # latin small letter n preceded by apostrophe
    "\xc5\x8a" => 'N',    # latin capital letter eng
    "\xc5\x8b" => 'n',    # latin small letter eng
    "\xc3\x9f" => 'ss',   # double-s
    "\xc5\x9a" => 'S',    # latin capital letter s with acute
    "\xc5\x9b" => 's',    # latin small letter s with acute
    "\xc5\xa0" => 'S',    # latin capital letter s with caron
    "\xc5\xa1" => 's',    # latin small letter s with caron
    "\xc5\x9e" => 'S',    # latin capital letter s with cedilla
    "\xc5\x9f" => 's',    # latin small letter s with cedilla
    "\xc5\x9c" => 'S',    # latin capital letter s with circumflex
    "\xc5\x9d" => 's',    # latin small letter s with circumflex
    "\xc8\x98" => 'S',    # latin capital letter s with comma below
    "\xc8\x99" => 's',    # latin small letter s with comma below
    "\xc5\xa4" => 'T',    # latin capital letter t with caron
    "\xc5\xa5" => 't',    # latin small letter t with caron
    "\xc5\xa2" => 'T',    # latin capital letter t with cedilla
    "\xc5\xa3" => 't',    # latin small letter t with cedilla
    "\xc5\xa6" => 'T',    # latin capital letter t with stroke
    "\xc5\xa7" => 't',    # latin small letter t with stroke
    "\xc8\x9a" => 'T',    # latin capital letter t with comma below
    "\xc8\x9b" => 't',    # latin small letter t with comma below
    "\xc6\x92" => 'f',    # latin small letter f with hook
    "\xc4\x9c" => 'G',    # latin capital letter g with circumflex
    "\xc4\x9d" => 'g',    # latin small letter g with circumflex
    "\xc4\x9e" => 'G',    # latin capital letter g with breve
    "\xc4\x9f" => 'g',    # latin small letter g with breve
    "\xc4\xa0" => 'G',    # latin capital letter g with dot above
    "\xc4\xa1" => 'g',    # latin small letter g with dot above
    "\xc4\xa2" => 'G',    # latin capital letter g with cedilla
    "\xc4\xa3" => 'g',    # latin small letter g with cedilla
    "\xc4\xa4" => 'H',    # latin capital letter h with circumflex
    "\xc4\xa5" => 'h',    # latin small letter h with circumflex
    "\xc4\xa6" => 'H',    # latin capital letter h with stroke
    "\xc4\xa7" => 'h',    # latin small letter h with stroke
    "\xc5\xb4" => 'W',    # latin capital letter w with circumflex
    "\xc5\xb5" => 'w',    # latin small letter w with circumflex
    "\xc3\x9d" => 'Y',    # latin capital letter y with acute
    "\xc3\xbd" => 'y',    # latin small letter y with acute
    "\xc5\xb8" => 'Y',    # latin capital letter y with diaeresis
    "\xc3\xbf" => 'y',    # latin small letter y with diaeresis
    "\xc5\xb6" => 'Y',    # latin capital letter y with circumflex
    "\xc5\xb7" => 'y',    # latin small letter y with circumflex
    "\xc5\xbd" => 'Z',    # latin capital letter z with caron
    "\xc5\xbe" => 'z',    # latin small letter z with caron
    "\xc5\xbb" => 'Z',    # latin capital letter z with dot above
    "\xc5\xbc" => 'z',    # latin small letter z with dot above
    "\xc5\xb9" => 'Z',    # latin capital letter z with acute
    "\xc5\xba" => 'z',    # latin small letter z with acute
);
function xliterate_utf8($s) {
    global $Utf8_ASCII;
    return strtr($s, $Utf8_ASCII);
}

function iso_dirify($s, $sep = '_') {
    if ($sep == '1') $sep = '_';
    $s = convert_high_ascii($s);  ## convert high-ASCII chars to 7bit.
    $s = strtolower($s);                   ## lower-case.
    $s = strip_tags($s);          ## remove HTML tags.
    $s = preg_replace('!&[^;\s]+;!', '', $s); ## remove HTML entities.
    $s = preg_replace('![^\w\s]!', '', $s);   ## remove non-word/space chars.
    $s = preg_replace('/\s+/',$sep,$s);         ## change space chars to underscores.
    return($s);
}

global $Latin1_ASCII;
$Latin1_ASCII = array(
    "\xc0" => 'A',    # A`
    "\xe0" => 'a',    # a`
    "\xc1" => 'A',    # A'
    "\xe1" => 'a',    # a'
    "\xc2" => 'A',    # A^
    "\xe2" => 'a',    # a^
    "\xc4" => 'A',    # A:
    "\xe4" => 'a',    # a:
    "\xc5" => 'A',    # Aring
    "\xe5" => 'a',    # aring
    "\xc6" => 'AE',   # AE
    "\xe6" => 'ae',   # ae
    "\xc3" => 'A',    # A~
    "\xe3" => 'a',    # a~
    "\xc8" => 'E',    # E`
    "\xe8" => 'e',    # e`
    "\xc9" => 'E',    # E'
    "\xe9" => 'e',    # e'
    "\xca" => 'E',    # E^
    "\xea" => 'e',    # e^
    "\xcb" => 'E',    # E:
    "\xeb" => 'e',    # e:
    "\xcc" => 'I',    # I`
    "\xec" => 'i',    # i`
    "\xcd" => 'I',    # I'
    "\xed" => 'i',    # i'
    "\xce" => 'I',    # I^
    "\xee" => 'i',    # i^
    "\xcf" => 'I',    # I:
    "\xef" => 'i',    # i:
    "\xd2" => 'O',    # O`
    "\xf2" => 'o',    # o`
    "\xd3" => 'O',    # O'
    "\xf3" => 'o',    # o'
    "\xd4" => 'O',    # O^
    "\xf4" => 'o',    # o^
    "\xd6" => 'O',    # O:
    "\xf6" => 'o',    # o:
    "\xd5" => 'O',    # O~
    "\xf5" => 'o',    # o~
    "\xd8" => 'O',    # O/
    "\xf8" => 'o',    # o/
    "\xd9" => 'U',    # U`
    "\xf9" => 'u',    # u`
    "\xda" => 'U',    # U'
    "\xfa" => 'u',    # u'
    "\xdb" => 'U',    # U^
    "\xfb" => 'u',    # u^
    "\xdc" => 'U',    # U:
    "\xfc" => 'u',    # u:
    "\xc7" => 'C',    # ,C
    "\xe7" => 'c',    # ,c
    "\xd1" => 'N',    # N~
    "\xf1" => 'n',    # n~
    "\xdd" => 'Y',    # Yacute
    "\xfd" => 'y',    # yacute
    "\xdf" => 'ss',   # szlig
    "\xff" => 'y'     # yuml
);
function convert_high_ascii($s) {
    global $Latin1_ASCII;
    return strtr($s, $Latin1_ASCII);
}

global $Languages;
$Languages = array(
    'en' => array(
            array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'),
            array('January','February','March','April','May','June',
                  'July','August','September','October','November','December'),
            array('AM','PM'),
          ),

    'fr' => array(
            array('dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi' ),
            array('janvier', "f&#xe9;vrier", 'mars', 'avril', 'mai', 'juin',
               'juillet', "ao&#xfb;t", 'septembre', 'octobre', 'novembre',
               "d&#xe9;cembre"),
            array('AM','PM'),
          ),

    'es' => array(
            array('Domingo', 'Lunes', 'Martes', "Mi&#xe9;rcoles", 'Jueves',
               'Viernes', "S&#xe1;bado"),
            array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto',
                  'Septiembre','Octubre','Noviembre','Diciembre'),
            array('AM','PM'),
          ),

    'pt' => array(
            array('domingo', 'segunda-feira', "ter&#xe7;a-feira", 'quarta-feira',
               'quinta-feira', 'sexta-feira', "s&#xe1;bado"),
            array('janeiro', 'fevereiro', "mar&#xe7;o", 'abril', 'maio', 'junho',
               'julho', 'agosto', 'setembro', 'outubro', 'novembro',
               'dezembro' ),
            array('AM','PM'),
          ),

    'nl' => array(
            array('zondag','maandag','dinsdag','woensdag','donderdag','vrijdag',
                  'zaterdag'),
            array('januari','februari','maart','april','mei','juni','juli','augustus',
                  'september','oktober','november','december'),
            array('am','pm'),
             "%d %B %Y %H:%M",
             "%d %B %Y"
          ),

    'dk' => array(
            array("s&#xf8;ndag", 'mandag', 'tirsdag', 'onsdag', 'torsdag',
               'fredag', "l&#xf8;rdag"),
            array('januar','februar','marts','april','maj','juni','juli','august',
                  'september','oktober','november','december'),
            array('am','pm'),
            "%d.%m.%Y %H:%M",
            "%d.%m.%Y",
            "%H:%M",
          ),

    'se' => array(
            array("s&#xf6;ndag", "m&#xe5;ndag", 'tisdag', 'onsdag', 'torsdag',
               'fredag', "l&#xf6;rdag"),
            array('januari','februari','mars','april','maj','juni','juli','augusti',
                  'september','oktober','november','december'),
            array('FM','EM'),
          ),

    'no' => array(
            array("S&#xf8;ndag", "Mandag", 'Tirsdag', 'Onsdag', 'Torsdag',
               'Fredag', "L&#xf8;rdag"),
            array('Januar','Februar','Mars','April','Mai','Juni','Juli','August',
                  'September','Oktober','November','Desember'),
            array('FM','EM'),
          ),

    'de' => array(
            array('Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag',
                  'Samstag'),
            array('Januar', 'Februar', "M&#xe4;rz", 'April', 'Mai', 'Juni',
               'Juli', 'August', 'September', 'Oktober', 'November',
               'Dezember'),
            array('FM','EM'),
            "%d.%m.%y %H:%M",
            "%d.%m.%y",
            "%H:%M",
          ),

    'it' => array(
            array('Domenica', "Luned&#xec;", "Marted&#xec;", "Mercoled&#xec;",
               "Gioved&#xec;", "Venerd&#xec;", 'Sabato'),
            array('Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio',
                  'Agosto','Settembre','Ottobre','Novembre','Dicembre'),
            array('AM','PM'),
            "%d.%m.%y %H:%M",
            "%d.%m.%y",
            "%H:%M",
          ),

    'pl' => array(
            array('niedziela', "poniedzia&#322;ek", 'wtorek', "&#347;roda",
               'czwartek', "pi&#261;tek", 'sobota'),
            array('stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca',
               'lipca', 'sierpnia', "wrze&#347;nia", "pa&#378;dziernika",
               'listopada', 'grudnia'),
            array('AM','PM'),
            "%e %B %Y %k:%M",
            "%e %B %Y",
            "%k:%M",
          ),
            
    'fi' => array(
            array('sunnuntai','maanantai','tiistai','keskiviikko','torstai','perjantai',
                  'lauantai'),
            array('tammikuu', 'helmikuu', 'maaliskuu', 'huhtikuu', 'toukokuu',
               "kes&#xe4;kuu", "hein&#xe4;kuu", 'elokuu', 'syyskuu', 'lokakuu',
               'marraskuu', 'joulukuu'),
            array('AM','PM'),
            "%d.%m.%y %H:%M",
          ),
            
    'is' => array(
            array('Sunnudagur', "M&#xe1;nudagur", "&#xde;ri&#xf0;judagur",
               "Mi&#xf0;vikudagur", 'Fimmtudagur', "F&#xf6;studagur",
               'Laugardagur'),
            array("jan&#xfa;ar", "febr&#xfa;ar", 'mars', "apr&#xed;l", "ma&#xed;",
               "j&#xfa;n&#xed;", "j&#xfa;l&#xed;", "&#xe1;g&#xfa;st", 'september',             
               "okt&#xf3;ber", "n&#xf3;vember", 'desember'),
            array('FH','EH'),
            "%d.%m.%y %H:%M",
          ),
            
    'si' => array(
            array('nedelja', 'ponedeljek', 'torek', 'sreda', "&#xe3;etrtek",
               'petek', 'sobota'),
            array('januar','februar','marec','april','maj','junij','julij','avgust',
                  'september','oktober','november','december'),
            array('AM','PM'),
            "%d.%m.%y %H:%M",
          ),
            
    'cz' => array(
            array('Ned&#283;le', 'Pond&#283;l&#237;', '&#218;ter&#253;',
               'St&#345;eda', '&#268;tvrtek', 'P&#225;tek', 'Sobota'),
            array('Leden', '&#218;nor', 'B&#345;ezen', 'Duben', 'Kv&#283;ten',
               '&#268;erven', '&#268;ervenec', 'Srpen', 'Z&#225;&#345;&#237;',
               '&#216;&#237;jen', 'Listopad', 'Prosinec'),
            array('AM','PM'),
            "%e. %B %Y %k:%M",
            "%e. %B %Y",
            "%k:%M",
          ),
            
    'sk' => array(
            array('nede&#318;a', 'pondelok', 'utorok', 'streda',
               '&#353;tvrtok', 'piatok', 'sobota'),
            array('janu&#225;r', 'febru&#225;r', 'marec', 'apr&#237;l',
               'm&#225;j', 'j&#250;n', 'j&#250;l', 'august', 'september',
               'okt&#243;ber', 'november', 'december'),
            array('AM','PM'),
            "%e. %B %Y %k:%M",
            "%e. %B %Y",
            "%k:%M",
          ),

    'jp' => array(
            array('&#26085;&#26332;&#26085;', '&#26376;&#26332;&#26085;',
              '&#28779;&#26332;&#26085;', '&#27700;&#26332;&#26085;',
              '&#26408;&#26332;&#26085;', '&#37329;&#26332;&#26085;',
              '&#22303;&#26332;&#26085;'),
            array('1','2','3','4','5','6','7','8','9','10','11','12'),
            array('AM','PM'),
            "%Y&#24180;%b&#26376;%e&#26085; %H:%M",
            "%Y&#24180;%b&#26376;%e&#26085;",
            "%H:%M",
            "%Y&#24180;%b&#26376;",
            "%b&#26376;%e&#26085;",
          ),

    'ja' => array(
            array('&#26085;&#26332;&#26085;', '&#26376;&#26332;&#26085;',
              '&#28779;&#26332;&#26085;', '&#27700;&#26332;&#26085;',
              '&#26408;&#26332;&#26085;', '&#37329;&#26332;&#26085;',
              '&#22303;&#26332;&#26085;'),
            array('1','2','3','4','5','6','7','8','9','10','11','12'),
            array('AM','PM'),
            "%Y&#24180;%b&#26376;%e&#26085; %H:%M",
            "%Y&#24180;%b&#26376;%e&#26085;",
            "%H:%M",
            "%Y&#24180;%b&#26376;",
            "%b&#26376;%e&#26085;",
          ),

    'et' => array(
            array('ip&uuml;hap&auml;ev','esmasp&auml;ev','teisip&auml;ev',
                  'kolmap&auml;ev','neljap&auml;ev','reede','laup&auml;ev'),
            array('jaanuar', 'veebruar', 'm&auml;rts', 'aprill', 'mai',
               'juuni', 'juuli', 'august', 'september', 'oktoober',
              'november', 'detsember'),
            array('AM','PM'),
            "%m.%d.%y %H:%M",
            "%e. %B %Y",
            "%H:%M",
          ),
);


function first_n_words($text, $n) {
    $text = strip_tags($text);
    $words = preg_split('/\s+/', $text);
    $max = count($words) > $n ? $n : count($words);
    return join(' ', array_slice($words, 0, $max));
}


?>
