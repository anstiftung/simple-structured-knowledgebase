
<?php
enum IngredientLicense: string
{
    case CCBY = 'cc-by'; // 'Namensnennung';
    case CCBYSA = 'cc-by-sa'; //'Namensnennung - Weitergabe unter gleichen Bedingungen';
    case CCBYND = 'cc-by-nd'; //'Namensnennung-Keine Bearbeitung ';
    case CCBYNC = 'cc-by-nc'; //'Namensnennung-Nicht kommerziell ';
    case CCBYNCSA = 'cc-by-nc-sa'; //'Namensnennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen';
    case CCBYNCND = 'cc-by-nc-nd'; //'Namensnennung - Nicht-kommerziell - Keine Bearbeitung ';
}
?>
