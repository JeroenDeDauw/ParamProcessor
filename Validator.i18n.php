<?php

/**
 * Internationalization file for the Validator extension
 *
 * @file Validator.i18n.php
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
*/

$messages = array();

/** English
 * @author Jeroen De Dauw
 */
$messages['en'] = array(
	'validator_name' => 'Validator',
	'validator-desc' => 'Validator provides an easy way for other extensions to validate parameters of parser functions and tag extensions, set default values and generate error messages',

	'validator_error_parameters' => 'The following {{PLURAL:$1|error has|errors have}} been detected in your syntax',
	'validator_warning_parameters' => 'There {{PLURAL:$1|is an error|are errors}} in your syntax.',

	// General errors
	'validator_error_unknown_argument' => '$1 is not a valid parameter.',
	'validator_error_required_missing' => 'The required parameter $1 is not provided.',

	// Criteria errors for single values
	'validator_error_empty_argument' => 'Parameter $1 can not have an empty value.',
	'validator_error_must_be_number' => 'Parameter $1 can only be a number.',
	'validator_error_must_be_integer' => 'Parameter $1 can only be an integer.',
	'validator_error_invalid_range' => 'Parameter $1 must be between $2 and $3.',
	'validator_error_invalid_argument' => 'The value $1 is not valid for parameter $2.',

	// Criteria errors for lists
	'validator_list_error_empty_argument' => 'Parameter $1 does not accept empty values.',
	'validator_list_error_must_be_number' => 'Parameter $1 can only contain numbers.',
	'validator_list_error_must_be_integer' => 'Parameter $1 can only contain integers.',
	'validator_list_error_invalid_range' => 'All values of parameter $1 must be between $2 and $3.',
	'validator_list_error_invalid_argument' => 'One or more values for parameter $1 are invalid.',	

	'validator_list_omitted' => 'The {{PLURAL:$2|value|values}} $1 {{PLURAL:$2|has|have}} been omitted.',

	// Crtiteria errors for single values & lists
	'validator_error_accepts_only' => 'Parameter $1 only accepts {{PLURAL:$3|this value|these values}}: $2.',
);

/** Message documentation (Message documentation)
 * @author Fryed-peach
 * @author Purodha
 */
$messages['qqq'] = array(
	'validator-desc' => '{{desc}}',
	'validator_error_parameters' => 'Parameters:
* $1 is the number of syntax errors, for PLURAL support (optional)',
);

/** Afrikaans (Afrikaans)
 * @author Naudefj
 */
$messages['af'] = array(
	'validator_name' => 'Valideerder',
	'validator-desc' => 'Die valideerder gee ander uitbreidings die vermoë om parameters van ontlederfunksies en etiket-uitbreidings te valideer, op hulle verstekwaardes in te stel en om foutboodskappe te genereer',
	'validator_error_parameters' => 'Die volgende {{PLURAL:$1|fout|foute}} is in u sintaks waargeneem',
	'validator_error_unknown_argument' => "$1 is nie 'n geldige parameter nie.",
	'validator_error_invalid_argument' => 'Die waarde $1 is nie geldig vir parameter $2 nie.',
	'validator_error_empty_argument' => 'Die parameter $1 mag nie leeg wees nie.',
	'validator_error_required_missing' => 'Die verpligte parameter $1 is nie verskaf nie.',
	'validator_error_must_be_number' => "Die parameter $1 mag net 'n getal wees.",
	'validator_error_must_be_integer' => "Die parameter $1 kan slegs 'n heelgetal wees.",
	'validator_error_invalid_range' => 'Die parameter $1 moet tussen $2 en $3 lê.',
	'validator_error_accepts_only' => 'Die parameter $1 kan slegs die volgende {{PLURAL:$3|waarde|waardes}} hê: $2.',
);

/** Arabic (العربية)
 * @author Meno25
 */
$messages['ar'] = array(
	'validator_name' => 'محقق',
	'validator-desc' => 'المحقق يوفر طريقة سهلة للامتدادات الأخرى للتحقق من محددات دوال المحلل وامتدادات الوسوم، وضبط القيم الافتراضية وتوليد رسائل الخطأ',
	'validator_error_parameters' => '{{PLURAL:$1|الخطأ التالي|الاخطاء التالية}} تم كشفها في صياغتك',
	'validator_error_unknown_argument' => '$1 ليس محددا صحيحا.',
	'validator_error_invalid_argument' => 'القيمة $1 ليست صحيحة للمحدد $2.',
	'validator_error_empty_argument' => 'المحدد $1 لا يمكن أن تكون قيمته فارغة.',
	'validator_error_required_missing' => 'المحدد المطلوب $1 ليس متوفرا.',
	'validator_error_must_be_number' => 'المحدد $1 يمكن أن يكون فقط عددا.',
	'validator_error_must_be_integer' => 'المحدد $1 يمكن أن يكون عددا صحيحا فقط.',
	'validator_error_invalid_range' => 'المحدد $1 يجب أن يكون بين $2 و $3.',
	'validator_error_accepts_only' => 'المحدد $1 يقبل فقط {{PLURAL:$3|هذه القيمة|هذه القيم}}: $2.',
);

/** Belarusian (Taraškievica orthography) (Беларуская (тарашкевіца))
 * @author EugeneZelenko
 * @author Jim-by
 */
$messages['be-tarask'] = array(
	'validator_name' => 'Правяраючы',
	'validator-desc' => 'Правяраючы палягчае іншым пашырэньням працу па праверцы парамэтраў функцыяў парсэру і тэгаў пашырэньняў, устанаўлівае значэньні па змоўчваньні і стварае паведамленьні пра памылкі',
	'validator_error_parameters' => 'У сынтаксісе {{PLURAL:$1|выяўленая наступная памылка|выяўленыя наступныя памылкі}}',
	'validator_error_unknown_argument' => 'Няслушны парамэтар $1.',
	'validator_error_required_missing' => 'Не пададзены абавязковы парамэтар $1.',
	'validator_error_empty_argument' => 'Парамэтар $1 ня можа мець пустое значэньне.',
	'validator_error_must_be_number' => 'Парамэтар $1 можа быць толькі лікам.',
	'validator_error_must_be_integer' => 'Парамэтар $1 можа быць толькі цэлым лікам.',
	'validator_error_invalid_range' => 'Парамэтар $1 павінен быць паміж $2 і $3.',
	'validator_error_invalid_argument' => 'Значэньне $1 не зьяўляецца слушным для парамэтру $2.',
	'validator_list_error_empty_argument' => 'Парамэтар $1 ня можа прымаць пустыя значэньні.',
	'validator_list_error_must_be_number' => 'Парамэтар $1 можа ўтрымліваць толькі лікі.',
	'validator_list_error_must_be_integer' => 'Парамэтар $1 можа ўтрымліваць толькі цэлыя лікі.',
	'validator_list_error_invalid_range' => 'Усе значэньні парамэтру $1 павінны знаходзіцца паміж $2 і $3.',
	'validator_list_error_invalid_argument' => 'Адно ці болей значэньняў парамэтру $1 зьяўляюцца няслушнымі.',
	'validator_list_omitted' => '{{PLURAL:$2|Значэньне|Значэньні}} $1 {{PLURAL:$2|было прапушчанае|былі прапушчаныя}}.',
	'validator_error_accepts_only' => 'Парамэтар $1 можа мець толькі {{PLURAL:$3|гэтае значэньне|гэтыя значэньні}}: $2.',
);

/** Breton (Brezhoneg)
 * @author Fohanno
 * @author Fulup
 * @author Y-M D
 */
$messages['br'] = array(
	'validator_name' => 'Kadarnataer',
	'validator_error_parameters' => "Kavet eo bet ar {{PLURAL:$1|fazi|fazioù}} da-heul en hoc'h ereadur",
	'validator_error_unknown_argument' => "$1 n'eo ket un arventenn reizh.",
	'validator_error_invalid_argument' => "N'eo ket reizh an dalvoudenn $1 evit an arventenn $2.",
	'validator_error_empty_argument' => "N'hall ket an arventenn $1 bezañ goullo he zalvoudenn",
	'validator_error_required_missing' => "N'eo ket bet pourchaset an arventenn rekis $1",
	'validator_error_must_be_number' => 'Un niver e rank an arventenn $1 bezañ hepken.',
	'validator_error_must_be_integer' => 'Rankout a ra an arventenn $1 bezañ un niver anterin.',
	'validator_error_invalid_range' => 'Rankout a ra an arventenn $1 bezañ etre $2 hag $3.',
);

/** Bosnian (Bosanski)
 * @author CERminator
 */
$messages['bs'] = array(
	'validator_name' => 'Validator',
	'validator-desc' => 'Validator pruža jednostavni način za druga proširenja u svrhu validacije parametara parserskih funkcija i proširenja oznaka, postavlja pretpostavljene vrijednosti i generira poruke pogrešaka.',
	'validator_error_parameters' => 'U Vašoj sintaksi {{PLURAL:$1|je|su}} {{PLURAL:$1|otkivena slijedeća greška|otkrivene slijedeće greške}}',
	'validator_error_unknown_argument' => '$1 nije valjan parametar.',
	'validator_error_invalid_argument' => 'Vrijednost $1 nije valjana za parametar $2.',
	'validator_error_empty_argument' => 'Parametar $1 ne može imati praznu vrijednost.',
	'validator_error_required_missing' => 'Obavezni parametar $1 nije naveden.',
	'validator_error_must_be_number' => 'Parametar $1 može biti samo broj.',
	'validator_error_must_be_integer' => 'Parametar $1 može biti samo cijeli broj.',
	'validator_error_invalid_range' => 'Parametar $1 mora biti između $2 i $3.',
	'validator_error_accepts_only' => 'Parametar $1 se može koristiti samo sa {{PLURAL:$3|ovom vrijednosti|ovim vrijednostima}}: $2.',
);

/** Lower Sorbian (Dolnoserbski)
 * @author Michawiki
 */
$messages['dsb'] = array(
	'validator_name' => 'Validator',
	'validator-desc' => 'Validator stoj lažki nałog za druge rozšyrjenja k dispoziciji, aby se pśekontrolěrowali parametry parserowych funkcijow a toflickich rozšyrjenjow, nastajili standardne gódnoty a napórali zmólkowe powěsći',
	'validator_error_parameters' => '{{PLURAL:$1|Slědujuca zmólka jo se namakała|Slědujucej zmólce stej se namakałej|Slědujuce zmólki su se namakali|Slědujuce zmólki su se namakali}} w twójej syntaksy:',
	'validator_error_unknown_argument' => '$1 njejo płaśiwy parameter.',
	'validator_error_invalid_argument' => 'Gódnota $1 njejo płaśiwa za parameter $2.',
	'validator_error_empty_argument' => 'Parameter $1 njamóžo proznu gódnotu měś.',
	'validator_error_required_missing' => 'Trěbny parameter $1 njejo pódany.',
	'validator_error_must_be_number' => 'Parameter $1 móžo jano licba byś.',
	'validator_error_must_be_integer' => 'Parameter $1 móžo jano ceła licba byś.',
	'validator_error_invalid_range' => 'Parameter $1 musy mjazy $2 a $3 byś.',
	'validator_error_accepts_only' => 'Parameter $1 akceptěrujo jano {{PLURAL:$3|toś tu gódnotu|toś tej gódnośe|toś te gódnoty|toś te gódnoty}}: $2.',
);

/** Greek (Ελληνικά)
 * @author ZaDiak
 */
$messages['el'] = array(
	'validator_name' => 'Επικυρωτής',
	'validator_error_unknown_argument' => '$1 δεν είναι μια έγκυρη παράμετρος.',
);

/** Spanish (Español)
 * @author Crazymadlover
 * @author Translationista
 */
$messages['es'] = array(
	'validator_error_empty_argument' => 'El parámetro $1 no puede tener un valor vacío.',
	'validator_error_required_missing' => 'No se ha provisto el parámetro requerido $1.',

	'validator_error_must_be_number' => 'El parámetro $1 sólo puede ser un número.',
	'validator_error_invalid_range' => 'El parámetro $1 debe ser entre $2 y $3.',
);

/** French (Français)
 * @author Cedric31
 * @author Crochet.david
 * @author IAlex
 * @author Jean-Frédéric
 * @author McDutchie
 * @author Peter17
 * @author PieRRoMaN
 * @author Verdy p
 */
$messages['fr'] = array(
	'validator_name' => 'Validateur',
	'validator-desc' => "Le validateur fournit un moyen simple aux autres extensions de valider les paramètres des fonctions parseur et des extensions de balises, de définir des valeurs par défaut et de générer des messages d'erreur",
	'validator_error_parameters' => '{{PLURAL:$1|L’erreur suivante a été détectée|Les erreurs suivantes ont été détectées}} dans votre syntaxe',
	'validator_error_unknown_argument' => '$1 n’est pas un paramètre valide.',
	'validator_error_required_missing' => "Le paramètre requis $1 n'est pas fourni.",
	'validator_error_empty_argument' => 'Le paramètre $1 ne peut pas avoir une valeur vide.',
	'validator_error_must_be_number' => 'Le paramètre $1 peut être uniquement un nombre.',
	'validator_error_must_be_integer' => 'Le paramètre $1 peut seulement être un entier.',
	'validator_error_invalid_range' => 'Le paramètre $1 doit être entre $2 et $3.',
	'validator_error_invalid_argument' => "La valeur $1 n'est pas valide pour le paramètre $2.",
	'validator_list_error_empty_argument' => "Le paramètre $1 n'accepte pas les valeurs vides.",
	'validator_list_error_must_be_number' => 'Le paramètre $1 ne peut contenir que des nombres.',
	'validator_list_error_must_be_integer' => 'Le paramètre $1 ne peut contenir que des entiers.',
	'validator_list_error_invalid_range' => 'Toutes les valeurs du paramètre $1 doivent être comprises entre $2 et $3.',
	'validator_list_error_invalid_argument' => 'Une ou plusieurs valeurs du paramètre $1 sont invalides.',
	'validator_list_omitted' => '{{PLURAL:$2|La valeur|Les valeurs}} $1 {{PLURAL:$2|a été oubliée|ont été oubliées}}.',
	'validator_error_accepts_only' => 'Le paramètre $1 accepte uniquement {{PLURAL:$3|cette valeur|ces valeurs}} : $2.',
);

/** Galician (Galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'validator_name' => 'Servizo de validación',
	'validator-desc' => 'O servizo de validación ofrece un medio sinxelo a outras extensións para validar os parámetros de funcións analíticas e etiquetas de extensións, para establecer os valores por defecto e para xerar mensaxes de erro',
	'validator_error_parameters' => '{{PLURAL:$1|Detectouse o seguinte erro|Detectáronse os seguintes erros}} na sintaxe empregada',
	'validator_error_unknown_argument' => '"$1" non é un parámetro válido.',
	'validator_error_invalid_argument' => 'O valor $1 non é válido para o parámetro $2.',
	'validator_error_empty_argument' => 'O parámetro $1 non pode ter un valor baleiro.',
	'validator_error_required_missing' => 'Non se proporcionou o parámetro $1 necesario.',
	'validator_error_must_be_number' => 'O parámetro $1 só pode ser un número.',
	'validator_error_must_be_integer' => 'O parámetro $1 só pode ser un número enteiro.',
	'validator_error_invalid_range' => 'O parámetro $1 debe estar entre $2 e $3.',
	'validator_error_accepts_only' => 'O parámetro "$1" só acepta {{PLURAL:$3|este valor|estes valores}}: $2.',
);

/** Swiss German (Alemannisch)
 * @author Als-Holder
 */
$messages['gsw'] = array(
	'validator_name' => 'Validator',
	'validator-desc' => 'Validator stellt e eifachi Form z Verfiegig fir anderi Erwyterige go Parameter validiere vu Parser- un Tag-Funktione, go Standardwärt definiere un Fählermäldige generiere',
	'validator_error_parameters' => '{{PLURAL:$1|Dää Fähler isch|Die Fähler sin}} in Dyyre Syntax gfunde wore',
	'validator_error_unknown_argument' => '$1 isch kei giltige Parameter.',
	'validator_error_required_missing' => 'Dr Paramter $1, wu aagforderet woren isch, wird nit z Verfiegig gstellt.',
	'validator_error_empty_argument' => 'Dr Parameter $1 cha kei lääre Wärt haa.',
	'validator_error_must_be_number' => 'Dr Parameter $1 cha nume ne Zahl syy.',
	'validator_error_must_be_integer' => 'Parameter $1 cha nume ne giltigi Zahl syy.',
	'validator_error_invalid_range' => 'Dr Parameter $1 muess zwische $2 un $3 syy.',
	'validator_error_invalid_argument' => 'Dr Wärt $1 isch nit giltig fir dr Parameter $2.',
	'validator_list_error_empty_argument' => 'Bim Parameter $1 sin keini lääre Wärt zuegloo.',
	'validator_list_error_must_be_number' => 'Fir dr Parameter $1 si nume Zahle zuegloo.',
	'validator_list_error_must_be_integer' => 'Fir dr Parameter $1 sin nume ganzi Zahle zuegloo.',
	'validator_list_error_invalid_range' => 'Alli Wärt fir dr Parameter $1 mien zwische $2 un $3 lige.',
	'validator_list_error_invalid_argument' => 'Ein oder mehreri Wärt fir dr Parameter $1 sin nit giltig.',
	'validator_list_omitted' => '{{PLURAL:$2|Dr Wärt|D Wärt}} $1 {{PLURAL:$2|isch|sin}} uusgloo wore.',
	'validator_error_accepts_only' => 'Dr Parameter $1 cha nume {{PLURAL:$3|dää Wärt|die Wärt}} haa: $2.',
);

/** Upper Sorbian (Hornjoserbsce)
 * @author Michawiki
 */
$messages['hsb'] = array(
	'validator_name' => 'Validator',
	'validator-desc' => 'Validator skići lochke wašnje za druhe rozšěrjenja, zo bychu so parametry parserowych funkcijow a tafličkowych rozšěrjenjow přepruwowali, standardne hódnoty nastajili a zmylkowe powěsće wutworili',
	'validator_error_parameters' => '{{PLURAL:$1|Slědowacy zmylk bu|Slědowacej zmylkaj buštej|Slědowace zmylki buchu|Slědowace zmylki buchu}} w twojej syntaksy {{PLURAL:$1|wotkryty|wotkrytej|wotkryte|wotkryte}}:',
	'validator_error_unknown_argument' => '$1 płaćiwy parameter njeje.',
	'validator_error_invalid_argument' => 'Hódnota $1 njeje płaćiwa za parameter $2.',
	'validator_error_empty_argument' => 'Parameter $1 njemóže prózdnu hódnotu měć.',
	'validator_error_required_missing' => 'Trěbny parameter $1 njeje podaty.',
	'validator_error_must_be_number' => 'Parameter $1 móže jenož ličba być.',
	'validator_error_must_be_integer' => 'Parameter $1 móže jenož cyła ličba być.',
	'validator_error_invalid_range' => 'Parameter $1 dyrbi mjez $2 a $3 być.',
	'validator_error_accepts_only' => 'Parameter $1 akceptuje jenož {{PLURAL:$3|tutu hódnotu|tutej hódnoće|tute hódnoty|tute hódnoty}}: $2.',
);

/** Hungarian (Magyar)
 * @author Dani
 * @author Glanthor Reviol
 */
$messages['hu'] = array(
	'validator_name' => 'Érvényesség-ellenőrző',
	'validator-desc' => 'Az érvényesség-ellenőrző egyszerű lehetőséget nyújt más kiterjesztéseknek az elemzőfüggvények és tagek paramétereinek ellenőrzésére, alapértelmezett értékek beállítására, valamint hibaüzenetek generálására.',
	'validator_error_parameters' => 'A következő {{PLURAL:$1|hiba található|hibák találhatóak}} a szintaxisban: $1',
	'validator_error_unknown_argument' => 'A(z) $1 nem érvényes paraméter.',
	'validator_error_invalid_argument' => 'A(z) $1 érték nem érvényes a(z) $2 paraméterhez.',
	'validator_error_empty_argument' => 'A(z) $1 paraméter értéke nem lehet üres.',
	'validator_error_required_missing' => 'A(z) $1 kötelező paraméter nem lett megadva.',
	'validator_error_must_be_number' => 'A(z) $1 paraméter csak szám lehet.',
	'validator_error_invalid_range' => 'A(z) $1 paraméter értékének $2 és $3 között kell lennie.',
	'validator_error_accepts_only' => 'A(z) $1 paraméter csak a következő {{PLURAL:$3|értéket|értékeket}} fogadja el: $2',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'validator_name' => 'Validator',
	'validator-desc' => 'Validator provide un modo facile a altere extensiones de validar parametros de functiones del analysator syntactic e extensiones de etiquettas, predefinir valores e generar messages de error',
	'validator_error_parameters' => 'Le sequente {{PLURAL:$1|error|errores}} ha essite detegite in tu syntaxe',
	'validator_error_unknown_argument' => '$1 non es un parametro valide.',
	'validator_error_invalid_argument' => 'Le valor $1 non es valide pro le parametro $2.',
	'validator_error_empty_argument' => 'Le parametro $1 non pote haber un valor vacue.',
	'validator_error_required_missing' => 'Le parametro requisite $1 non ha essite fornite.',
	'validator_error_must_be_number' => 'Le parametro $1 pote solmente esser un numero.',
	'validator_error_must_be_integer' => 'Le parametro $1 pote solmente esser un numero integre.',
	'validator_error_invalid_range' => 'Le parametro $1 debe esser inter $2 e $3.',
	'validator_error_accepts_only' => 'Le parametro $1 accepta solmente iste {{PLURAL:$3|valor|valores}}: $2.',
);

/** Indonesian (Bahasa Indonesia)
 * @author Bennylin
 * @author Farras
 * @author Irwangatot
 * @author IvanLanin
 */
$messages['id'] = array(
	'validator_name' => 'Validator',
	'validator-desc' => 'Validator memberikan cara mudah untuk ekstensi lain untuk memvalidasi parameter ParserFunction dan ekstensi tag, mengatur nilai biasa dan membuat pesan kesalahan',
	'validator_error_parameters' => '{{PLURAL:$1|Kesalahan|Kesalahan}} berikut telah terdeteksi pada sintaksis Anda',
	'validator_error_unknown_argument' => '$1 bukan parameter yang benar.',
	'validator_error_invalid_argument' => 'Nilai $1 tidak valid untuk parameter $2.',
	'validator_error_empty_argument' => 'Parameter $1 tidak dapat bernilai kosong.',
	'validator_error_required_missing' => 'Parameter $1 yang diperlukan tidak diberikan.',
	'validator_error_must_be_number' => 'Parameter $1 hanya dapat berupa angka.',
	'validator_error_must_be_integer' => 'Parameter $1 hanya dapat berupa integer.',
	'validator_error_invalid_range' => 'Parameter $1 harus antara $2 dan $3.',
	'validator_error_accepts_only' => 'Parameter $1 hanya menerima {{PLURAL:$3|nilai ini|nilai ini}}: $2.',
);

/** Japanese (日本語)
 * @author Aotake
 * @author Fryed-peach
 */
$messages['ja'] = array(
	'validator_name' => '妥当性評価器',
	'validator-desc' => '妥当性評価器は他の拡張機能にパーサー関数やタグ拡張の引数の妥当性を確認したり、規定値を設定したり、エラーメッセージを生成する手段を提供する',
	'validator_error_parameters' => 'あなたの入力から以下の{{PLURAL:$1|エラー}}が検出されました',
	'validator_error_unknown_argument' => '$1 は有効な引数ではありません。',
	'validator_error_required_missing' => '必須の引数「$1」が入力されていません。',
	'validator_error_empty_argument' => '引数「$1」は空の値をとることができません。',
	'validator_error_must_be_number' => '引数「$1」は数値でなければなりません。',
	'validator_error_must_be_integer' => '引数「$1」は整数でなければなりません。',
	'validator_error_invalid_range' => '引数「$1」は $2 と $3 の間の値でなければなりません。',
	'validator_error_invalid_argument' => '値「$1」は引数「$2」として妥当ではありません。',
	'validator_list_error_empty_argument' => '引数「$1」は空の値をとりません。',
	'validator_list_error_must_be_number' => '引数「$1」は数値しかとることができません。',
	'validator_list_error_must_be_integer' => '引数「$1」は整数値しかとることができません。',
	'validator_list_error_invalid_range' => '引数「$1」の値はすべて $2 と $3 の間のものでなくてはなりません。',
	'validator_list_error_invalid_argument' => '引数「$1」の値に不正なものが1つ以上あります。',
	'validator_list_omitted' => '{{PLURAL:$2|値}} $1 は省かれました。',
	'validator_error_accepts_only' => '引数 $1 は次の{{PLURAL:$3|値}}以外を取ることはできません: $2',
);

/** Ripoarisch (Ripoarisch)
 * @author Purodha
 */
$messages['ksh'] = array(
	'validator_name' => 'Prööver',
	'validator-desc' => '{{int:validator_name}} brängk eine eijfache Wääsch, der Parrammeetere fun Paaser-Fungkßjohne un Zohsatzprojramme ze prööve, Schtandatt-Wääte enzefööje, un Fähler ze mällde.',
	'validator_error_parameters' => '{{PLURAL:$1|Heh dä|Heh di|Keine}} Fähler {{PLURAL:$1|es|sin|es}} en Dinge Syntax opjevalle:',
	'validator_error_unknown_argument' => '„$1“ es keine jöltijje Parameeter.',
	'validator_error_invalid_argument' => 'Däm Parameeter $2 singe Wäät es $1, dat es ävver doför nit jöltesch.',
	'validator_error_empty_argument' => 'Dä Parameeter $1 kann keine Wäät met nix dren hann.',
	'validator_error_required_missing' => 'Dä Parameeter $1 moß aanjejovve sin, un fählt.',
	'validator_error_must_be_number' => 'Dä Parameeter $1 kann blohß en Zahl sin.',
	'validator_error_must_be_integer' => 'Dä Parrameeter $1 kann bloß en jannze Zahl sin.',
	'validator_error_invalid_range' => 'Dä Parameeter $1 moß zwesche $2 un $3 sin.',
	'validator_error_accepts_only' => 'Dä Parameeter $1 kann {{PLURAL:$3|bloß dä eine Wäät|bloß eine vun dä Wääte|keine Wäät}} han: $2',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'validator_name' => 'Validator',
	'validator-desc' => 'Validator erlaabt et op eng einfach Manéier fir Parameter vu Parser-Fonctiounen an Tag-Erweiderungen ze validéieren, fir Standard-Werter festzeleeën a fir Feeler-Messagen ze generéieren',
	'validator_error_parameters' => '{{PLURAL:$1|Dëser Feeler gouf|Dës Feeler goufen}} an Ärer Syntax fonnt.',
	'validator_error_unknown_argument' => '$1 ass kee valbele Parameter.',
	'validator_error_invalid_argument' => 'De Wert $1 ass net valabel fir de Parameter $2.',
	'validator_error_empty_argument' => 'De Parameter $1 ka keen eidele Wert hunn.',
	'validator_error_required_missing' => 'Den obligatoresche Parameter $1 war net derbäi.',
	'validator_error_must_be_number' => 'De Parameter $1 ka just eng Zuel sinn',
	'validator_error_invalid_range' => 'De Parameter $1 muss tëschent $2 an $3 leien.',
	'validator_error_accepts_only' => 'De Parameter $1 akzeptéiert just {{PLURAL:$3|dëse Wert|dës Werter}}: $2',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 * @author McDutchie
 */
$messages['mk'] = array(
	'validator_name' => 'Потврдувач',
	'validator-desc' => 'Потврдувачот овозможува лесен начин другите проширувања да ги потврдат параметрите на парсерските функции и проширувањата со ознаки, да поставаат основно зададени вредности и да создаваат пораки за грешки',
	'validator_error_parameters' => 'Во вашата синтакса {{PLURAL:$1|е откриена следнава грешка|се откриени следниве грешки}}',
	'validator_error_unknown_argument' => '$1 не е важечки параметар.',
	'validator_error_invalid_argument' => 'Вредноста $1 е неважечка за параметарот $2.',
	'validator_error_empty_argument' => 'Параметарот $1 не може да има празна вредност.',
	'validator_error_required_missing' => 'Бараниот параметар $1 не е наведен.',
	'validator_error_must_be_number' => 'Параметарот $1 може да биде само број.',
	'validator_error_must_be_integer' => 'Параметарот $1 може да биде само цел број.',
	'validator_error_invalid_range' => 'Параметарот $1 мора да изнесува помеѓу $2 и $3.',
	'validator_error_accepts_only' => 'Параметарот $1 {{PLURAL:$3|ја прифаќа само оваа вредност|ги прифаќа само овие вредности}}: $2.',
);

/** Dutch (Nederlands)
 * @author Jeroen De Dauw
 * @author Siebrand
 */
$messages['nl'] = array(
	'validator_name' => 'Validator',
	'validator-desc' => 'Validator geeft andere uitbreidingen de mogelijkheid om parameters van parserfuncties en taguitbreidingen te valideren, in te stellen op hun standaardwaarden en foutberichten te genereren',
	'validator_error_parameters' => 'In uw syntaxis {{PLURAL:$1|is de volgende fout|zijn de volgende fouten}} gedetecteerd',
	'validator_error_unknown_argument' => '$1 is geen geldige parameter.',
	'validator_error_required_missing' => 'De verplichte parameter $1 is niet opgegeven.',
	'validator_error_empty_argument' => 'De parameter $1 mag niet leeg zijn.',
	'validator_error_must_be_number' => 'De parameter $1 mag alleen een getal zijn.',
	'validator_error_must_be_integer' => 'De parameter $1 kan alleen een heel getal zijn.',
	'validator_error_invalid_range' => 'De parameter $1 moet tussen $2 en $3 liggen.',
	'validator_error_invalid_argument' => 'De waarde $1 is niet geldig voor de parameter $2.',
	'validator_list_error_empty_argument' => 'Voor de parameter $1 zijn lege waarden niet toegestaan.',
	'validator_list_error_must_be_number' => 'Voor de parameter $1 zijn alleen getallen toegestaan.',
	'validator_list_error_must_be_integer' => 'Voor de parameter $1 zijn alleen hele getallen toegestaan.',
	'validator_list_error_invalid_range' => 'Alle waarden voor de parameter $1 moeten tussen $2 en $3 liggen.',
	'validator_list_error_invalid_argument' => 'Een of meerdere waarden voor de parameter $1 zijn ongeldig.',
	'validator_list_omitted' => 'De {{PLURAL:$2|waarde|waarden}} $1 {{PLURAL:$2|mist|missen}}.',
	'validator_error_accepts_only' => 'De parameter $1 kan alleen de volgende {{PLURAL:$3|waarde|waarden}} hebben: $2.',
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Nghtwlkr
 */
$messages['no'] = array(
	'validator_error_parameters' => 'Følgende {{PLURAL:$1|feil|feil}} har blitt oppdaget i syntaksen din',
	'validator_error_unknown_argument' => '$1 er ikke en gyldig parameter.',
	'validator_error_required_missing' => 'Den nødvendige parameteren $1 er ikke angitt.',
	'validator_error_empty_argument' => 'Parameteren $1 kan ikke ha en tom verdi.',
	'validator_error_must_be_number' => 'Parameter $1 må være et tall.',
	'validator_error_must_be_integer' => 'Parameteren $1 må være et heltall.',
	'validator_error_invalid_range' => 'Parameter $1 må være mellom $2 og $3.',
	'validator_error_invalid_argument' => 'Verdien $1 er ikke en gyldig parameter for $2.',
	'validator_error_accepts_only' => 'Parameteren $1 kan kun ha {{PLURAL:$3|denne verdien|disse verdiene}}: $2',
);

/** Occitan (Occitan)
 * @author Cedric31
 * @author Jfblanc
 */
$messages['oc'] = array(
	'validator_name' => 'Validaire',
	'validator-desc' => "Validator porgís a d'autras extensions un biais per validar aisidament los paramètres de foncions d'analisi e las extensions de mercas, definir de valors per manca e crear de messatges d'error",
	'validator_error_parameters' => '{{PLURAL:$1|Aquela error es estada detectada|Aquelas errors son estadas detectadas}} dins la sintaxi',
	'validator_error_unknown_argument' => '$1 es pas un paramètre valedor.',
	'validator_error_invalid_argument' => '$1 es pas valedor pel paramètre $2.',
	'validator_error_empty_argument' => 'Lo paramètre $1 pòt pas estar voide.',
	'validator_error_required_missing' => "Manca lo paramètre $1 qu'es obligatòri.",
	'validator_error_must_be_number' => 'Lo paramètre $1 deu èsser un nombre.',
	'validator_error_must_be_integer' => 'Lo paramètre $1 deu èsser un nombre entièr.',
	'validator_error_invalid_range' => 'Lo paramètre $1 deu èsser entre $2 e $3.',
	'validator_error_accepts_only' => 'Sonque {{PLURAL:$3|aquela valor es valedora|aquelas valors son valedoras}}pel paramètre $1 : $2.',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 * @author Dragonòt
 * @author McDutchie
 */
$messages['pms'] = array(
	'validator_name' => 'Validator',
	'validator-desc' => "Validator a dà na manera bel fé për àutre estension ëd validé ij paràmetr ëd le funsion dël parser e j'estension dij tag, d'amposté ij valor ëd default e generé mëssagi d'eror",
	'validator_error_parameters' => "{{PLURAL:$1|L'eror sota a l'é stàit|J'eror sota a son ëstàit}} trovà an toa sintassi",
	'validator_error_unknown_argument' => "$1 a l'é un paràmetr pa bon.",
	'validator_error_invalid_argument' => "Ël valor $1 a l'é pa bon për ël paràmetr $2.",
	'validator_error_empty_argument' => 'Ël paràmetr $1 a peul pa avèj un valor veuid.',
	'validator_error_required_missing' => "Ël paràmetr obligatòri $1 a l'é pa dàit.",
	'validator_error_must_be_number' => 'Ël paràmetr $1 a peul mach esse un nùmer.',
	'validator_error_must_be_integer' => "Ël paràmetr $1 a peul mach esse n'antregh.",
	'validator_error_invalid_range' => 'Ël paràmetr $1 a deuv esse an tra $2 e $3.',
	'validator_error_accepts_only' => 'Ël paràmetr $1 a aceta mach {{PLURAL:$3|sto valor-sì|sti valor-sì}}: $2.',
);

/** Portuguese (Português)
 * @author Hamilton Abreu
 */
$messages['pt'] = array(
	'validator_name' => 'Serviço de Validação',
	'validator-desc' => 'O Serviço de Validação permite que, de forma simples, as outras extensões possam validar parâmetros das funções do analisador sintáctico e das extensões dos elementos HTML, definir valores por omissão e gerar mensagens de erro',
	'validator_error_parameters' => '{{PLURAL:$1|Foi detectado o seguinte erro sintáctico|Foram detectados os seguintes erros sintácticos}}',
	'validator_error_unknown_argument' => '$1 não é um parâmetro válido.',
	'validator_error_required_missing' => 'O parâmetro obrigatório $1 não foi fornecido.',
	'validator_error_empty_argument' => 'O parâmetro $1 não pode estar vazio.',
	'validator_error_must_be_number' => 'O parâmetro $1 só pode ser numérico.',
	'validator_error_must_be_integer' => 'O parâmetro $1 só pode ser um número inteiro.',
	'validator_error_invalid_range' => 'O parâmetro $1 tem de ser entre $2 e $3.',
	'validator_error_invalid_argument' => 'O valor $1 não é válido para o parâmetro $2.',
	'validator_list_error_empty_argument' => 'O parâmetro $1 não pode estar vazio.',
	'validator_list_error_must_be_number' => 'O parâmetro $1 só pode ser numérico.',
	'validator_list_error_must_be_integer' => 'O parâmetro $1 só pode ser um número inteiro.',
	'validator_list_error_invalid_range' => 'Todos os valores do parâmetro $1 têm de ser entre $2 e $3.',
	'validator_list_error_invalid_argument' => 'Um ou mais valores do parâmetro $1 são inválidos.',
	'validator_list_omitted' => '{{PLURAL:$2|O valor $1 foi omitido|Os valores $1 foram omitidos}}.',
	'validator_error_accepts_only' => 'O parâmetro $1 só aceita {{PLURAL:$3|este valor|estes valores}}: $2.',
);

/** Russian (Русский)
 * @author Lockal
 * @author McDutchie
 * @author Александр Сигачёв
 */
$messages['ru'] = array(
	'validator_name' => 'Валидатор',
	'validator-desc' => 'Валидатор предоставляет другим расширениям возможности проверки параметров функций парсера и тегов, установки значения по умолчанию и создания сообщения об ошибках',
	'validator_error_parameters' => 'В вашем синтаксисе {{PLURAL:$1|обнаружена следующая ошибка|обнаружены следующие ошибки}}',
	'validator_error_unknown_argument' => '$1 не является допустимым параметром.',
	'validator_error_required_missing' => 'Не указан обязательный параметр $1.',
	'validator_error_empty_argument' => 'Параметр $1 не может принимать пустое значение.',
	'validator_error_must_be_number' => 'Значением параметра $1 могут быть только числа.',
	'validator_error_must_be_integer' => 'Параметр $1 может быть только целым числом.',
	'validator_error_invalid_range' => 'Параметр $1 должен быть от $2 до $3.',
	'validator_error_invalid_argument' => 'Значение $1 не является допустимым параметром $2',
	'validator_list_error_empty_argument' => 'Параметр $1 не может принимать пустые значения.',
	'validator_list_error_must_be_number' => 'Параметр $1 может содержать только цифры.',
	'validator_list_error_must_be_integer' => 'Параметр $ 1 может содержать только целые числа.',
	'validator_list_error_invalid_range' => 'Все значения параметра $1 должна находиться в диапазоне от $2 до $3.',
	'validator_list_error_invalid_argument' => 'Одно или несколько значений параметра $1 ошибочны.',
	'validator_list_omitted' => '{{PLURAL:$2|Значение $1 было пропущено|Значения $1 были пропущены}}.',
	'validator_error_accepts_only' => 'Параметр $1 может принимать только {{PLURAL:$3|следующее значение|следующие значения}}: $2.',
);

/** Sinhala (සිංහල)
 * @author Calcey
 */
$messages['si'] = array(
	'validator_name' => 'තහවුරු කරන්නා',
	'validator-desc' => 'තහවුරු කරන්නා ටැග් දිඟුවන් හා parser ශ්‍රිතවල පරාමිතීන් තහවුරු කිරීමට අනෙක් දිඟුවන් සඳහා පහසු ක්‍රමයක් සපයයි,පෙරනිමි අගයන් පිහිටුවීම හා දෝෂ පණිවුඩ ජනනය කිරීම ද සිදු කරයි',
	'validator_error_parameters' => 'ඔබේ වාග් රීතිය මඟින් පහත {{PLURAL:$1|දෝෂය|දෝෂයන්}} අනාවරණය කරනු ලැබ ඇත',
	'validator_error_unknown_argument' => '$1 වලංගු පරාමිතියක් නොවේ.',
	'validator_error_invalid_argument' => '$2 පරාමිතිය සඳහා $1 අගය වලංගු නොවේ.',
	'validator_error_empty_argument' => '$1 පරාමිතියට හිස් අගයක් තිබිය නොහැක.',
	'validator_error_required_missing' => 'අවශ්‍ය වන $1 පරාමිතිය සපයා නොමැත.',
	'validator_error_must_be_number' => '$1 පරාමිතිය විය හැක්කේ ඉලක්කමක් පමණි.',
	'validator_error_invalid_range' => '$1 පරාමිතිය $2 හා $3 අතර විය යුතුය.',
	'validator_error_accepts_only' => '$1 පරාමිතිය විසින් පිළිගනු ලබන්නේ {{PLURAL:$3|මෙම අගය|මෙම අගයන්}}: $2 පමණි.',
);

/** Swedish (Svenska)
 * @author Fluff
 * @author Per
 */
$messages['sv'] = array(
	'validator_error_parameters' => 'Följande fel har upptäckts i din syntax',
	'validator_error_unknown_argument' => '$1 är inte en giltig paramter.',
	'validator_error_invalid_argument' => 'Värdet $1 är inte giltigt som parameter $2.',
	'validator_error_empty_argument' => 'Parametern $1 kan inte lämnas tom.',
	'validator_error_required_missing' => 'Den nödvändiga parametern $1 har inte angivits.',
	'validator_error_must_be_number' => 'Parameter $1 måste bestå av ett tal.',
	'validator_error_must_be_integer' => 'Parametern $1 måste vara ett heltal.',
	'validator_error_invalid_range' => 'Parameter $1 måste vara i mellan $2 och $3.',
	'validator_error_accepts_only' => 'Parametern $1 måste ha {{PLURAL:$3|detta värde|ett av dessa värden}}: $2.',
);

/** Turkish (Türkçe)
 * @author Vito Genovese
 */
$messages['tr'] = array(
	'validator_name' => 'Doğrulayıcı',
	'validator_error_unknown_argument' => '$1, geçerli bir parametre değildir.',
	'validator_error_must_be_integer' => '$1 parametresi sadece bir tamsayı olabilir',
);

/** Ukrainian (Українська)
 * @author Prima klasy4na
 */
$messages['uk'] = array(
	'validator_name' => 'Валідатор',
	'validator-desc' => 'Валідатор забезпечує іншим розширенням можливості перевірки параметрів функцій парсеру і тегів, встановлення значень за замовчуванням та створення повідомлень про помилки',
	'validator_error_parameters' => 'У вашому синтаксисі {{PLURAL:$1|виявлена наступна помилка|виявлені наступні помилки}}',
);

/** Vietnamese (Tiếng Việt)
 * @author Minh Nguyen
 * @author Vinhtantran
 */
$messages['vi'] = array(
	'validator_name' => 'Bộ phê chuẩn',
	'validator-desc' => 'Bộ phê chuẩn cho phép các phần mở rộng khác phê chuẩn tham số của hàm cú pháp và thẻ mở rộng, đặt giá trị mặc định, và báo cáo lỗi.',
	'validator_error_parameters' => 'Cú pháp có {{PLURAL:$1|lỗi|các lỗi}} sau',
	'validator_error_unknown_argument' => '$1 không phải là tham số hợp lệ.',
	'validator_error_invalid_argument' => 'Giá trị “$1” không hợp tham số “$2”.',
	'validator_error_empty_argument' => 'Tham số “$1” không được để trống.',
	'validator_error_required_missing' => 'Không định rõ tham số bắt buộc “$1”.',
	'validator_error_must_be_number' => 'Tham số “$1” phải là con số.',
	'validator_error_invalid_range' => 'Tham số “$1” phải nằm giữa $2 và $3.',
	'validator_error_accepts_only' => 'Tham số $1 chỉ nhận được {{PLURAL:$3|giá trị|các giá trị}} này: $2.',
);

